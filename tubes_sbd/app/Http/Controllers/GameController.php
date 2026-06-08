<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\Genre;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $selectedGenre = $request->query('genre');
        $selectedCategory = $request->query('category');
        $selectedDeveloper = $request->query('developer');
        $selectedPublisher = $request->query('publisher');

        $genres = Genre::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        $baseQuery = Game::with(['publisher', 'developer', 'genres', 'categories', 'detail'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', $search.'%');
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

        $discountedGames = (clone $baseQuery)
            ->whereHas('detail', function ($query) {
                $query->where('discount', '>', 0);
            })
            ->orderByDesc('release_date')
            ->take(24)
            ->get()
            ->sortByDesc(fn (Game $game) => $game->discount_percent)
            ->take(12)
            ->values();

        $showcaseQuery = fn () => (clone $baseQuery)
            ->with(['screenshots'])
            ->withCount('reviews');

        $showcaseNewReleases = $showcaseQuery()
            ->orderByDesc('release_date')
            ->latest()
            ->take(8)
            ->get();

        $showcaseTopSellers = $showcaseQuery()
            ->withPaidPurchasesCount()
            ->orderByDesc('paid_purchases_count')
            ->orderByDesc('release_date')
            ->take(8)
            ->get();

        $showcaseUpcoming = $showcaseQuery()
            ->whereDate('release_date', '>=', now()->toDateString())
            ->orderBy('release_date')
            ->take(8)
            ->get();

        if ($showcaseUpcoming->isEmpty()) {
            $showcaseUpcoming = $showcaseQuery()
                ->orderByDesc('release_date')
                ->skip(2)
                ->take(8)
                ->get();
        }

        $showcaseSpecials = $showcaseQuery()
            ->whereHas('detail', function ($query) {
                $query->where('discount', '>', 0);
            })
            ->orderByDesc('release_date')
            ->take(8)
            ->get();

        $showcaseF2p = $showcaseQuery()
            ->where('price', '<=', 0)
            ->withPaidPurchasesCount()
            ->orderByDesc('paid_purchases_count')
            ->take(8)
            ->get();

        if ($showcaseF2p->isEmpty()) {
            $showcaseF2p = $showcaseQuery()
                ->orderBy('price')
                ->orderByDesc('release_date')
                ->take(8)
                ->get();
        }

        $showcaseTabs = collect([
            [
                'key' => 'new-releases',
                'label' => 'Rilisan Terbaru Terpopuler',
                'games' => $showcaseNewReleases,
            ],
            [
                'key' => 'top-sellers',
                'label' => 'Penjualan Terlaris',
                'games' => $showcaseTopSellers,
            ],
            [
                'key' => 'popular-upcoming',
                'label' => 'Populer dan Mendatang',
                'games' => $showcaseUpcoming,
            ],
            [
                'key' => 'specials',
                'label' => 'Spesial',
                'games' => $showcaseSpecials,
            ],
            [
                'key' => 'f2p',
                'label' => 'F2P Ngetren',
                'games' => $showcaseF2p,
            ],
        ])->filter(fn (array $tab) => $tab['games']->isNotEmpty())->values();

        $browseLabelMap = [
            'action' => 'AKSI',
            'adventure' => 'PETUALANGAN',
            'fantasy' => 'FANTASI',
            'indie' => 'INDIE',
            'racing' => 'BALAPAN',
            'rpg' => 'RPG',
            'sci-fi' => 'SCI-FI',
            'simulation' => 'SIMULASI',
            'strategy' => 'STRATEGI',
            'survival' => 'SURVIVAL',
            'visual novel' => 'NOVEL VISUAL',
        ];

        $browsePreferredOrder = [
            'anime',
            'adventure',
            'racing',
            'rogue-like',
            'visual novel',
            'action',
            'rpg',
            'simulation',
            'strategy',
            'survival',
            'fantasy',
            'sci-fi',
            'indie',
        ];

        $browsePalettes = [
            ['rgba(142, 42, 58, 0.72)', 'rgba(91, 20, 54, 0.62)'],
            ['rgba(131, 85, 27, 0.72)', 'rgba(87, 48, 16, 0.62)'],
            ['rgba(98, 125, 47, 0.72)', 'rgba(39, 91, 58, 0.62)'],
            ['rgba(38, 114, 129, 0.72)', 'rgba(43, 78, 127, 0.62)'],
            ['rgba(59, 42, 127, 0.72)', 'rgba(69, 31, 113, 0.62)'],
            ['rgba(26, 89, 146, 0.72)', 'rgba(24, 54, 109, 0.62)'],
        ];

        $fallbackBrowseImages = $featuredGames
            ->merge($recommendedGames)
            ->pluck('thumbnail_url')
            ->filter()
            ->values();

        $browseCategoryCards = $genres
            ->sortBy(function (Genre $genre) use ($browsePreferredOrder) {
                $position = array_search(strtolower($genre->name), $browsePreferredOrder, true);

                return $position === false ? 100 + $genre->genre_id : $position;
            })
            ->map(function (Genre $genre, int $index) use ($browseLabelMap, $browsePalettes, $fallbackBrowseImages) {
                $gamesForGenre = Game::query()
                    ->whereHas('genres', function ($query) use ($genre) {
                        $query->where('genres.genre_id', $genre->genre_id);
                    })
                    ->orderByDesc('release_date')
                    ->take(4)
                    ->get();

                $images = $gamesForGenre
                    ->pluck('thumbnail_url')
                    ->filter()
                    ->values();

                if ($images->isEmpty()) {
                    $images = $fallbackBrowseImages->take(4)->values();
                }

                if ($images->isEmpty()) {
                    return null;
                }

                $palette = $browsePalettes[$index % count($browsePalettes)];
                $nameKey = strtolower($genre->name);

                return [
                    'label' => $browseLabelMap[$nameKey] ?? strtoupper($genre->name),
                    'image' => $images->first(),
                    'overlay' => "linear-gradient(135deg, {$palette[0]}, {$palette[1]})",
                    'url' => route('games.search', ['genre' => $genre->genre_id]),
                ];
            })
            ->filter()
            ->take(12)
            ->values();

        $budgetGamesSource = (clone $baseQuery)
            ->with(['screenshots'])
            ->orderByDesc('release_date')
            ->take(40)
            ->get();

        $budgetDeals = $budgetGamesSource
            ->filter(fn (Game $game) => $game->final_price <= 90000)
            ->sortBy(fn (Game $game) => sprintf('%012.2f-%s', $game->final_price, $game->title))
            ->take(5)
            ->values();

        if ($budgetDeals->isEmpty()) {
            $budgetDeals = $budgetGamesSource->take(5)->values();
        }

        $budgetQuickLinks = collect([
            [
                'label' => 'Di Bawah Rp 90 000',
                'url' => route('games.search', ['max_price' => 90000]),
            ],
            [
                'label' => 'Di Bawah Rp 45 000',
                'url' => route('games.search', ['max_price' => 45000]),
            ],
        ]);

        return view('welcome', compact(
            'browseCategoryCards',
            'budgetDeals',
            'budgetQuickLinks',
            'featuredGame',
            'featuredGames',
            'categories',
            'genres',
            'discountedGames',
            'popularGames',
            'recommendedGames',
            'showcaseTabs',
            'selectedCategory',
            'selectedDeveloper',
            'selectedPublisher',
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

        $isWishlisted = false;

        if (auth()->check()) {
            $isWishlisted = Wishlist::where('user_id', auth()->id())
                ->where('game_id', $game->game_id)
                ->exists();
        }

        $genreIds = $game->genres->pluck('genre_id');
        $relatedGames = Game::with(['detail', 'genres'])
            ->where('game_id', '!=', $game->game_id)
            ->when($genreIds->isNotEmpty(), function ($query) use ($genreIds) {
                $query->whereHas('genres', function ($genreQuery) use ($genreIds) {
                    $genreQuery->whereIn('genres.genre_id', $genreIds);
                });
            })
            ->orderByDesc('release_date')
            ->take(4)
            ->get();

        if ($relatedGames->count() < 4) {
            $fallbackGames = Game::with(['detail', 'genres'])
                ->where('game_id', '!=', $game->game_id)
                ->whereNotIn('game_id', $relatedGames->pluck('game_id'))
                ->orderByDesc('release_date')
                ->take(4 - $relatedGames->count())
                ->get();

            $relatedGames = $relatedGames->concat($fallbackGames)->values();
        }

        return view('game.show', compact('game', 'isWishlisted', 'relatedGames'));
    }
}
