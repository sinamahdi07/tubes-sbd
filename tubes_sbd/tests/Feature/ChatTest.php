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
