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
            ->get();

        return view('welcome', compact('games', 'search'));
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Game
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $game = Game::with(['publisher', 'developer'])
            ->findOrFail($id);

        return view('game.show', compact('game'));
    }
}