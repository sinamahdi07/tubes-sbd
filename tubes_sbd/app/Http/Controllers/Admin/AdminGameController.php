<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\GameDetail;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Genre;
use App\Models\Category;
use App\Models\Platform;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with(['developer', 'publisher', 'genres', 'detail']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', fn($q) => $q->where('genres.genre_id', $request->genre));
        }

        $games  = $query->latest()->paginate(15)->withQueryString();
        $genres = Genre::orderBy('name')->get();

        return view('admin.games.index', compact('games', 'genres'));
    }

    public function create()
    {
        $developers = Developer::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $genres     = Genre::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();
        $platforms  = Platform::orderBy('name')->get();

        return view('admin.games.create', compact('developers', 'publishers', 'genres', 'categories', 'platforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:200',
            'description'          => 'nullable|string',
            'price'                => 'required|numeric|min:0',
            'release_date'         => 'nullable|date',
            'thumbnail_url'        => 'nullable|url|max:500',
            'developer_id'         => 'required|exists:developers,developer_id',
            'publisher_id'         => 'required|exists:publishers,publisher_id',
            // Detail fields
            'discount'             => 'nullable|integer|min:0|max:100',
            'short_description'    => 'nullable|string|max:1000',
            'website'              => 'nullable|url|max:1000',
            'minimum_requirements' => 'nullable|string',
            // Relations
            'genres'               => 'nullable|array',
            'genres.*'             => 'exists:genres,genre_id',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,category_id',
            'platforms'            => 'nullable|array',
            'platforms.*'          => 'exists:platforms,platform_id',
            'screenshots'          => 'nullable|array',
            'screenshots.*.url'    => 'required|url|max:500',
            'screenshots.*.order'  => 'nullable|integer|min:0',
            'trailers'             => 'nullable|array',
            'trailers.*.url'       => 'required|url|max:500',
            'trailers.*.title'     => 'nullable|string|max:200',
        ]);

        $game = Game::create([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'release_date'  => $validated['release_date'] ?? null,
            'thumbnail_url' => $validated['thumbnail_url'] ?? null,
            'developer_id'  => $validated['developer_id'],
            'publisher_id'  => $validated['publisher_id'],
        ]);

        // Save game_details
        GameDetail::create([
            'game_id'              => $game->game_id,
            'discount'             => $validated['discount'] ?? 0,
            'short_description'    => $validated['short_description'] ?? null,
            'website'              => $validated['website'] ?? null,
            'minimum_requirements' => $validated['minimum_requirements'] ?? null,
        ]);

        if (!empty($validated['genres'])) {
            $game->genres()->sync($validated['genres']);
        }
        if (!empty($validated['categories'])) {
            $game->categories()->sync($validated['categories']);
        }
        if (!empty($validated['platforms'])) {
            $game->platforms()->sync($validated['platforms']);
        }

        if (!empty($validated['screenshots'])) {
            foreach ($validated['screenshots'] as $index => $screenshotData) {
                if (!empty($screenshotData['url'])) {
                    $game->screenshots()->create([
                        'url'   => $screenshotData['url'],
                        'order' => $screenshotData['order'] ?? $index,
                    ]);
                }
            }
        }

        if (!empty($validated['trailers'])) {
            foreach ($validated['trailers'] as $index => $trailerData) {
                if (!empty($trailerData['url'])) {
                    $game->trailers()->create([
                        'url'   => $trailerData['url'],
                        'title' => $trailerData['title'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan!');
    }

    public function show(Game $game)
    {
        $game->load(['developer', 'publisher', 'genres', 'screenshots', 'platforms', 'detail']);
        return view('admin.games.show', compact('game'));
    }

    public function edit(Game $game)
    {
        $developers         = Developer::orderBy('name')->get();
        $publishers         = Publisher::orderBy('name')->get();
        $genres             = Genre::orderBy('name')->get();
        $categories         = Category::orderBy('name')->get();
        $platforms          = Platform::orderBy('name')->get();
        $selectedGenres     = $game->genres->pluck('genre_id')->toArray();
        $selectedCategories = $game->categories->pluck('category_id')->toArray();
        $selectedPlatforms  = $game->platforms->pluck('platform_id')->toArray();

        $game->load(['screenshots', 'trailers', 'detail']);

        return view('admin.games.edit', compact(
            'game', 'developers', 'publishers', 'genres', 'categories', 'platforms',
            'selectedGenres', 'selectedCategories', 'selectedPlatforms'
        ));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:200',
            'description'          => 'nullable|string',
            'price'                => 'required|numeric|min:0',
            'release_date'         => 'nullable|date',
            'thumbnail_url'        => 'nullable|url|max:500',
            'developer_id'         => 'required|exists:developers,developer_id',
            'publisher_id'         => 'required|exists:publishers,publisher_id',
            // Detail fields
            'discount'             => 'nullable|integer|min:0|max:100',
            'short_description'    => 'nullable|string|max:1000',
            'website'              => 'nullable|url|max:1000',
            'minimum_requirements' => 'nullable|string',
            // Relations
            'genres'               => 'nullable|array',
            'genres.*'             => 'exists:genres,genre_id',
            'categories'           => 'nullable|array',
            'categories.*'         => 'exists:categories,category_id',
            'platforms'            => 'nullable|array',
            'platforms.*'          => 'exists:platforms,platform_id',
            'screenshots'          => 'nullable|array',
            'screenshots.*.url'    => 'required|url|max:500',
            'screenshots.*.order'  => 'nullable|integer|min:0',
            'delete_screenshots'   => 'nullable|array',
            'delete_screenshots.*' => 'nullable|exists:game_screenshots,screenshot_id',
            'trailers'             => 'nullable|array',
            'trailers.*.url'       => 'required|url|max:500',
            'trailers.*.title'     => 'nullable|string|max:200',
            'delete_trailers'      => 'nullable|array',
            'delete_trailers.*'    => 'nullable|exists:game_trailers,trailer_id',
        ]);

        $game->update([
            'title'         => $validated['title'],
            'description'   => $validated['description'] ?? null,
            'price'         => $validated['price'],
            'release_date'  => $validated['release_date'] ?? null,
            'thumbnail_url' => $validated['thumbnail_url'] ?? null,
            'developer_id'  => $validated['developer_id'],
            'publisher_id'  => $validated['publisher_id'],
        ]);

        // Upsert game_details
        GameDetail::updateOrCreate(
            ['game_id' => $game->game_id],
            [
                'discount'             => $validated['discount'] ?? 0,
                'short_description'    => $validated['short_description'] ?? null,
                'website'              => $validated['website'] ?? null,
                'minimum_requirements' => $validated['minimum_requirements'] ?? null,
            ]
        );

        $game->genres()->sync($validated['genres'] ?? []);
        $game->categories()->sync($validated['categories'] ?? []);
        $game->platforms()->sync($validated['platforms'] ?? []);

        // Screenshot deletions
        $toDelScreenshots = array_filter($validated['delete_screenshots'] ?? [], fn($id) => !empty($id));
        if (!empty($toDelScreenshots)) {
            $game->screenshots()->whereIn('screenshot_id', $toDelScreenshots)->delete();
        }

        // Screenshot additions
        if (!empty($validated['screenshots'])) {
            foreach ($validated['screenshots'] as $index => $data) {
                if (!empty($data['url'])) {
                    $game->screenshots()->create([
                        'url'   => $data['url'],
                        'order' => $data['order'] ?? $index,
                    ]);
                }
            }
        }

        // Trailer deletions
        $toDelTrailers = array_filter($validated['delete_trailers'] ?? [], fn($id) => !empty($id));
        if (!empty($toDelTrailers)) {
            $game->trailers()->whereIn('trailer_id', $toDelTrailers)->delete();
        }

        // Trailer additions
        if (!empty($validated['trailers'])) {
            foreach ($validated['trailers'] as $index => $data) {
                if (!empty($data['url'])) {
                    $game->trailers()->create([
                        'url'   => $data['url'],
                        'title' => $data['title'] ?? null,
                        'order' => $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil diperbarui!');
    }

    public function destroy(Game $game)
    {
        $game->delete();

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil dihapus!');
    }
}
