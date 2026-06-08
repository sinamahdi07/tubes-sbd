@extends('layouts.store')

@section('content')

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-[#16202d] border border-[#2a475e] rounded-2xl">
                <div class="p-6">
                    <form method="GET" action="{{ route('friends.index') }}" class="flex flex-col gap-3 sm:flex-row">
                        <label class="sr-only" for="search">Cari user</label>
                        <input
                            id="search"
                            type="search"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari nama atau email user..."
                            class="w-full
                           bg-[#0f1923]
                           border border-[#316282]
                           focus:border-[#66c0f4]
                           outline-none
                           px-5 py-4
                           rounded-xl
                           text-white"
                        >
                        <button type="submit" class="steam-blue px-8 rounded-xl font-semibold">
                            Cari
                        </button>
                    </form>

                    @if($search !== '')
                        <div class="mt-6">
                            <h3 class="text-sm font-semibold uppercase tracking-wide text-gray-400">Hasil pencarian</h3>

                            <div class="mt-3 grid gap-3 md:grid-cols-2">
                                @forelse($users as $foundUser)
                                    <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-white">{{ $foundUser->name }}</p>
                                            <p class="truncate text-sm text-gray-400">{{ $foundUser->email }}</p>
                                        </div>

                                        <form action="{{ route('friends.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="friend_id" value="{{ $foundUser->id }}">
                                            <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                                Tambah
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-400 md:col-span-2">
                                        Tidak ada user baru yang cocok dengan pencarian ini.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-[#16202d] border border-[#2a475e] rounded-2xl overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white">Permintaan Masuk</h3>

                        <div class="mt-5 space-y-3">
                            @forelse($incomingRequests as $requestFriendship)
                                <div class="flex flex-col gap-4 rounded-lg border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-white">{{ $requestFriendship->requester->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $requestFriendship->requester->email }}</p>
                                    </div>

                                    <div class="flex gap-2">
                                        <form action="{{ route('friends.accept', $requestFriendship) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                                Terima
                                            </button>
                                        </form>

                                        <form action="{{ route('friends.reject', $requestFriendship) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                                Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-400">
                                    Tidak ada permintaan masuk.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-[#16202d] border border-[#2a475e] rounded-2xl overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white">Permintaan Terkirim</h3>

                        <div class="mt-5 space-y-3">
                            @forelse($outgoingRequests as $requestFriendship)
                                <div class="flex flex-col gap-4 rounded-lg border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-white">{{ $requestFriendship->addressee->name }}</p>
                                        <p class="text-sm text-gray-400">{{ $requestFriendship->addressee->email }}</p>
                                    </div>

                                    <form action="{{ route('friends.cancel', $requestFriendship) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            Batalkan
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-400">
                                    Tidak ada permintaan terkirim.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-[#16202d] border border-[#2a475e] rounded-2xl overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white">Daftar Teman</h3>
                        <span class="rounded-full bg-gray-100 px-3 py-1 text-sm font-semibold text-gray-700">
                            {{ $friendships->count() }} teman
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        @forelse($friendships as $friendship)
                            @php
                                $friend = $friendship->otherUser($user);
                            @endphp

                            @if($friend)
                                <div class="flex flex-col gap-4 rounded-lg border border-gray-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate font-semibold text-white">{{ $friend->name }}</p>
                                        <p class="truncate text-sm text-gray-400">{{ $friend->email }}</p>
                                    </div>

                                    <div class="flex shrink-0 gap-2">
                                        <a href="{{ route('chat.show', $friend) }}" class="rounded-md border border-[#66c0f4] px-3 py-2 text-sm font-semibold text-[#66c0f4] hover:bg-[#0f2638]">
                                            Chat
                                        </a>

                                        <form action="{{ route('friends.destroy', $friendship) }}" method="POST" onsubmit="return confirm('Hapus teman ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-md border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-400 md:col-span-2">
                                Kamu belum punya teman. Cari user di atas untuk mulai menambahkan.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
