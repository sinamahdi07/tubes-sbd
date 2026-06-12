<div class="chat-sidebar flex flex-col overflow-hidden rounded-[2rem] border border-white/5 bg-[#0f1923]/60 backdrop-blur-xl shadow-2xl">
    <div class="border-b border-white/5 bg-white/5 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-black tracking-tighter text-white">Messages</h2>
                <p class="text-xs font-bold uppercase tracking-widest text-[#66c0f4] mt-1">{{ $friends->count() }} active contacts</p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                </svg>
            </div>
        </div>
        
        <div class="mt-6 relative">
            <input type="text" placeholder="Search friends..." class="w-full rounded-xl bg-black/20 border-none py-3 pl-10 pr-4 text-sm text-white placeholder-white/20 focus:ring-1 focus:ring-[#66c0f4]/50 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-3 h-4 w-4 text-white/20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
    </div>

    <div class="chat-sidebar-list flex-1 overflow-y-auto p-4 store-scrollbar">
        @forelse($friends as $listFriend)
            @php
                $latestMessage = $latestMessages->get($listFriend->id);
                $unreadCount = (int) ($unreadCounts->get($listFriend->id) ?? 0);
                $isActiveFriend = isset($friend) && $friend->id === $listFriend->id;
            @endphp

            <a
                href="{{ route('chat.show', $listFriend) }}"
                class="group relative mb-2 flex items-center gap-4 rounded-2xl p-4 transition-all duration-300 {{ $isActiveFriend ? 'bg-gradient-to-r from-[#118dff]/20 to-transparent border-l-4 border-[#118dff]' : 'hover:bg-white/5 border-l-4 border-transparent' }}"
            >
                <div class="relative shrink-0">
                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-tr from-[#07111d] to-[#16202d] text-xl font-black text-white shadow-lg group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr($listFriend->name, 0, 1)) }}
                    </span>
                    <div class="absolute -bottom-1 -right-1 h-4 w-4 rounded-full border-2 border-[#0f1923] bg-emerald-400"></div>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-2">
                        <span class="truncate font-black text-white group-hover:text-[#66c0f4] transition-colors">{{ $listFriend->name }}</span>
                        @if($latestMessage)
                            <span class="shrink-0 text-[10px] font-bold text-white/20 uppercase tracking-tighter">{{ $latestMessage->created_at->diffForHumans(null, true) }}</span>
                        @endif
                    </div>

                    <div class="mt-1 flex items-center justify-between gap-3">
                        <span class="truncate text-sm font-medium {{ $unreadCount > 0 ? 'text-white' : 'text-white/40' }}">
                            @if($latestMessage)
                                {{ $latestMessage->sender_id === $user->id ? 'You: ' : '' }}{{ $latestMessage->message }}
                            @else
                                <span class="italic text-white/20">Say hi! 👋</span>
                            @endif
                        </span>
                        
                        @if($unreadCount > 0)
                            <span class="flex h-5 min-w-[20px] items-center justify-center rounded-lg bg-[#118dff] px-1.5 text-[10px] font-black text-white shadow-lg shadow-blue-500/40">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="flex flex-col items-center justify-center rounded-2xl border border-dashed border-white/10 p-10 text-center">
                <div class="mb-4 text-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <p class="text-xs font-bold uppercase tracking-widest text-white/30">No active friends</p>
            </div>
        @endforelse
    </div>
</div>
