<?php

namespace Tests\Feature;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FriendshipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_send_friend_request(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $response = $this->actingAs($user)->post(route('friends.store'), [
            'friend_id' => $friend->id,
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('friendships', [
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_PENDING,
        ]);
    }

    public function test_friend_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('friends.index'));

        $response
            ->assertOk()
            ->assertSee('Teman')
            ->assertSee('Permintaan Masuk')
            ->assertSee('Daftar Teman');
    }

    public function test_addressee_can_accept_friend_request(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $friendship = Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_PENDING,
        ]);

        $response = $this->actingAs($friend)->patch(route('friends.accept', $friendship));

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('friendships', [
            'id' => $friendship->id,
            'status' => Friendship::STATUS_ACCEPTED,
        ]);
    }

    public function test_requester_cannot_accept_their_own_friend_request(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $friendship = Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->patch(route('friends.accept', $friendship));

        $response->assertForbidden();
    }

    public function test_reverse_pending_request_is_not_duplicated(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        Friendship::create([
            'requester_id' => $friend->id,
            'addressee_id' => $user->id,
            'status' => Friendship::STATUS_PENDING,
        ]);

        $response = $this->actingAs($user)->post(route('friends.store'), [
            'friend_id' => $friend->id,
        ]);

        $response->assertSessionHas('error');
        $this->assertSame(1, Friendship::count());
    }

    public function test_friend_can_be_removed(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $friendship = Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_ACCEPTED,
        ]);

        $response = $this->actingAs($friend)->delete(route('friends.destroy', $friendship));

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('friendships', [
            'id' => $friendship->id,
        ]);
    }
}
