<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function checkout(Request $request)
    {
        $selectionMode = $request->boolean('selection') || $request->has('cart_ids');
        $cartIds = $selectionMode ? $this->requestedCartIds($request) : null;

        if ($selectionMode && empty($cartIds)) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Pilih minimal satu game untuk checkout.');
        }

        $carts = $this->cartQuery($request, $cartIds)->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', $selectionMode ? 'Game yang dipilih tidak ditemukan di cart.' : 'Keranjang masih kosong.');
        }

        return view('payments.checkout', [
            'carts'          => $carts,
            'summary'        => $this->buildSummary($carts),
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'method' => ['required', 'in:bank_transfer,qris,e_wallet,card'],
            'cart_ids' => ['sometimes', 'array'],
            'cart_ids.*' => ['integer'],
        ]);

        $payment = DB::transaction(function () use ($request, $validated) {
            $cartIds = $this->requestedCartIds($request);

            if ($request->has('cart_ids') && empty($cartIds)) {
                throw ValidationException::withMessages([
                    'cart' => 'Pilih minimal satu game untuk checkout.',
                ]);
            }

            $carts = $this->cartQuery($request, $cartIds)
                ->lockForUpdate()
                ->get();

            if ($carts->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang masih kosong.',
                ]);
            }

            $summary = $this->buildSummary($carts);

            $payment = Payment::create([
                'user_id'      => $request->user()->id,
                'payment_code' => $this->generatePaymentCode(),
                'method'       => $validated['method'],
                'status'       => Payment::STATUS_PAID,
                'paid_at'      => now(),
            ]);

            foreach ($summary['items'] as $item) {
                $payment->items()->create([
                    'game_id'          => $item['game']->game_id,
                    'title'            => $item['game']->title,
                    'unit_price'       => $item['price'],
                    'discount_percent' => $item['discount_percent'],
                    'quantity'         => $item['quantity'],
                ]);
            }

            $deleteQuery = Cart::where('user_id', $request->user()->id);

            if ($cartIds !== null) {
                $deleteQuery->whereIn('id', $cartIds);
            }

            $deleteQuery->delete();

            return $payment;
        });

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    public function history(Request $request)
    {
        $payments = Payment::withCount('items')
            ->with('items')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('payments.history', compact('payments'));
    }

    public function show(Request $request, Payment $payment)
    {
        abort_unless(
            $payment->user_id === $request->user()->id || $request->user()->is_admin,
            403
        );

        $payment->load(['items.game.publisher']);

        return view('payments.show', compact('payment'));
    }

    private function cartQuery(Request $request, ?array $cartIds = null)
    {
        $query = Cart::with(['game.publisher', 'game.detail'])
            ->where('user_id', $request->user()->id);

        if ($cartIds !== null) {
            $query->whereIn('id', $cartIds);
        }

        return $query;
    }

    private function requestedCartIds(Request $request): ?array
    {
        if (! $request->has('cart_ids')) {
            return null;
        }

        return collect($request->input('cart_ids', []))
            ->flatten()
            ->filter(fn ($id) => is_numeric($id) && (int) $id > 0)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();
    }

    private function buildSummary(Collection $carts): array
    {
        $items = $carts->map(function (Cart $cart) {
            $game = $cart->game;
            $quantity = 1;
            $price = (float) ($game->price ?? 0);
            $discountPercent = min(100, max(0, (int) ($game->discount_percent ?? 0)));
            $lineSubtotal = $price * $quantity;
            $lineDiscount = $lineSubtotal * ($discountPercent / 100);
            $lineTotal = max(0, $lineSubtotal - $lineDiscount);

            return [
                'cart'             => $cart,
                'game'             => $game,
                'quantity'         => $quantity,
                'price'            => $price,
                'discount_percent' => $discountPercent,
                'line_subtotal'    => $lineSubtotal,
                'line_discount'    => $lineDiscount,
                'line_total'       => $lineTotal,
            ];
        });

        return [
            'items'          => $items,
            'subtotal'       => $items->sum('line_subtotal'),
            'discount_total' => $items->sum('line_discount'),
            'total'          => $items->sum('line_total'),
            'quantity'       => $items->sum('quantity'),
        ];
    }

    private function generatePaymentCode(): string
    {
        do {
            $code = 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Payment::where('payment_code', $code)->exists());

        return $code;
    }

    private function paymentMethods(): array
    {
        return [
            'bank_transfer' => 'Bank Transfer',
            'qris'          => 'QRIS',
            'e_wallet'      => 'E-Wallet',
            'card'          => 'Kartu Debit/Kredit',
        ];
    }
}
