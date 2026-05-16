<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Genre;
use App\Models\Payment;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_games'      => Game::count(),
            'total_users'      => User::count(),
            'total_developers' => Developer::count(),
            'total_publishers' => Publisher::count(),
            'total_genres'     => Genre::count(),
            'total_payments'   => Payment::count(),
            'total_revenue'    => Payment::where('status', 'paid')->sum('total'),
        ];

        $recentGames = Game::with(['developer', 'publisher'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        $recentPayments = Payment::with('user')
            ->withCount('items')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentGames', 'recentUsers', 'recentPayments'));
    }
}
