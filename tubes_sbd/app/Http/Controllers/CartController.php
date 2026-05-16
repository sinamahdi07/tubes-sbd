<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Game $game)
    {
        $user = auth()->user();

        // cek apakah game sudah ada di cart
        $existingCart = Cart::where('user_id', $user->id)
            ->where('game_id', $game->game_id)
            ->first();

        if($existingCart){

            $existingCart->increment('quantity');

        } else {

            Cart::create([
                'user_id' => $user->id,
                'game_id' => $game->game_id,
                'quantity' => 1
            ]);
        }

        return back()->with('success', 'Game added to cart!');
    }

    public function index()
    {
        $carts = Cart::with(['game.publisher'])
            ->where('user_id', auth()->id())
            ->get();

        return view('cart.index', compact('carts'));
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Game removed from cart!');
    }
}