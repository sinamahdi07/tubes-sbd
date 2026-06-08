<div class="overflow-hidden rounded-2xl border border-[#2a475e]/90 bg-[#0f1923]/90 shadow-2xl shadow-black/25">
    <div class="border-b border-[#2a475e] bg-[#07111d]/70 p-5">
        <div class="flex items-center justify-between gap-3">
            <h2 class="text-lg font-black text-white">Chat</h2>
            <span class="rounded-full border border-[#2a475e] bg-[#050a12]/70 px-3 py-1 text-sm font-semibold text-gray-300">
                {{ $friends->count() }} teman
            </span>
        </div>
    </div>

    <div class="max-h-[640px] overflow-y-auto p-3">
        @forelse($friends as $listFriend)
            @php
                $latestMessage = $latestMessages->get($listFriend->id);
                $unreadCount = (int) ($unreadCounts->get($listFriend->id) ?? 0);
                $isActiveFriend = isset($friend) && $friend->id === $listFriend->id;
            @endphp

            <a
                href="{{ route('chat.show', $listFriend) }}"
                class="mb-2 flex items-center gap-3 rounded-xl border p-4 transition {{ $isActiveFriend ? 'border-[#66c0f4] bg-[#0b2a44]' : 'border-transparent hover:border-[#2a475e] hover:bg-[#07111d]' }}"
            >
                <span class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] text-lg font-black text-white">
                    {{ strtoupper(substr($listFriend->name, 0, 1)) }}
                </span>

                <span class="min-w-0 flex-1">
                    <span class="flex items-center justify-between gap-3">
                        <span class="truncate font-bold text-white">{{ $listFriend->name }}</span>
                        @if($unreadCount > 0)
                            <span class="flex h-6 min-w-6 items-center justify-center rounded-full bg-[#06bfff] px-2 text-xs font-black text-white">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </span>

                    <span class="mt-1 block truncate text-sm text-gray-400">
                        @if($latestMessage)
                            {{ $latestMessage->sender_id === $user->id ? 'Kamu: ' : '' }}{{ $latestMessage->message }}
                        @else
                            Mulai percakapan
                        @endif
                    </span>
                </span>
            </a>
        @empty
            <div class="rounded-xl border border-dashed border-[#2a475e] bg-[#07111d]/70 p-6 text-sm text-gray-400">
                Kamu belum punya teman untuk diajak chat.
            </div>
        @endforelse
    </div>
</div>
