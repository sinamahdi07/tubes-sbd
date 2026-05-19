<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Category;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $selectedGenre = $request->query('genre');
        $selectedCategory = $request->query('category');

        $genres = Genre::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $baseQuery = Game::with(['publisher', 'developer', 'genres', 'categories', 'detail'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', $search . '%');
            })
            ->when($selectedGenre, function ($query) use ($selectedGenre) {
                $query->whereHas('genres', function ($q) use ($selectedGenre) {
                    $q->where('genres.genre_id', $selectedGenre);
                });
            })
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->whereHas('categories', function ($q) use ($selectedCategory) {
                    $q->where('categories.category_id', $selectedCategory);
                });
            });

        $featuredGames = (clone $baseQuery)
            ->inRandomOrder()
            ->take(4)
            ->get();

        $featuredGame = $featuredGames->first();

        $recommendedGames = (clone $baseQuery)
            ->inRandomOrder()
            ->take(5)
            ->get();

        $popularGames = (clone $baseQuery)
            ->withPaidPurchasesCount()
            ->orderByDesc('paid_purchases_count')
            ->latest('release_date')
            ->take(5)
            ->get();

        $newReleases = (clone $baseQuery)
            ->orderByDesc('release_date')
            ->latest()
            ->take(6)
            ->get();

        return view('welcome', compact(
            'featuredGame',
            'featuredGames',
            'categories',
            'genres',
            'newReleases',
            'popularGames',
            'recommendedGames',
            'selectedCategory',
            'selectedGenre',
            'search'
        ));
    }

    public function show($id)
    {
        $game = Game::with([
            'publisher',
            'developer',
            'screenshots',
            'genres',
            'categories',
            'platforms',
            'detail',
        ])->findOrFail($id);

        return view('game.show', compact('game'));
    }
}
