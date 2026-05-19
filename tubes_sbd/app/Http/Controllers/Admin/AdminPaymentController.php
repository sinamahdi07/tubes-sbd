<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user')
            ->with('items')
            ->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('payment_code', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        $payments = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total_payments' => Payment::count(),
            'paid_payments'  => Payment::where('status', 'paid')->count(),
            'total_revenue'  => $this->paymentTotal('paid'),
            'pending_total'  => $this->paymentTotal('pending'),
        ];

        $statuses = Payment::query()
            ->select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        $methods = Payment::query()
            ->select('method')
            ->distinct()
            ->orderBy('method')
            ->pluck('method');

        return view('admin.payments.index', compact('payments', 'stats', 'statuses', 'methods'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'items.game.publisher']);

        return view('admin.payments.show', compact('payment'));
    }

    private function paymentTotal(?string $status = null): float
    {
        if (Schema::hasColumn('payments', 'total')) {
            return (float) Payment::query()
                ->when($status, fn ($query) => $query->where('status', $status))
                ->sum('total');
        }

        if (! Schema::hasTable('payment_items')) {
            return 0.0;
        }

        $priceColumn = Schema::hasColumn('payment_items', 'unit_price') ? 'unit_price' : 'price';
        $lineTotalExpression = "payment_items.{$priceColumn} * payment_items.quantity * (1 - (payment_items.discount_percent / 100))";

        return (float) DB::table('payment_items')
            ->join('payments', 'payments.id', '=', 'payment_items.payment_id')
            ->when($status, fn ($query) => $query->where('payments.status', $status))
            ->selectRaw("COALESCE(SUM({$lineTotalExpression}), 0) as total")
            ->value('total');
    }
}
