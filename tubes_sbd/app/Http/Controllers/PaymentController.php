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
        $carts = $this->cartQuery($request)->get();

        if ($carts->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang masih kosong.');
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
        ]);

        $payment = DB::transaction(function () use ($request, $validated) {
            $carts = $this->cartQuery($request)
                ->lockForUpdate()
                ->get();

            if ($carts->isEmpty()) {
                throw ValidationException::withMessages([
                    'cart' => 'Keranjang masih kosong.',
                ]);
            }

            $summary = $this->buildSummary($carts);

            $payment = Payment::create([
                'user_id'        => $request->user()->id,
                'payment_code'   => $this->generatePaymentCode(),
                'method'         => $validated['method'],
                'status'         => 'paid',
                'subtotal'       => $summary['subtotal'],
                'discount_total' => $summary['discount_total'],
                'total'          => $summary['total'],
                'paid_at'        => now(),
            ]);

            foreach ($summary['items'] as $item) {
                $payment->items()->create([
                    'game_id'          => $item['game']->game_id,
                    'title'            => $item['game']->title,
                    'price'            => $item['price'],
                    'discount_percent' => $item['discount_percent'],
                    'quantity'         => $item['quantity'],
                    'line_total'       => $item['line_total'],
                ]);
            }

            Cart::where('user_id', $request->user()->id)->delete();

            return $payment;
        });

        return redirect()
            ->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    public function history(Request $request)
    {
        $payments = Payment::withCount('items')
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

    private function cartQuery(Request $request)
    {
        return Cart::with(['game.publisher'])
            ->where('user_id', $request->user()->id);
    }

    private function buildSummary(Collection $carts): array
    {
        $items = $carts->map(function (Cart $cart) {
            $game = $cart->game;
            $quantity = max(1, (int) $cart->quantity);
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
