@extends('admin.layouts.app')

@section('title', 'Manajemen Game')

@section('content')
    @php
        $isTrash = request()->boolean('trash');
    @endphp

    {{-- ===== FILTER BAR ===== --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap lg:flex-nowrap">

        {{-- Tab: Aktif / Terhapus --}}
        <div class="flex items-center gap-1 p-1 rounded-xl bg-white/5 border border-white/8 flex-shrink-0">
            <a href="{{ route('admin.games.index', request()->except(['trash', 'page'])) }}"
               class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                      {{ !$isTrash ? 'bg-[#118dff] text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Aktif
            </a>
            <a href="{{ route('admin.games.index', array_merge(request()->except('page'), ['trash' => 1])) }}"
               class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                      {{ $isTrash ? 'bg-red-500/80 text-white shadow-lg shadow-red-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Terhapus
            </a>
        </div>

        {{-- Search + Genre + Buttons --}}
        <form method="GET" action="{{ route('admin.games.index') }}"
              class="flex items-center gap-2 flex-1 min-w-0">
            @if($isTrash)
                <input type="hidden" name="trash" value="1">
            @endif

            {{-- Search Input --}}
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul game..."
                       class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm font-medium text-white placeholder-gray-500
                              bg-white/5 border border-white/10 focus:border-[#118dff]/60 focus:bg-white/8
                              focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all">
            </div>

            {{-- Genre Select --}}
            <select name="genre"
                    class="py-2.5 px-3 rounded-xl text-sm font-medium text-white
                           bg-white/5 border border-white/10 focus:border-[#118dff]/60
                           focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all
                           flex-shrink-0 w-40 lg:w-48 appearance-none cursor-pointer"
                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; padding-right: 32px;">
                <option value="">Semua Genre</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre->genre_id }}" {{ request('genre') == $genre->genre_id ? 'selected' : '' }}>
                        {{ $genre->name }}
                    </option>
                @endforeach
            </select>

            {{-- Filter Button --}}
            <button type="submit"
                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                           bg-white/8 border border-white/10 text-gray-300 hover:bg-[#118dff]/15 hover:border-[#118dff]/40
                           hover:text-white transition-all whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/>
                </svg>
                Filter
            </button>

            {{-- Reset (if active) --}}
            @if(request()->hasAny(['search', 'genre']))
                <a href="{{ route('admin.games.index', $isTrash ? ['trash' => 1] : []) }}"
                   class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                          text-gray-500 hover:text-red-400 border border-transparent hover:border-red-500/30 transition-all whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </form>

        {{-- Add Game Button --}}
        @unless($isTrash)
            <a href="{{ route('admin.games.create') }}"
               class="flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                      bg-[#118dff] text-white shadow-lg shadow-blue-500/20
                      hover:bg-blue-500 hover:scale-105 active:scale-95 transition-all whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Game
            </a>
        @endunless
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
                                    <div>
                                        <span class="font-semibold text-white text-sm">{{ $game->title }}</span>
                                        @if($game->detail && $game->detail->discount > 0)
                                            <span class="ml-1 bg-[#4c6b22] text-[#beee11] text-xs font-black px-1.5 py-0.5 rounded">
                                                -{{ $game->detail->discount }}%
                                            </span>
                                        @endif
                                    </div>
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
                            <td class="p-4 text-sm">
                                @php
                                    $disc = $game->detail->discount ?? 0;
                                    $orig = $game->price;
                                    $final = $disc > 0 ? $orig * (1 - $disc / 100) : $orig;
                                @endphp
                                @if($orig == 0)
                                    <span class="text-blue-400 font-medium">Gratis</span>
                                @elseif($disc > 0)
                                    <div>
                                        <div class="text-gray-500 line-through text-xs">Rp {{ number_format($orig, 0, ',', '.') }}</div>
                                        <div class="text-green-400 font-medium">Rp {{ number_format($final, 0, ',', '.') }}</div>
                                    </div>
                                @else
                                    <span class="text-green-400 font-medium">Rp {{ number_format($orig, 0, ',', '.') }}</span>
                                @endif
                            </td>
                            <td class="p-4 text-gray-400 text-sm">{{ $game->release_date ? $game->release_date->format('d M Y') : '-' }}</td>
                            <td class="p-4 text-center">
                                <div class="flex flex-wrap justify-center gap-2">
                                     @if($isTrash)
                                         <form action="{{ route('admin.games.restore', $game->game_id) }}" method="POST" class="inline"
                                               onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin mengembalikan game {{ addslashes($game->title) }}?', 'info', 'Restore Game');">
                                             @csrf
                                             <button type="submit"
                                                     class="px-3 py-1 text-xs bg-green-900/50 hover:bg-green-800 text-green-300 border border-green-800 rounded transition">
                                                 Restore
                                             </button>
                                         </form>
                                         <form action="{{ route('admin.games.force-destroy', $game->game_id) }}" method="POST" class="inline"
                                               onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin menghapus permanen game {{ addslashes($game->title) }}? Data tidak dapat dikembalikan.', 'danger', 'Hapus Permanen');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit"
                                                     class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">
                                                 Hapus Permanen
                                             </button>
                                         </form>
                                     @else
                                         <a href="{{ route('admin.games.show', $game->game_id) }}"
                                            class="px-3 py-1 text-xs bg-purple-900/50 hover:bg-purple-800 text-purple-300 border border-purple-800 rounded transition">
                                             Detail
                                         </a>
                                         <a href="{{ route('admin.games.edit', $game->game_id) }}"
                                            class="px-3 py-1 text-xs bg-[#1b2838] hover:bg-[#2a475e] text-[#66c0f4] border border-[#2a475e] rounded transition">
                                             Edit
                                         </a>
                                         <form action="{{ route('admin.games.destroy', $game->game_id) }}" method="POST" class="inline"
                                               onsubmit="return adminConfirmSubmit(event, 'Yakin ingin menghapus game {{ addslashes($game->title) }} ke tempat sampah?', 'danger', 'Hapus Game');">
                                             @csrf
                                             @method('DELETE')
                                             <button type="submit"
                                                     class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">
                                                 Hapus
                                             </button>
                                         </form>
                                     @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-10 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                                </svg>
                                <p>{{ $isTrash ? 'Tidak ada game terhapus.' : 'Tidak ada game yang ditemukan.' }}</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-[#2a475e] bg-[#1b2838] flex items-center justify-between">
            <p class="text-sm text-gray-400">Total: {{ $games->total() }} {{ $isTrash ? 'game terhapus' : 'game' }}</p>
            {{ $games->links() }}
        </div>
    </div>
@endsection
