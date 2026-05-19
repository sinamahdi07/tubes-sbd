@extends('admin.layouts.app')

@section('title', 'Manajemen Review')

@section('content')
    @php
        use Illuminate\Support\Str;
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="steam-card rounded-lg p-6">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Total Review</p>
            <p class="text-3xl font-bold text-white">{{ $stats['total'] }}</p>
        </div>
        <div class="steam-card rounded-lg p-6 border-l-4 border-l-green-500">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Like</p>
            <p class="text-3xl font-bold text-green-300">{{ $stats['likes'] }}</p>
        </div>
        <div class="steam-card rounded-lg p-6 border-l-4 border-l-red-500">
            <p class="text-gray-400 text-sm font-semibold uppercase tracking-wider mb-1">Dislike</p>
            <p class="text-3xl font-bold text-red-300">{{ $stats['dislikes'] }}</p>
        </div>
    </div>

    <div class="steam-card rounded-lg overflow-hidden">
        <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_180px_160px]">
                <input
                    type="text"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari game, user, email, atau isi review..."
                    class="p-2 steam-input rounded"
                >

                <select name="sentiment" class="p-2 steam-input rounded">
                    <option value="">Semua Review</option>
                    <option value="like" @selected($sentiment === 'like')>Like</option>
                    <option value="dislike" @selected($sentiment === 'dislike')>Dislike</option>
                </select>

                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition text-sm">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'sentiment']))
                        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 text-gray-400 hover:text-white text-sm self-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left steam-table">
                <thead>
                    <tr>
                        <th class="p-4">Game</th>
                        <th class="p-4">User</th>
                        <th class="p-4">Review</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td class="p-4">
                                <a href="{{ url('/game/' . $review->game_id) }}" target="_blank" class="flex items-center gap-3 text-[#66c0f4] hover:text-white">
                                    @if($review->game?->thumbnail_url)
                                        <img src="{{ $review->game->thumbnail_url }}" alt="{{ $review->game->title }}" class="h-10 w-16 rounded object-cover">
                                    @else
                                        <span class="h-10 w-16 rounded bg-gray-800"></span>
                                    @endif
                                    <span class="font-semibold">{{ $review->game?->title ?? 'Game dihapus' }}</span>
                                </a>
                            </td>
                            <td class="p-4">
                                <div>
                                    <p class="text-white font-medium">{{ $review->user?->name ?? 'User dihapus' }}</p>
                                    <p class="text-gray-500 text-xs">{{ $review->user?->email ?? '-' }}</p>
                                </div>
                            </td>
                            <td class="p-4 text-gray-300 max-w-xl">
                                {{ Str::limit($review->body, 130) }}
                            </td>
                            <td class="p-4">
                                @if($review->is_recommended)
                                    <span class="inline-flex items-center rounded bg-green-900/60 px-2 py-1 text-xs font-bold text-green-200">Like</span>
                                @else
                                    <span class="inline-flex items-center rounded bg-red-900/60 px-2 py-1 text-xs font-bold text-red-200">Dislike</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-400">
                                {{ $review->created_at?->format('d M Y H:i') ?? '-' }}
                            </td>
                            <td class="p-4 text-center">
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline" onsubmit="return confirm('Hapus review ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-10 text-center text-gray-500">
                                Belum ada review yang ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#2a475e] bg-[#1b2838] flex items-center justify-between">
            <p class="text-sm text-gray-400">Total: {{ $reviews->total() }} review</p>
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
