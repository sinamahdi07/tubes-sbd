<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Genre;
use Illuminate\Http\Request;

class AdminGenreController extends Controller
{
    public function index(Request $request)
    {
        $query = Genre::withCount('games');

        if ($request->boolean('trash')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $genres = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.genres.index', compact('genres'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:genres,name']);

        Genre::create(['name' => $request->name]);

        return back()->with('success', 'Genre berhasil ditambahkan!');
    }

    public function update(Request $request, Genre $genre)
    {
        $request->validate(['name' => 'required|string|max:100|unique:genres,name,' . $genre->genre_id . ',genre_id']);

        $genre->update(['name' => $request->name]);

        return back()->with('success', 'Genre berhasil diperbarui!');
    }

    public function destroy(Genre $genre)
    {
        $genre->delete();

        return back()->with('success', 'Genre berhasil dipindahkan ke trash!');
    }

    public function restore(int $genre)
    {
        Genre::onlyTrashed()->findOrFail($genre)->restore();

        return redirect()->route('admin.genres.index', ['trash' => 1])
            ->with('success', 'Genre berhasil direstore!');
    }
}
