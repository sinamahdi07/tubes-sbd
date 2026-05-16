<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('user')
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
            'total_revenue'  => Payment::where('status', 'paid')->sum('total'),
            'pending_total'  => Payment::where('status', 'pending')->sum('total'),
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
}
