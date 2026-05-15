<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class HomeController extends Controller
{
    public function search(Request $request)
{
    $search = $request->search;

    $games = Game::with(['publisher', 'genres'])

        ->where('title', 'LIKE', "%{$search}%")

        ->paginate(12)

        ->withQueryString();

    return view('search', compact(
        'games',
        'search'
    ));
}
}
