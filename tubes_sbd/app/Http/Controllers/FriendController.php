<?php

namespace App\Http\Controllers;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FriendController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $search = trim((string) $request->query('search', ''));

        $friendships = Friendship::with(['requester', 'addressee'])
            ->forUser($user->id)
            ->where('status', Friendship::STATUS_ACCEPTED)
            ->latest('updated_at')
            ->get();

        $incomingRequests = Friendship::with('requester')
            ->where('addressee_id', $user->id)
            ->where('status', Friendship::STATUS_PENDING)
            ->latest()
            ->get();

        $outgoingRequests = Friendship::with('addressee')
            ->where('requester_id', $user->id)
            ->where('status', Friendship::STATUS_PENDING)
            ->latest()
            ->get();

        $relatedUserIds = Friendship::forUser($user->id)
            ->get(['requester_id', 'addressee_id'])
            ->flatMap(fn (Friendship $friendship) => [$friendship->requester_id, $friendship->addressee_id])
            ->push($user->id)
            ->unique()
            ->values();

        $users = collect();

        if ($search !== '') {
            $users = User::query()
                ->whereNotIn('id', $relatedUserIds)
                ->where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->orderBy('name')
                ->limit(12)
                ->get();
        }

        return view('friends.index', compact(
            'friendships',
            'incomingRequests',
            'outgoingRequests',
            'users',
            'search',
            'user'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'friend_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $user = $request->user();
        $targetId = (int) $validated['friend_id'];

        if ($targetId === $user->id) {
            return back()->with('error', 'Kamu tidak bisa menambahkan diri sendiri sebagai teman.');
        }

        $existingFriendship = Friendship::between($user->id, $targetId)->first();

        if ($existingFriendship) {
            return back()->with('error', $this->existingFriendshipMessage($existingFriendship, $user->id));
        }

        Friendship::create([
            'requester_id' => $user->id,
            'addressee_id' => $targetId,
            'status' => Friendship::STATUS_PENDING,
        ]);

        return back()->with('success', 'Permintaan teman berhasil dikirim.');
    }

    public function accept(Request $request, Friendship $friendship): RedirectResponse
    {
        $this->authorizeIncomingRequest($request, $friendship);

        if ($friendship->status !== Friendship::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah tidak aktif.');
        }

        $friendship->update(['status' => Friendship::STATUS_ACCEPTED]);

        return back()->with('success', 'Permintaan teman diterima.');
    }

    public function reject(Request $request, Friendship $friendship): RedirectResponse
    {
        $this->authorizeIncomingRequest($request, $friendship);

        if ($friendship->status !== Friendship::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah tidak aktif.');
        }

        $friendship->delete();

        return back()->with('success', 'Permintaan teman ditolak.');
    }

    public function cancel(Request $request, Friendship $friendship): RedirectResponse
    {
        abort_unless($friendship->requester_id === $request->user()->id, 403);

        if ($friendship->status !== Friendship::STATUS_PENDING) {
            return back()->with('error', 'Permintaan ini sudah tidak aktif.');
        }

        $friendship->delete();

        return back()->with('success', 'Permintaan teman dibatalkan.');
    }

    public function destroy(Request $request, Friendship $friendship): RedirectResponse
    {
        $userId = $request->user()->id;

        abort_unless(
            $friendship->status === Friendship::STATUS_ACCEPTED
                && in_array($userId, [$friendship->requester_id, $friendship->addressee_id], true),
            403
        );

        $friendship->delete();

        return back()->with('success', 'Teman berhasil dihapus.');
    }

    private function authorizeIncomingRequest(Request $request, Friendship $friendship): void
    {
        abort_unless($friendship->addressee_id === $request->user()->id, 403);
    }

    private function existingFriendshipMessage(Friendship $friendship, int $userId): string
    {
        if ($friendship->status === Friendship::STATUS_ACCEPTED) {
            return 'Kamu sudah berteman dengan user ini.';
        }

        if ($friendship->requester_id === $userId) {
            return 'Permintaan teman sudah pernah dikirim.';
        }

        return 'User ini sudah mengirim permintaan teman kepadamu. Cek bagian permintaan masuk.';
    }
}
