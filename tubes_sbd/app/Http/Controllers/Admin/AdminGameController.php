<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\Genre;
use Illuminate\Http\Request;

class AdminGameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with(['developer', 'publisher', 'genres']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('genre')) {
            $query->whereHas('genres', fn($q) => $q->where('genres.genre_id', $request->genre));
        }

        $games    = $query->latest()->paginate(15)->withQueryString();
        $genres   = Genre::orderBy('name')->get();

        return view('admin.games.index', compact('games', 'genres'));
    }

    public function create()
    {
        $developers = Developer::orderBy('name')->get();
        $publishers = Publisher::orderBy('name')->get();
        $genres     = Genre::orderBy('name')->get();

        return view('admin.games.create', compact('developers', 'publishers', 'genres'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:200',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'release_date'  => 'nullable|date',
            'thumbnail_url' => 'nullable|url|max:500',
            'developer_id'  => 'required|exists:developers,developer_id',
            'publisher_id'  => 'required|exists:publishers,publisher_id',
            'genres'        => 'nullable|array',
            'genres.*'      => 'exists:genres,genre_id',
            'screenshots'   => 'nullable|array',
            'screenshots.*.url' => 'required|url|max:500',
            'screenshots.*.order' => 'nullable|integer|min:0',
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

        if (!empty($validated['genres'])) {
            $game->genres()->sync($validated['genres']);
        }

        if (!empty($validated['screenshots'])) {
            foreach ($validated['screenshots'] as $index => $screenshotData) {
                if (!empty($screenshotData['url'])) {
                    $game->screenshots()->create([
                        'url' => $screenshotData['url'],
                        'order' => $screenshotData['order'] ?? $index,
                    ]);
                }
            }
        }

        return redirect()->route('admin.games.index')
            ->with('success', 'Game berhasil ditambahkan!');
    }

    public function show(Game $game)
    {
        $game->load(['developer', 'publisher', 'genres', 'screenshots']);
        return view('admin.games.show', compact('game'));
    }

    public function edit(Game $game)
    {
        $developers    = Developer::orderBy('name')->get();
        $publishers    = Publisher::orderBy('name')->get();
        $genres        = Genre::orderBy('name')->get();
        $selectedGenres = $game->genres->pluck('genre_id')->toArray();
        
        // Load screenshots
        $game->load('screenshots');

        return view('admin.games.edit', compact('game', 'developers', 'publishers', 'genres', 'selectedGenres'));
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:200',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'release_date'  => 'nullable|date',
            'thumbnail_url' => 'nullable|url|max:500',
            'developer_id'  => 'required|exists:developers,developer_id',
            'publisher_id'  => 'required|exists:publishers,publisher_id',
            'genres'        => 'nullable|array',
            'genres.*'      => 'exists:genres,genre_id',
            'screenshots'   => 'nullable|array',
            'screenshots.*.url' => 'required|url|max:500',
            'screenshots.*.order' => 'nullable|integer|min:0',
            'delete_screenshots' => 'nullable|array',
            'delete_screenshots.*' => 'exists:game_screenshots,screenshot_id',
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

        $game->genres()->sync($validated['genres'] ?? []);

        // Handle screenshot deletions
        if (!empty($validated['delete_screenshots'])) {
            $game->screenshots()->whereIn('screenshot_id', $validated['delete_screenshots'])->delete();
        }

        // Handle screenshot updates and additions
        if (!empty($validated['screenshots'])) {
            foreach ($validated['screenshots'] as $index => $screenshotData) {
                if (!empty($screenshotData['url'])) {
                    $game->screenshots()->create([
                        'url' => $screenshotData['url'],
                        'order' => $screenshotData['order'] ?? $index,
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
