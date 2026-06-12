@extends('layouts.store')

@section('title', 'Social Hub - PlayMart')

@push('styles')
    <style>
        .social-container-main {
            background: radial-gradient(circle at top right, rgba(17, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.05), transparent 40%);
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
        }
        .social-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }
        .social-card:hover {
            border-color: rgba(102, 192, 244, 0.2);
            background: rgba(255, 255, 255, 0.04);
        }
        .search-input-premium {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .search-input-premium:focus {
            border-color: #118dff;
            background: rgba(0, 0, 0, 0.5);
            box-shadow: 0 0 20px rgba(17, 141, 255, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="social-container-main min-h-screen py-10 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black tracking-tighter text-white">Social <span class="text-[#66c0f4]">Hub</span></h1>
                    <p class="text-gray-400 mt-2 font-medium">Temukan teman mabar dan bangun komunitas gaming-mu.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-2xl bg-white/5 flex items-center justify-center text-[#66c0f4]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-black text-white leading-none">{{ $friendships->count() }}</p>
                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mt-1">Friends Connected</p>
                    </div>
                </div>
            </div>

            <!-- Search Section -->
            <div class="glass-panel p-6 lg:p-10 mb-10">
                <form method="GET" action="{{ route('friends.index') }}">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="relative flex-1 group">
                            <input
                                id="search"
                                type="search"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari teman lewat nama atau email..."
                                class="search-input-premium w-full rounded-2xl py-4 pl-14 pr-6 text-white placeholder-white/20 outline-none"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-5 top-4 h-6 w-6 text-white/20 group-focus-within:text-[#66c0f4] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <button type="submit" class="rounded-2xl bg-gradient-to-tr from-[#118dff] to-[#66c0f4] px-10 py-4 font-black text-white shadow-xl shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all">
                            Cari User
                        </button>
                    </div>
                </form>

                @if($search !== '')
                    <div class="mt-10 animate-in fade-in slide-in-from-top-4 duration-500">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-1 w-8 rounded-full bg-[#66c0f4]"></div>
                            <h3 class="text-xs font-black uppercase tracking-widest text-[#66c0f4]">Search Results</h3>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @forelse($users as $foundUser)
                                <div class="social-card p-5 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4 min-w-0">
                                        <div class="h-12 w-12 rounded-xl bg-white/5 flex items-center justify-center text-lg font-black text-white shrink-0">
                                            {{ strtoupper(substr($foundUser->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-black text-white leading-tight">{{ $foundUser->name }}</p>
                                            <p class="truncate text-[11px] font-medium text-gray-500">{{ $foundUser->email }}</p>
                                        </div>
                                    </div>

                                    <form action="{{ route('friends.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{ $foundUser->id }}">
                                        <button type="submit" class="h-10 w-10 rounded-xl bg-[#118dff]/10 text-[#118dff] flex items-center justify-center hover:bg-[#118dff] hover:text-white transition-all active:scale-90">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="col-span-full py-12 text-center bg-black/20 rounded-[2rem] border border-dashed border-white/10">
                                    <p class="text-gray-500 font-medium italic">Tidak ada user baru yang ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endif
            </div>

            <!-- Requests Grid -->
            <div class="grid gap-8 lg:grid-cols-2 mb-10">
                <!-- Incoming Requests -->
                <div class="glass-panel p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black tracking-tighter text-white">Permintaan Masuk</h3>
                        </div>
                        @if($incomingRequests->count() > 0)
                            <span class="px-3 py-1 rounded-lg bg-orange-500 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-orange-500/20">
                                {{ $incomingRequests->count() }} New
                            </span>
                        @endif
                    </div>

                    <div class="space-y-4">
                        @forelse($incomingRequests as $requestFriendship)
                            <div class="social-card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="h-12 w-12 rounded-xl bg-gradient-to-tr from-orange-500 to-amber-500 flex items-center justify-center text-white text-lg font-black shrink-0">
                                        {{ strtoupper(substr($requestFriendship->requester->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-white leading-tight truncate">{{ $requestFriendship->requester->name }}</p>
                                        <p class="text-[11px] font-medium text-gray-500 truncate">{{ $requestFriendship->requester->email }}</p>
                                    </div>
                                </div>

                                <div class="flex gap-2 shrink-0">
                                    <form action="{{ route('friends.accept', $requestFriendship) }}" method="POST" class="flex-1 sm:flex-none">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="w-full rounded-xl bg-emerald-400 px-5 py-2.5 text-xs font-black uppercase tracking-widest text-[#07111d] transition-all hover:scale-105">
                                            Accept
                                        </button>
                                    </form>
                                    <form action="{{ route('friends.reject', $requestFriendship) }}" method="POST" class="flex-1 sm:flex-none">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full px-5 py-2.5 rounded-xl bg-white/5 text-white/40 font-black text-xs uppercase tracking-widest border border-white/5 hover:bg-red-500 hover:text-white transition-all">
                                            Reject
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="py-10 text-center opacity-30 italic font-medium">No pending requests</div>
                        @endforelse
                    </div>
                </div>

                <!-- Outgoing Requests -->
                <div class="glass-panel p-8">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-[#118dff]/10 flex items-center justify-center text-[#118dff]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black tracking-tighter text-white">Sent</h3>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @forelse($outgoingRequests as $requestFriendship)
                            <div class="social-card p-5 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="h-12 w-12 rounded-xl bg-white/5 flex items-center justify-center text-white/40 text-lg font-black shrink-0">
                                        {{ strtoupper(substr($requestFriendship->addressee->name, 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-black text-white leading-tight truncate">{{ $requestFriendship->addressee->name }}</p>
                                        <span class="inline-flex items-center gap-1 text-[9px] font-black text-[#66c0f4] uppercase tracking-[0.15em] bg-[#66c0f4]/10 px-1.5 py-0.5 rounded mt-1">Pending</span>
                                    </div>
                                </div>

                                <form action="{{ route('friends.cancel', $requestFriendship) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="h-10 w-10 rounded-xl bg-white/5 text-white/20 flex items-center justify-center hover:bg-red-500/20 hover:text-red-500 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @empty
                            <div class="py-10 text-center opacity-30 italic font-medium">No outgoing requests</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Friend List -->
            <div class="glass-panel p-8 lg:p-12">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 mb-10">
                    <div>
                        <h2 class="text-3xl font-black tracking-tighter text-white">Daftar Teman</h2>
                        <p class="text-gray-400 mt-1 font-medium">Gamer yang terhubung dengan Anda di PlayMart.</p>
                    </div>
                    <div class="px-4 py-2 rounded-xl bg-white/5 border border-white/5 text-xs font-black text-white/60 uppercase tracking-widest">
                        Total {{ $friendships->count() }} contacts
                    </div>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    @forelse($friendships as $friendship)
                        @php $friend = $friendship->otherUser($user); @endphp
                        @if($friend)
                            <div class="social-card p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-5">
                                    <div class="relative">
                                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-[#118dff] to-[#66c0f4] flex items-center justify-center text-2xl font-black text-white shadow-lg">
                                            {{ strtoupper(substr($friend->name, 0, 1)) }}
                                        </div>
                                        <div class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-4 border-[#07111d] bg-emerald-400"></div>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-xl font-black text-white tracking-tight leading-tight mb-1 truncate">{{ $friend->name }}</h3>
                                        <p class="text-xs font-bold text-gray-500 uppercase tracking-widest truncate">{{ $friend->email }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3 shrink-0">
                                    <a href="{{ route('chat.show', $friend) }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-[#118dff] text-white font-black text-xs uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:scale-105 active:scale-95 transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
                                        </svg>
                                        Chat
                                    </a>
                                    
                                    <form action="{{ route('friends.destroy', $friendship) }}" method="POST" onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin menghapus teman ini?', 'danger', 'Hapus Teman');" class="flex-1 sm:flex-none">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-full h-11 w-11 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center border border-red-500/20 hover:bg-red-500 hover:text-white transition-all active:scale-95">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="col-span-full bg-black/20 border border-dashed border-white/10 rounded-[2rem] p-16 text-center">
                            <div class="h-20 w-20 bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6 text-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-black text-white tracking-tighter">Your friend list is empty</h3>
                            <p class="text-gray-400 mt-2 mb-8 max-w-xs mx-auto">Gunakan fitur pencarian di atas untuk menemukan dan menambahkan teman baru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
