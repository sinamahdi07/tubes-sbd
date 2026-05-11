<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\User;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Genre;

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
        ];

        $recentGames = Game::with(['developer', 'publisher'])
            ->latest()
            ->take(5)
            ->get();

        $recentUsers = User::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentGames', 'recentUsers'));
    }
}
