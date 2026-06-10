<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $friends = $this->friendsFor($user);
        $conversationMeta = $this->conversationMeta($user, $friends);

        return view('chat.index', [
            'user' => $user,
            'friends' => $friends,
            'latestMessages' => $conversationMeta['latestMessages'],
            'unreadCounts' => $conversationMeta['unreadCounts'],
        ]);
    }

    public function show(Request $request, User $friend): View
    {
        $user = $request->user();

        abort_unless($this->areFriends($user->id, $friend->id), 403);

        ChatMessage::query()
            ->where('sender_id', $friend->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $friends = $this->friendsFor($user);
        $conversationMeta = $this->conversationMeta($user, $friends);
        $messages = ChatMessage::with(['sender:id,name', 'receiver:id,name'])
            ->between($user->id, $friend->id)
            ->oldest()
            ->get();

        return view('chat.show', [
            'user' => $user,
            'friend' => $friend,
            'friends' => $friends,
            'messages' => $messages,
            'latestMessages' => $conversationMeta['latestMessages'],
            'unreadCounts' => $conversationMeta['unreadCounts'],
        ]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'unread_count' => ChatMessage::query()
                ->where('receiver_id', $request->user()->id)
                ->whereNull('read_at')
                ->count(),
        ]);
    }

    public function messages(Request $request, User $friend): JsonResponse
    {
        $user = $request->user();

        abort_unless($this->areFriends($user->id, $friend->id), 403);

        $afterId = max(0, (int) $request->query('after_id', 0));
        $messages = ChatMessage::with(['sender:id,name', 'receiver:id,name'])
            ->between($user->id, $friend->id)
            ->when($afterId > 0, fn ($query) => $query->where('id', '>', $afterId))
            ->oldest()
            ->limit(80)
            ->get();

        $incomingIds = $messages
            ->where('sender_id', $friend->id)
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->pluck('id');

        if ($incomingIds->isNotEmpty()) {
            ChatMessage::query()
                ->whereIn('id', $incomingIds)
                ->update(['read_at' => now()]);
        }

        return response()->json([
            'messages' => $messages
                ->map(fn (ChatMessage $message) => $this->messagePayload($message, $user))
                ->values(),
            'last_id' => $messages->last()?->id ?? $afterId,
        ]);
    }

    public function store(Request $request, User $friend): RedirectResponse|JsonResponse
    {
        $user = $request->user();
        $friendship = $this->acceptedFriendship($user->id, $friend->id);

        abort_unless($friendship, 403);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'receiver_id' => $friend->id,
            'friendship_id' => $friendship->id,
            'message' => $validated['body'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $this->messagePayload($message, $user),
            ], 201);
        }

        return redirect()
            ->route('chat.show', $friend)
            ->with('success', 'Pesan terkirim.');
    }

    private function friendsFor(User $user): Collection
    {
        return Friendship::with(['requester:id,name,email', 'addressee:id,name,email'])
            ->forUser($user->id)
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->latest('updated_at')
            ->get()
            ->map(fn (Friendship $friendship) => $friendship->otherUser($user))
            ->filter()
            ->values();
    }

    private function areFriends(int $userId, int $friendId): bool
    {
        return (bool) $this->acceptedFriendship($userId, $friendId);
    }

    private function acceptedFriendship(int $userId, int $friendId): ?Friendship
    {
        if ($userId === $friendId) {
            return null;
        }

        return Friendship::between($userId, $friendId)
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->first();
    }

    private function conversationMeta(User $user, Collection $friends): array
    {
        $friendIds = $friends->pluck('id');

        if ($friendIds->isEmpty()) {
            return [
                'latestMessages' => collect(),
                'unreadCounts' => collect(),
            ];
        }

        $latestMessages = ChatMessage::query()
            ->forUser($user->id)
            ->where(function ($query) use ($friendIds) {
                $query->whereIn('sender_id', $friendIds)
                    ->orWhereIn('receiver_id', $friendIds);
            })
            ->latest()
            ->get()
            ->unique(fn (ChatMessage $message) => $message->otherUserId($user->id))
            ->keyBy(fn (ChatMessage $message) => $message->otherUserId($user->id));

        $unreadCounts = ChatMessage::query()
            ->where('receiver_id', $user->id)
            ->whereNull('read_at')
            ->whereIn('sender_id', $friendIds)
            ->selectRaw('sender_id, count(*) as total')
            ->groupBy('sender_id')
            ->pluck('total', 'sender_id');

        return [
            'latestMessages' => $latestMessages,
            'unreadCounts' => $unreadCounts,
        ];
    }

    private function messagePayload(ChatMessage $message, User $user): array
    {
        return [
            'id' => $message->id,
            'body' => $message->message,
            'is_mine' => $message->sender_id === $user->id,
            'date_label' => $message->sentDateLabel(),
            'time_label' => $message->sentTimeLabel(),
            'sent_at_label' => $message->sentAtLabel(),
            'created_at' => $message->created_at?->toIso8601String(),
        ];
    }
}
