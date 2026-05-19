<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Publisher;
use Illuminate\Http\Request;

class AdminPublisherController extends Controller
{
    public function index(Request $request)
    {
        $query = Publisher::withCount('games');

        if ($request->boolean('trash')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $publishers = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.publishers.index', compact('publishers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:200|unique:publishers,name']);

        Publisher::create(['name' => $request->name]);

        return back()->with('success', 'Publisher berhasil ditambahkan!');
    }

    public function update(Request $request, Publisher $publisher)
    {
        $request->validate(['name' => 'required|string|max:200|unique:publishers,name,' . $publisher->publisher_id . ',publisher_id']);

        $publisher->update(['name' => $request->name]);

        return back()->with('success', 'Publisher berhasil diperbarui!');
    }

    public function destroy(Publisher $publisher)
    {
        if ($publisher->games()->count() > 0) {
            return back()->with('error', 'Publisher tidak bisa dihapus karena masih memiliki game!');
        }

        $publisher->delete();

        return back()->with('success', 'Publisher berhasil dipindahkan ke trash!');
    }

    public function restore(int $publisher)
    {
        Publisher::onlyTrashed()->findOrFail($publisher)->restore();

        return redirect()->route('admin.publishers.index', ['trash' => 1])
            ->with('success', 'Publisher berhasil direstore!');
    }
}
