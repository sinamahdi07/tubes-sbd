<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\Game;
use App\Models\GameReview;
use App\Models\Payment;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_can_view_review_feed(): void
    {
        $user = User::factory()->create(['name' => 'Reviewer One']);
        $game = $this->createGame();

        GameReview::create([
            'game_id' => $game->game_id,
            'user_id' => $user->id,
            'is_recommended' => true,
            'body' => 'Sangat worth it untuk dimainkan.',
        ]);

        $this->getJson(route('games.reviews.index', $game))
            ->assertOk()
            ->assertJsonPath('stats.total', 1)
            ->assertJsonPath('stats.recommended', 1)
            ->assertJsonPath('stats.percentage', 100)
            ->assertJsonPath('reviews.0.user_name', 'Reviewer One');
    }

    public function test_user_must_purchase_before_reviewing(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame();

        $this->actingAs($user)
            ->postJson(route('games.reviews.store', $game), [
                'is_recommended' => true,
                'body' => 'Belum beli tapi coba review.',
            ])
            ->assertForbidden();
    }

    public function test_purchased_user_can_create_and_update_review(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame();
        $this->markPurchased($user, $game);

        $this->actingAs($user)
            ->postJson(route('games.reviews.store', $game), [
                'is_recommended' => true,
                'body' => 'Game ini seru dan stabil.',
            ])
            ->assertCreated()
            ->assertJsonPath('stats.total', 1)
            ->assertJsonPath('stats.recommended', 1);

        $this->actingAs($user)
            ->postJson(route('games.reviews.store', $game), [
                'is_recommended' => false,
                'body' => 'Setelah update terakhir performanya turun.',
            ])
            ->assertCreated()
            ->assertJsonPath('stats.total', 1)
            ->assertJsonPath('stats.recommended', 0);

        $this->assertDatabaseCount('game_reviews', 1);
        $this->assertDatabaseHas('game_reviews', [
            'game_id' => $game->game_id,
            'user_id' => $user->id,
            'is_recommended' => false,
        ]);
    }

    private function createGame(): Game
    {
        $developer = Developer::create(['name' => 'Review Developer']);
        $publisher = Publisher::create(['name' => 'Review Publisher']);

        return Game::create([
            'title' => 'Review Game',
            'description' => 'Game untuk test review.',
            'price' => 50000,
            'release_date' => '2026-05-18',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);
    }

    private function markPurchased(User $user, Game $game): void
    {
        $payment = Payment::create([
            'user_id' => $user->id,
            'payment_code' => 'PAY-REVIEW-' . $user->id,
            'method' => 'qris',
            'status' => Payment::STATUS_PAID,
            'paid_at' => now(),
        ]);

        $payment->items()->create([
            'game_id' => $game->game_id,
            'title' => $game->title,
            'unit_price' => 50000,
            'discount_percent' => 0,
            'quantity' => 1,
        ]);
    }
}
