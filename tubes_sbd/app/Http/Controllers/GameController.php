<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class GameController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Home / Search Game
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $search = $request->search;

        $games = Game::with(['publisher', 'developer'])
            ->when($search, function ($query) use ($search) {

                $query->where('title', 'like', '%' . $search . '%');

            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $featuredGame = Game::with('publisher')->latest()->first();

        return view('welcome', compact('games', 'search', 'featuredGame'));
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Game
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $game = Game::with(['publisher', 'developer', 'screenshots', 'genres'])
            ->findOrFail($id);

        return view('game.show', compact('game'));
    }
}