@extends('admin.layouts.app')

@section('title', 'Detail Game: ' . $game->title)

@section('content')
    {{-- Back + Action Buttons --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.games.index') }}" class="flex items-center gap-2 text-sm text-gray-400 hover:text-white transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke daftar game
        </a>
        <div class="flex gap-3">
            <a href="{{ route('admin.games.edit', $game->game_id) }}"
               class="px-4 py-2 text-sm font-semibold rounded steam-btn-primary">
                ✏️ Edit Game
            </a>
            <form action="{{ route('admin.games.destroy', $game->game_id) }}" method="POST"
                  onsubmit="return confirm('Yakin hapus game {{ addslashes($game->title) }}? Data tidak bisa dikembalikan!');">
                @csrf @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 text-sm font-semibold rounded steam-btn-danger">
                    🗑️ Hapus Game
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===================== KOLOM KIRI: Info Utama ===================== --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Hero Thumbnail + Title --}}
            <div class="steam-card rounded-lg overflow-hidden">
                @if($game->thumbnail_url)
                    <div class="relative">
                        <img src="{{ $game->thumbnail_url }}" alt="{{ $game->title }}"
                             class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-6">
                            <h1 class="text-3xl font-bold text-white drop-shadow">{{ $game->title }}</h1>
                            <p class="text-gray-300 text-sm mt-1">ID: {{ $game->game_id }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-[#1b2838] p-6 border-b border-[#2a475e]">
                        <h1 class="text-3xl font-bold text-white">{{ $game->title }}</h1>
                        <p class="text-gray-400 text-sm mt-1">ID: {{ $game->game_id }}</p>
                    </div>
                @endif

                {{-- Genres --}}
                @if($game->genres->count())
                    <div class="p-4 border-b border-[#2a475e] flex flex-wrap gap-2">
                        @foreach($game->genres as $genre)
                            <span class="px-3 py-1 text-xs bg-[#1b2838] text-[#66c0f4] border border-[#2a475e] rounded-full">
                                {{ $genre->name }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Description --}}
                <div class="p-6">
                    <h3 class="text-sm font-semibold text-[#66c0f4] uppercase tracking-wider mb-3">Deskripsi</h3>
                    @if($game->description)
                        <p class="text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ $game->description }}</p>
                    @else
                        <p class="text-gray-500 italic text-sm">Tidak ada deskripsi.</p>
                    @endif
                </div>
            </div>

            {{-- ===================== SCREENSHOTS GALLERY ===================== --}}
            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex items-center justify-between">
                    <h2 class="font-bold text-[#66c0f4] flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Screenshots
                    </h2>
                    <span class="text-xs bg-gray-700 text-gray-300 px-2 py-1 rounded">
                        {{ $game->screenshots->count() }} gambar
                    </span>
                </div>

                @if($game->screenshots->count())
                    {{-- Main viewer --}}
                    <div class="p-4">
                        <div class="relative rounded overflow-hidden bg-black mb-4" style="aspect-ratio: 16/9;">
                            <img id="main-screenshot"
                                 src="{{ $game->screenshots->first()->url }}"
                                 alt="Screenshot"
                                 class="w-full h-full object-contain transition-all duration-300">
                        </div>

                        {{-- Thumbnail strip --}}
                        <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                            @foreach($game->screenshots as $i => $screenshot)
                                <button onclick="changeScreenshot('{{ $screenshot->url }}', this)"
                                        class="screenshot-thumb rounded overflow-hidden border-2 {{ $i === 0 ? 'border-[#66c0f4]' : 'border-transparent' }} hover:border-[#66c0f4] transition focus:outline-none"
                                        title="Screenshot {{ $i + 1 }}">
                                    <img src="{{ $screenshot->url }}"
                                         alt="Screenshot {{ $i + 1 }}"
                                         class="w-full aspect-video object-cover">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="p-10 text-center text-gray-500">
                        <svg class="w-16 h-16 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-sm">Belum ada screenshot untuk game ini.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- ===================== KOLOM KANAN: Meta Info ===================== --}}
        <div class="space-y-6">

            {{-- Info Box --}}
            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                    <h2 class="font-bold text-[#66c0f4]">Informasi Game</h2>
                </div>
                <div class="p-5 space-y-4 text-sm">

                    {{-- Harga --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Harga</span>
                        <span class="font-bold text-right
                            {{ $game->price == 0 ? 'text-blue-400' : 'text-green-400' }}">
                            {{ $game->price == 0 ? 'Gratis' : 'Rp ' . number_format($game->price, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="border-t border-[#2a475e]"></div>

                    {{-- Developer --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Developer</span>
                        <span class="text-white text-right font-medium">{{ $game->developer->name ?? '-' }}</span>
                    </div>

                    {{-- Publisher --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Publisher</span>
                        <span class="text-white text-right font-medium">{{ $game->publisher->name ?? '-' }}</span>
                    </div>

                    <div class="border-t border-[#2a475e]"></div>

                    {{-- Tanggal Rilis --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Tanggal Rilis</span>
                        <span class="text-white text-right">
                            {{ $game->release_date ? $game->release_date->format('d F Y') : 'Belum diset' }}
                        </span>
                    </div>

                    {{-- Ditambahkan --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Ditambahkan</span>
                        <span class="text-gray-300 text-right">{{ $game->created_at ? $game->created_at->format('d M Y') : '-' }}</span>
                    </div>

                    {{-- Diperbarui --}}
                    <div class="flex items-start justify-between gap-4">
                        <span class="text-gray-400 whitespace-nowrap">Diperbarui</span>
                        <span class="text-gray-300 text-right">{{ $game->updated_at ? $game->updated_at->format('d M Y') : '-' }}</span>
                    </div>

                </div>
            </div>

            {{-- Screenshots Table --}}
            @if($game->screenshots->count())
                <div class="steam-card rounded-lg overflow-hidden">
                    <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                        <h2 class="font-bold text-[#66c0f4] text-sm">Daftar Screenshot ({{ $game->screenshots->count() }})</h2>
                    </div>
                    <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                        @foreach($game->screenshots as $i => $ss)
                            <div class="flex items-center gap-3 p-2 rounded hover:bg-[#1b2838] transition group cursor-pointer"
                                 onclick="changeScreenshot('{{ $ss->url }}', null)">
                                <span class="text-xs text-gray-500 w-5 text-center">#{{ $i + 1 }}</span>
                                <img src="{{ $ss->url }}" alt="#{{ $i+1 }}"
                                     class="w-16 h-9 object-cover rounded border border-gray-700 flex-shrink-0">
                                <span class="text-xs text-gray-400 break-all truncate flex-1 group-hover:text-white">
                                    {{ Str::limit($ss->url, 50) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Genre Box --}}
            <div class="steam-card rounded-lg overflow-hidden">
                <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                    <h2 class="font-bold text-[#66c0f4] text-sm">Genre ({{ $game->genres->count() }})</h2>
                </div>
                <div class="p-4 flex flex-wrap gap-2">
                    @forelse($game->genres as $genre)
                        <span class="px-3 py-1 text-xs bg-[#1b2838] text-[#66c0f4] border border-[#2a475e] rounded-full">
                            {{ $genre->name }}
                        </span>
                    @empty
                        <p class="text-sm text-gray-500 italic">Tidak ada genre.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
<script>
    function changeScreenshot(url, btn) {
        document.getElementById('main-screenshot').src = url;

        // Update border highlight on thumbnails
        if (btn) {
            document.querySelectorAll('.screenshot-thumb').forEach(el => {
                el.classList.remove('border-[#66c0f4]');
                el.classList.add('border-transparent');
            });
            btn.classList.remove('border-transparent');
            btn.classList.add('border-[#66c0f4]');
        }
    }
</script>
@endpush
