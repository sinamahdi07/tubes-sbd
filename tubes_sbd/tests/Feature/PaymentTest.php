<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Developer;
use App\Models\Game;
use App\Models\GameDetail;
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
            'user_id' => $user->id,
            'game_id' => $game->game_id,
            'quantity' => 2,
        ]);

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'method' => 'qris',
        ]);

        $payment = Payment::with('items')->first();

        $response->assertRedirect(route('payments.show', $payment));
        $this->assertSame('paid', $payment->status);
        $this->assertSame('qris', $payment->method);
        $this->assertEquals(50000, (float) $payment->total);
        $this->assertSame(1, $payment->items->count());
        $this->assertEquals($game->game_id, $payment->items->first()->game_id);
        $this->assertEquals(1, $payment->items->first()->quantity);
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

    public function test_user_can_checkout_selected_cart_items_only(): void
    {
        $user = User::factory()->create();
        $firstGame = $this->createGame('Selected Game', 50000);
        $secondGame = $this->createGame('Skipped Game', 80000);

        $selectedCart = Cart::create([
            'user_id' => $user->id,
            'game_id' => $firstGame->game_id,
            'quantity' => 1,
        ]);

        $skippedCart = Cart::create([
            'user_id' => $user->id,
            'game_id' => $secondGame->game_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'method' => 'qris',
            'cart_ids' => [$selectedCart->id],
        ]);

        $payment = Payment::with('items')->first();

        $response->assertRedirect(route('payments.show', $payment));
        $this->assertEquals(50000, (float) $payment->total);
        $this->assertSame(1, $payment->items->count());
        $this->assertEquals($firstGame->game_id, $payment->items->first()->game_id);
        $this->assertDatabaseMissing('carts', ['id' => $selectedCart->id]);
        $this->assertDatabaseHas('carts', ['id' => $skippedCart->id]);
    }

    public function test_checkout_applies_game_detail_discount(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame('Discounted Game', 100000, 25);

        Cart::create([
            'user_id' => $user->id,
            'game_id' => $game->game_id,
            'quantity' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('payments.store'), [
            'method' => 'qris',
        ]);

        $payment = Payment::with('items')->first();

        $response->assertRedirect(route('payments.show', $payment));
        $this->assertEquals(100000, (float) $payment->subtotal);
        $this->assertEquals(25000, (float) $payment->discount_total);
        $this->assertEquals(75000, (float) $payment->total);
        $this->assertSame(25, $payment->items->first()->discount_percent);
        $this->assertEquals(75000, (float) $payment->items->first()->line_total);
    }

    private function createGame(string $title = 'Game Test', int $price = 50000, int $discount = 0): Game
    {
        $developer = Developer::create([
            'name' => 'Developer '.$title,
        ]);

        $publisher = Publisher::create([
            'name' => 'Publisher '.$title,
        ]);

        $game = Game::create([
            'title' => $title,
            'description' => 'Game untuk test payment.',
            'price' => $price,
            'release_date' => '2026-05-16',
            'thumbnail_url' => 'https://example.com/game.jpg',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);

        GameDetail::create([
            'game_id' => $game->game_id,
            'discount' => $discount,
        ]);

        return $game;
    }
}
