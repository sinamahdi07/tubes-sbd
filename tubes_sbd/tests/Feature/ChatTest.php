<?php

namespace Tests\Feature;

use App\Models\ChatMessage;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_chat_index_with_accepted_friends(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create(['name' => 'Chat Friend']);
        $this->makeFriends($user, $friend);

        $response = $this->actingAs($user)->get(route('chat.index'));

        $response
            ->assertOk()
            ->assertSee('Chat')
            ->assertSee('Chat Friend');
    }

    public function test_user_can_send_message_to_accepted_friend(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $this->makeFriends($user, $friend);

        $response = $this->actingAs($user)->post(route('chat.store', $friend), [
            'body' => 'Halo, mau main bareng?',
        ]);

        $response->assertRedirect(route('chat.show', $friend));

        $this->assertDatabaseHas('chat_messages', [
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'message' => 'Halo, mau main bareng?',
        ]);
    }

    public function test_user_can_send_message_with_json_response(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $this->makeFriends($user, $friend);

        $response = $this->actingAs($user)->postJson(route('chat.store', $friend), [
            'body' => 'Langsung muncul tanpa reload.',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('message.body', 'Langsung muncul tanpa reload.')
            ->assertJsonPath('message.is_mine', true);
    }

    public function test_user_can_fetch_new_messages_without_refreshing_page(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $friendship = $this->makeFriends($user, $friend);

        $message = ChatMessage::create([
            'sender_id' => $friend->id,
            'receiver_id' => $user->id,
            'friendship_id' => $friendship->id,
            'message' => 'Pesan baru otomatis masuk.',
        ]);

        $response = $this->actingAs($user)->getJson(route('chat.messages', [
            'friend' => $friend,
            'after_id' => 0,
        ]));

        $response
            ->assertOk()
            ->assertJsonPath('messages.0.id', $message->id)
            ->assertJsonPath('messages.0.body', 'Pesan baru otomatis masuk.')
            ->assertJsonPath('messages.0.is_mine', false);

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_user_can_see_total_unread_chat_count(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $friendship = $this->makeFriends($user, $friend);

        ChatMessage::create([
            'sender_id' => $friend->id,
            'receiver_id' => $user->id,
            'friendship_id' => $friendship->id,
            'message' => 'Pesan masuk belum dibaca.',
        ]);

        ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'friendship_id' => $friendship->id,
            'message' => 'Pesan keluar tidak dihitung.',
        ]);

        $this->actingAs($user)
            ->getJson(route('chat.unread-count'))
            ->assertOk()
            ->assertJsonPath('unread_count', 1);
    }

    public function test_user_cannot_chat_with_non_friend(): void
    {
        $user = User::factory()->create();
        $notFriend = User::factory()->create();

        $response = $this->actingAs($user)->post(route('chat.store', $notFriend), [
            'body' => 'Pesan ini tidak boleh terkirim.',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseCount('chat_messages', 0);
    }

    public function test_opening_chat_marks_incoming_messages_as_read(): void
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $friendship = $this->makeFriends($user, $friend);

        $message = ChatMessage::create([
            'sender_id' => $friend->id,
            'receiver_id' => $user->id,
            'friendship_id' => $friendship->id,
            'message' => 'Sudah online?',
        ]);

        $this->actingAs($user)
            ->get(route('chat.show', $friend))
            ->assertOk()
            ->assertSee('Sudah online?');

        $this->assertNotNull($message->fresh()->read_at);
    }

    private function makeFriends(User $user, User $friend): Friendship
    {
        return Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $friend->id,
            'status' => Friendship::STATUS_ACCEPTED,
        ]);
    }
}
