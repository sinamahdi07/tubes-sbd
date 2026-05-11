@extends('admin.layouts.app')

@section('title', 'Manajemen Game')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <form method="GET" action="{{ route('admin.games.index') }}" class="flex gap-3 flex-1 mr-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari judul game..."
                   class="flex-1 max-w-sm p-2 steam-input rounded">
            <select name="genre" class="p-2 steam-input rounded">
                <option value="">Semua Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->genre_id }}" {{ request('genre') == $genre->genre_id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition text-sm">Filter</button>
            @if(request()->hasAny(['search', 'genre']))
                <a href="{{ route('admin.games.index') }}" class="px-3 py-2 text-gray-400 hover:text-white text-sm self-center">Reset</a>
            @endif
        </form>
        <a href="{{ route('admin.games.create') }}"
           class="px-4 py-2 steam-btn-primary rounded font-semibold text-sm whitespace-nowrap">
            + Tambah Game
        </a>
    </div>

    <div class="steam-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left steam-table">
                <thead>
                    <tr>
                        <th class="p-4">Game</th>
                        <th class="p-4">Developer</th>
                        <th class="p-4">Publisher</th>
                        <th class="p-4">Genre</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Rilis</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        <tr>
                            <td class="p-4">
                                <div class="flex items-center gap-3">
                                    @if($game->thumbnail_url)
                                        <img src="{{ $game->thumbnail_url }}" alt="{{ $game->title }}"
                                             class="w-16 h-9 object-cover rounded border border-gray-700">
                                    @else
                                        <div class="w-16 h-9 bg-gray-800 rounded border border-gray-700 flex items-center justify-center text-xs text-gray-500">N/A</div>
                                    @endif
                                    <span class="font-semibold text-white text-sm">{{ $game->title }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-gray-300 text-sm">{{ $game->developer->name ?? '-' }}</td>
                            <td class="p-4 text-gray-300 text-sm">{{ $game->publisher->name ?? '-' }}</td>
                            <td class="p-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($game->genres->take(2) as $genre)
                                        <span class="px-2 py-0.5 text-xs bg-[#1b2838] text-[#66c0f4] border border-[#2a475e] rounded">{{ $genre->name }}</span>
                                    @endforeach
                                    @if($game->genres->count() > 2)
                                        <span class="px-2 py-0.5 text-xs bg-gray-800 text-gray-400 rounded">+{{ $game->genres->count() - 2 }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="p-4 text-green-400 font-medium text-sm">
                                @if($game->price == 0)
                                    <span class="text-blue-400">Gratis</span>
                                @else
                                    Rp {{ number_format($game->price, 0, ',', '.') }}
                                @endif
                            </td>
                            <td class="p-4 text-gray-400 text-sm">{{ $game->release_date ? $game->release_date->format('d M Y') : '-' }}</td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.games.edit', $game->game_id) }}"
                                       class="px-3 py-1 text-xs bg-[#1b2838] hover:bg-[#2a475e] text-[#66c0f4] border border-[#2a475e] rounded transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.games.destroy', $game->game_id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus game {{ addslashes($game->title) }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                <p>Tidak ada game yang ditemukan.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#2a475e] bg-[#1b2838] flex items-center justify-between">
            <p class="text-sm text-gray-400">Total: {{ $games->total() }} game</p>
            {{ $games->links() }}
        </div>
    </div>
@endsection
