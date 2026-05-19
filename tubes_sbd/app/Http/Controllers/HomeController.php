<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use App\Models\Genre;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function search(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $selectedGenre = $request->query('genre');
        $selectedCategory = $request->query('category');
        $sort = $request->query('sort') === 'popular' ? 'popular' : 'latest';

        $categories = Category::orderBy('name')->get();
        $genres = Genre::orderBy('name')->get();

        $gamesQuery = Game::with(['publisher', 'genres', 'categories', 'detail'])
            ->withPaidPurchasesCount()
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

        if ($sort === 'popular') {
            $gamesQuery
                ->orderByDesc('paid_purchases_count')
                ->latest('release_date');
        } else {
            $gamesQuery->latest();
        }

        $games = $gamesQuery
            ->paginate(12)
            ->withQueryString();

        return view('search', compact(
            'categories',
            'games',
            'genres',
            'search',
            'sort',
            'selectedCategory',
            'selectedGenre'
        ));
    }

    public function autocomplete(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        if ($search === '') {
            return response()->json([]);
        }

        $games = Game::query()
            ->select(['game_id', 'title', 'price', 'thumbnail_url'])
            ->when($request->query('genre'), function ($query, $genreId) {
                $query->whereHas('genres', function ($q) use ($genreId) {
                    $q->where('genres.genre_id', $genreId);
                });
            })
            ->when($request->query('category'), function ($query, $categoryId) {
                $query->whereHas('categories', function ($q) use ($categoryId) {
                    $q->where('categories.category_id', $categoryId);
                });
            })
            ->where('title', 'like', $search . '%')
            ->orderBy('title')
            ->take(8)
            ->get()
            ->map(fn ($game) => [
                'game_id' => $game->game_id,
                'title' => $game->title,
                'price' => (float) $game->price,
                'thumbnail_url' => $game->thumbnail_url,
                'url' => url('/game/' . $game->game_id),
            ]);

        return response()->json($games);
    }
}
