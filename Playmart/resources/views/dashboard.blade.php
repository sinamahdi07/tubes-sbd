<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="section-eyebrow">Community</p>
                <h2 class="mt-1 text-2xl font-black text-white">Dashboard</h2>
            </div>
            <a href="{{ route('friends.index') }}" class="btn-secondary py-2.5 text-sm">Kelola teman</a>
        </div>
    </x-slot>

    <div class="pb-24 pt-8 md:pb-10 sm:pt-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 sm:grid-cols-3 sm:gap-6">
                @foreach([
                    ['label' => 'Total teman', 'value' => $friendCount, 'tone' => 'text-[#66c0f4]'],
                    ['label' => 'Permintaan masuk', 'value' => $incomingFriendRequests->count(), 'tone' => 'text-amber-300'],
                    ['label' => 'Saran teman', 'value' => $suggestedFriends->count(), 'tone' => 'text-emerald-300'],
                ] as $stat)
                    <article class="surface-card p-5 sm:p-6">
                        <p class="text-xs font-black uppercase tracking-widest text-slate-500">{{ $stat['label'] }}</p>
                        <p class="mt-3 text-4xl font-black {{ $stat['tone'] }}">{{ $stat['value'] }}</p>
                    </article>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section class="surface-card p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-black text-white">Permintaan Masuk</h3>
                        <a href="{{ route('friends.index') }}" class="text-sm font-bold text-[#66c0f4] hover:text-white">Lihat semua</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($incomingFriendRequests as $friendship)
                            <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-black/20 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-white">{{ $friendship->requester->name }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $friendship->requester->email }}</p>
                                </div>
                                <form action="{{ route('friends.accept', $friendship) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn-primary w-full py-2.5 text-sm sm:w-auto">Terima</button>
                                </form>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/10 p-6 text-sm text-slate-500">Belum ada permintaan teman baru.</p>
                        @endforelse
                    </div>
                </section>

                <section class="surface-card p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h3 class="text-lg font-black text-white">Saran Teman</h3>
                        <a href="{{ route('friends.index') }}" class="text-sm font-bold text-[#66c0f4] hover:text-white">Cari user</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($suggestedFriends as $suggestedFriend)
                            <div class="flex flex-col gap-4 rounded-2xl border border-white/10 bg-black/20 p-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="truncate font-bold text-white">{{ $suggestedFriend->name }}</p>
                                    <p class="truncate text-sm text-slate-500">{{ $suggestedFriend->email }}</p>
                                </div>
                                <form action="{{ route('friends.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="friend_id" value="{{ $suggestedFriend->id }}">
                                    <button type="submit" class="btn-secondary w-full py-2.5 text-sm sm:w-auto">Tambah</button>
                                </form>
                            </div>
                        @empty
                            <p class="rounded-2xl border border-dashed border-white/10 p-6 text-sm text-slate-500">Belum ada saran teman.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
