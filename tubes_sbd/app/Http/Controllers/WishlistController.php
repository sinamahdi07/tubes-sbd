<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::with(['game.publisher', 'game.detail', 'game.genres'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        $totalItems = $wishlists->count();

        return view('wishlist.index', compact('wishlists', 'totalItems'));
    }

    public function toggle(Game $game)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('game_id', $game->game_id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();

            return back()->with('success', 'Game dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => auth()->id(),
            'game_id' => $game->game_id,
        ]);

        return back()->with('success', 'Game ditambahkan ke wishlist.');
    }
}
