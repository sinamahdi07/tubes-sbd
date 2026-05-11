<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Developer;
use Illuminate\Http\Request;

class AdminDeveloperController extends Controller
{
    public function index(Request $request)
    {
        $query = Developer::withCount('games');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $developers = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.developers.index', compact('developers'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:200|unique:developers,name']);

        Developer::create(['name' => $request->name]);

        return back()->with('success', 'Developer berhasil ditambahkan!');
    }

    public function update(Request $request, Developer $developer)
    {
        $request->validate(['name' => 'required|string|max:200|unique:developers,name,' . $developer->developer_id . ',developer_id']);

        $developer->update(['name' => $request->name]);

        return back()->with('success', 'Developer berhasil diperbarui!');
    }

    public function destroy(Developer $developer)
    {
        if ($developer->games()->count() > 0) {
            return back()->with('error', 'Developer tidak bisa dihapus karena masih memiliki game!');
        }

        $developer->delete();

        return back()->with('success', 'Developer berhasil dihapus!');
    }
}
