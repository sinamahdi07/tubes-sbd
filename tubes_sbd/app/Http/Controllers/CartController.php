<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Game;
use App\Models\Payment;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Game $game)
    {
        $user = auth()->user();

        $alreadyPurchased = Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
            ->where('payments.user_id', $user->id)
            ->where('payment_items.game_id', $game->game_id)
            ->where('payments.status', 'completed')
            ->exists();

        if($alreadyPurchased){
            return back()->with('error', 'You have already purchased this game!');
        }

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

        $totalItems = $carts->sum('quantity');
        $totalPrice = $carts->sum(function (Cart $cart) {
            return (float) ($cart->game->price ?? 0) * max(1, (int) $cart->quantity);
        });

        return view('cart.index', compact('carts', 'totalItems', 'totalPrice'));
    }

    public function remove($id)
    {
        Cart::where('id', $id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Game removed from cart!');
    }
}
