<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use Illuminate\Http\Request;

class AdminPlatformController extends Controller
{
    public function index(Request $request)
    {
        $query = Platform::query();

        if ($request->boolean('trash')) {
            $query->onlyTrashed();
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        $platforms = $query->latest()->paginate(15)->withQueryString();

        return view('admin.platforms.index', compact('platforms'));
    }

    public function create()
    {
        return view('admin.platforms.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:platforms,slug',
        ]);

        Platform::create($validated);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform berhasil ditambahkan!');
    }

    public function edit(Platform $platform)
    {
        return view('admin.platforms.edit', compact('platform'));
    }

    public function update(Request $request, Platform $platform)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100|unique:platforms,slug,'.$platform->platform_id.',platform_id',
        ]);

        $platform->update($validated);

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform berhasil diperbarui!');
    }

    public function destroy(Platform $platform)
    {
        $platform->delete();

        return redirect()->route('admin.platforms.index')
            ->with('success', 'Platform berhasil dipindahkan ke trash!');
    }

    public function restore(int $platform)
    {
        Platform::onlyTrashed()->findOrFail($platform)->restore();

        return redirect()->route('admin.platforms.index', ['trash' => 1])
            ->with('success', 'Platform berhasil direstore!');
    }

    public function forceDestroy(int $platform)
    {
        Platform::onlyTrashed()->findOrFail($platform)->forceDelete();

        return redirect()->route('admin.platforms.index', ['trash' => 1])
            ->with('success', 'Platform berhasil dihapus permanen!');
    }
}
