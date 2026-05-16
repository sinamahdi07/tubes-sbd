<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Developer;
use App\Models\Game;
use App\Models\Payment;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_checkout_cart(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame();

        Cart::create([
            'user_id'  => $user->id,
            'game_id'  => $game->game_id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'method' => 'qris',
        ]);

        $payment = Payment::with('items')->first();

        $response->assertRedirect(route('payments.show', $payment));
        $this->assertSame('paid', $payment->status);
        $this->assertSame('qris', $payment->method);
        $this->assertEquals(100000, (float) $payment->total);
        $this->assertSame(1, $payment->items->count());
        $this->assertEquals($game->game_id, $payment->items->first()->game_id);
        $this->assertEquals(2, $payment->items->first()->quantity);
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_checkout_page_redirects_when_cart_is_empty(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('payments.checkout'));

        $response
            ->assertRedirect(route('cart.index'))
            ->assertSessionHas('error');
    }

    private function createGame(): Game
    {
        $developer = Developer::create([
            'name' => 'Developer Test',
        ]);

        $publisher = Publisher::create([
            'name' => 'Publisher Test',
        ]);

        return Game::create([
            'title'        => 'Game Test',
            'description'  => 'Game untuk test payment.',
            'price'        => 50000,
            'release_date' => '2026-05-16',
            'thumbnail_url'=> 'https://example.com/game.jpg',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);
    }
}
