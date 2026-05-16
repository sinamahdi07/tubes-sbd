<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Genre;

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

        $genres = Genre::all();

        $games = Game::with(['publisher', 'developer', 'genres', 'detail'])
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%');
            })
            ->when($request->genre, function ($query) use ($request) {
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('genres.genre_id', $request->genre);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $featuredGame = Game::with(['publisher', 'genres', 'detail'])
            ->when($request->genre, function ($query) use ($request) {
                $query->whereHas('genres', function ($q) use ($request) {
                    $q->where('genres.genre_id', $request->genre);
                });
            })
            ->latest()->first();

        return view('welcome', compact('games', 'search', 'featuredGame', 'genres'));
    }

    /*
    |--------------------------------------------------------------------------
    | Detail Game
    |--------------------------------------------------------------------------
    */

    public function show($id)
    {
        $game = Game::with([
            'publisher',
            'developer',
            'screenshots',
            'genres',
            'platforms',
            'detail',
        ])->findOrFail($id);

        return view('game.show', compact('game'));
    }
}