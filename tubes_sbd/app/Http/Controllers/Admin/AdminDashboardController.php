<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\GameReview;
use App\Models\Genre;
use App\Models\Payment;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_games' => Game::count(),
            'total_users' => User::count(),
            'total_developers' => Developer::count(),
            'total_publishers' => Publisher::count(),
            'total_genres' => Genre::count(),
            'total_categories' => Category::count(),
            'total_reviews' => GameReview::count(),
            'total_payments' => Payment::count(),
            'total_revenue' => $this->paymentTotal('paid'),
        ];

        $recentGames = Game::with(['developer', 'publisher'])
            ->orderByRaw('CASE WHEN release_date IS NULL THEN 1 ELSE 0 END')
            ->orderByDesc('release_date')
            ->latest('created_at')
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        $recentPayments = Payment::with('user')
            ->with('items')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentGames', 'recentUsers', 'recentPayments'));
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
