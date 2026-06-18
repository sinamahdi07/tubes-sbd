<?php

namespace Tests\Feature;

use App\Models\Developer;
use App\Models\Game;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_before_toggling_wishlist(): void
    {
        $game = $this->createGame();

        $this->post(route('wishlist.toggle', $game))
            ->assertRedirect(route('login'));
    }

    public function test_user_can_view_wishlist_page(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame();

        Wishlist::create([
            'user_id' => $user->id,
            'game_id' => $game->game_id,
        ]);

        $this->actingAs($user)
            ->get(route('wishlist.index'))
            ->assertOk()
            ->assertSee('Wishlist')
            ->assertSee($game->title);
    }

    public function test_user_can_add_and_remove_game_from_wishlist(): void
    {
        $user = User::factory()->create();
        $game = $this->createGame();

        $this->actingAs($user)
            ->post(route('wishlist.toggle', $game))
            ->assertRedirect();

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $user->id,
            'game_id' => $game->game_id,
        ]);

        $this->actingAs($user)
            ->post(route('wishlist.toggle', $game))
            ->assertRedirect();

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $user->id,
            'game_id' => $game->game_id,
        ]);
    }

    private function createGame(): Game
    {
        $developer = Developer::create(['name' => 'Wishlist Developer']);
        $publisher = Publisher::create(['name' => 'Wishlist Publisher']);

        return Game::create([
            'title' => 'Wishlist Game',
            'description' => 'Game untuk test wishlist.',
            'price' => 75000,
            'release_date' => '2026-06-07',
            'thumbnail_url' => 'https://example.com/wishlist-game.jpg',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);
    }
}
