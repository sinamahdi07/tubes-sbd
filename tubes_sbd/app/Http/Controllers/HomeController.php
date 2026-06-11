<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function search(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $selectedGenre = $request->query('genre');
        $selectedCategory = $request->query('category');
        $selectedDeveloper = $request->query('developer');
        $selectedPublisher = $request->query('publisher');
        $sort = $request->query('sort') === 'popular' ? 'popular' : 'latest';
        $maxPrice = $request->integer('max_price') > 0 ? $request->integer('max_price') : null;
        $discountedOnly = $request->boolean('discount');

        $categories = Category::orderBy('name')->get();
        $genres = Genre::orderBy('name')->get();

        $gamesQuery = Game::with(['publisher', 'genres', 'categories', 'detail', 'platforms', 'screenshots'])
            ->withPaidPurchasesCount()
            ->withCount('reviews')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%');
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
            })
            ->when($selectedDeveloper, function ($query) use ($selectedDeveloper) {
                $query->where('developer_id', $selectedDeveloper);
            })
            ->when($selectedPublisher, function ($query) use ($selectedPublisher) {
                $query->where('publisher_id', $selectedPublisher);
            })
            ->when($discountedOnly, function ($query) {
                $query->whereHas('detail', function ($detailQuery) {
                    $detailQuery->where('discount', '>', 0);
                });
            })
            ->when($maxPrice, function ($query) use ($maxPrice) {
                $query->where(function ($priceQuery) use ($maxPrice) {
                    $priceQuery
                        ->where('games.price', '<=', $maxPrice)
                        ->orWhereHas('detail', function ($detailQuery) use ($maxPrice) {
                            $detailQuery->whereRaw(
                                'games.price * (1 - COALESCE(game_details.discount, 0) / 100.0) <= ?',
                                [$maxPrice]
                            );
                        });
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

        // Logika untuk menentukan judul halaman (misal: "Games developed by Valve")
        $pageTitle = 'Semua Game';
        $resultsCount = null;

        if ($selectedDeveloper) {
            $dev = Developer::withCount('games')->find($selectedDeveloper);
            if ($dev) {
                $pageTitle = 'Games developed by '.$dev->name;
                $resultsCount = $dev->games_count;
            }
        } elseif ($selectedPublisher) {
            $pub = Publisher::withCount('games')->find($selectedPublisher);
            if ($pub) {
                $pageTitle = 'Games published by '.$pub->name;
                $resultsCount = $pub->games_count;
            }
        } elseif ($search) {
            $pageTitle = 'Search Results for: '.$search;
        } elseif ($discountedOnly) {
            $pageTitle = 'Game yang lagi diskon';
        } elseif ($maxPrice) {
            $pageTitle = 'Game di bawah Rp '.number_format($maxPrice, 0, ',', '.');
        }

        return view('search', compact(
            'categories',
            'games',
            'genres',
            'discountedOnly',
            'maxPrice',
            'search',
            'sort',
            'selectedCategory',
            'selectedGenre',
            'pageTitle',
            'resultsCount'
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
            ->where('title', 'like', '%'.$search.'%')
            ->orderBy('title')
            ->take(8)
            ->get()
            ->map(fn ($game) => [
                'game_id' => $game->game_id,
                'title' => $game->title,
                'price' => (float) $game->price,
                'thumbnail_url' => $game->thumbnail_url,
                'url' => url('/game/'.$game->game_id),
            ]);

        return response()->json($games);
    }
}
