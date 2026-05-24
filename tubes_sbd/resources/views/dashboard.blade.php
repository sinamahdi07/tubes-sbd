<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
            <a href="{{ route('friends.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                Kelola teman
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Total teman</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">{{ $friendCount }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Permintaan masuk</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">{{ $incomingFriendRequests->count() }}</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <p class="text-sm font-medium text-gray-500">Saran teman</p>
                        <p class="mt-3 text-4xl font-bold text-gray-900">{{ $suggestedFriends->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Permintaan Masuk</h3>
                            <a href="{{ route('friends.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Lihat semua</a>
                        </div>

                        <div class="mt-5 space-y-3">
                            @forelse($incomingFriendRequests as $friendship)
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $friendship->requester->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $friendship->requester->email }}</p>
                                    </div>

                                    <form action="{{ route('friends.accept', $friendship) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white hover:bg-indigo-700">
                                            Terima
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                                    Belum ada permintaan teman baru.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900">Saran Teman</h3>
                            <a href="{{ route('friends.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">Cari user</a>
                        </div>

                        <div class="mt-5 space-y-3">
                            @forelse($suggestedFriends as $suggestedFriend)
                                <div class="flex items-center justify-between gap-4 rounded-lg border border-gray-200 p-4">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $suggestedFriend->name }}</p>
                                        <p class="text-sm text-gray-500">{{ $suggestedFriend->email }}</p>
                                    </div>

                                    <form action="{{ route('friends.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="friend_id" value="{{ $suggestedFriend->id }}">
                                        <button type="submit" class="rounded-md border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                                            Tambah
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="rounded-lg border border-dashed border-gray-300 p-6 text-sm text-gray-500">
                                    Belum ada saran teman.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
