@php
use Illuminate\Support\Str;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameStore — Toko Game Terlengkap</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #1b2838;
            overflow-x: hidden;
            font-family: Arial, Helvetica, sans-serif;
        }

        .hero-bg {
            background:
                linear-gradient(to bottom, rgba(15, 32, 39, 0.5), rgba(27,40,56,1)),
                url('https://images.unsplash.com/photo-1542751371-adc38448a05e?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
        }

        .game-card:hover {
            transform: scale(1.03);
            transition: .25s ease;
            box-shadow: 0 0 20px rgba(102,192,244,.4);
        }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
        }

        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }

        .sidebar-item:hover {
            background: rgba(255,255,255,0.08);
        }
    </style>
</head>
<body class="text-white min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-[#171a21] border-b border-[#2a475e] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <div class="flex items-center gap-10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full steam-blue flex items-center justify-center font-bold text-xl">
                        G
                    </div>
                    <h1 class="text-2xl font-bold tracking-wide text-[#66c0f4]">
                        PlayMart
                    </h1>
                </div>

                <div class="hidden md:flex gap-8 text-sm uppercase tracking-wider font-semibold text-gray-300">
                    <a href="{{ route('home') }}" class="hover:text-white">Store</a>
                    <a href="#" class="hover:text-white">About</a>
                    <a href="#" class="hover:text-white">Support</a>
                    <a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-store-user-menu />
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-bg min-h-[650px] relative flex items-center">

        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full grid lg:grid-cols-3 gap-8 items-center">

            <!-- CATEGORY DROPDOWN -->
            <div class="glass rounded-2xl p-5">
                @php
                    $selectedGenre = $genres->firstWhere('genre_id', (int) request('genre'));
                @endphp

                <div class="mb-4">
                    <p class="text-xs font-bold uppercase tracking-[0.25em] text-gray-400">
                        Filter
                    </p>
                    <h2 class="text-2xl font-bold text-[#66c0f4]">
                        Browse Categories
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">
                        {{ $selectedGenre ? 'Aktif: ' . $selectedGenre->name : 'Pilih genre favoritmu' }}
                    </p>
                </div>

                <form action="{{ route('home') }}" method="GET">
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <div class="rounded-2xl bg-gradient-to-r from-[#06bfff] via-[#2d73ff] to-[#5c7e10] p-[1px] shadow-lg shadow-[#06bfff]/10">
                        <div class="relative">
                            <select
                                name="genre"
                                onchange="this.form.submit()"
                                class="w-full appearance-none rounded-2xl border-0 bg-[#0f1923] px-5 py-4 pr-12 text-white outline-none transition focus:bg-[#16202d]"
                            >
                                <option value="" {{ !request('genre') ? 'selected' : '' }}>
                                    All Games
                                </option>

                                @foreach($genres as $genre)
                                    <option value="{{ $genre->genre_id }}" {{ request('genre') == $genre->genre_id ? 'selected' : '' }}>
                                        {{ $genre->name }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-[#66c0f4]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    @if(request('genre'))
                        <a href="{{ route('home', request('search') ? ['search' => request('search')] : []) }}"
                           class="mt-3 inline-block text-sm font-semibold text-[#66c0f4] hover:text-white">
                            Reset kategori
                        </a>
                    @endif
                </form>
            </div>

            <!-- FEATURED GAME -->
            <div class="lg:col-span-2">
                @if($featuredGame)
                    <div class="glass rounded-3xl overflow-hidden shadow-2xl border border-[#2a475e]">

                        <img
                        src="{{ $featuredGame->thumbnail_url ?? 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=80&w=2070&auto=format&fit=crop' }}"
                        class="w-full h-[420px] object-cover"
                        >

                        <div class="p-8 bg-gradient-to-b from-[#1b2838] to-[#0f1923]">
                            <div class="flex flex-col lg:flex-row justify-between gap-6">

                                <div>
                                    <h2 class="text-5xl font-bold mb-4 leading-tight">
                                        {{ $featuredGame->title }}
                                    </h2>
                                    <p class="text-gray-300 max-w-2xl leading-relaxed text-lg">
                                        {{ Str::limit($featuredGame->detail->short_description ?? $featuredGame->description, 200) }}
                                    </p>
                                </div>

                                <div class="flex flex-col justify-end items-start lg:items-end gap-4">
                                    @php
                                        $fDiscount = $featuredGame->detail->discount ?? 0;
                                        $fOriginal = $featuredGame->price;
                                        $fFinal = $fDiscount > 0 ? $fOriginal * (1 - $fDiscount / 100) : $fOriginal;
                                    @endphp
                                    <div class="flex items-center gap-3">
                                        @if($fDiscount > 0)
                                        <span class="bg-[#4c6b22] text-[#beee11] px-3 py-2 rounded font-bold text-lg">
                                            -{{ $fDiscount }}%
                                        </span>
                                        <div>
                                            <div class="text-gray-400 line-through text-sm">
                                                Rp {{ number_format($fOriginal, 0, ',', '.') }}
                                            </div>
                                            <div class="text-3xl font-bold text-white">
                                                Rp {{ number_format($fFinal, 0, ',', '.') }}
                                            </div>
                                        </div>
                                        @else
                                        <div class="text-3xl font-bold text-white">
                                            {{ $fOriginal == 0 ? 'Gratis' : 'Rp ' . number_format($fOriginal, 0, ',', '.') }}
                                        </div>
                                        @endif
                                    </div>

                                    <a href="{{ url('/game/' . $featuredGame->game_id) }}"
                                       class="steam-blue px-8 py-4 rounded-xl font-bold text-lg hover:opacity-90 transition inline-block">
                                        View Game
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="glass rounded-3xl min-h-[420px] border border-[#2a475e] p-8 flex flex-col justify-center">
                        <h1 class="text-5xl font-bold mb-4 leading-tight">
                            Belum ada game
                        </h1>
                        <p class="text-gray-300 max-w-2xl leading-relaxed text-lg">
                            Tambahkan data game lewat admin atau import Excel supaya halaman store bisa menampilkan rekomendasi.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- SEARCH BAR -->
    <section class="bg-[#1f2f42] border-y border-[#2a475e] py-5 relative z-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="relative">

            <form action="{{ route('games.search') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    id="search-input"
                    name="search"
                    autocomplete="off"
                    placeholder="Search games..."
                    class="w-full
                           bg-[#0f1923]
                           border border-[#316282]
                           focus:border-[#66c0f4]
                           outline-none
                           px-5 py-4
                           rounded-xl
                           text-white"
                >

                <button
                    type="submit"
                    class="steam-blue px-8 rounded-xl font-semibold"
                >
                    Search
                </button>

            </form>

            <!-- DROPDOWN -->
            <div id="search-results"
                 class="absolute
                        top-full
                        left-0
                        w-full
                        bg-[#16202d]
                        border border-[#2a475e]
                        rounded-xl
                        mt-2
                        hidden
                        overflow-hidden
                        shadow-2xl
                        z-[999]">
            </div>

        </div>

    </div>

</section>

    <!-- FEATURED SECTION -->
    <section class="max-w-7xl mx-auto px-6 py-14">

        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">
                Featured & Recommended
            </h2>

            <a href="#" class="text-[#66c0f4] hover:underline">
                View More
            </a>
        </div>

        

            <!-- CARD 1 -->
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

    @forelse($games as $game)

        <a href="{{ url('/game/' . $game->game_id) }}" class="block h-full">

            <div class="game-card
                        h-full
                        flex
                        flex-col
                        bg-[#16202d]
                        rounded-2xl
                        overflow-hidden
                        border border-[#2a475e]
                        transition duration-300
                        cursor-pointer">

                <img
                    src="{{ $game->thumbnail_url ?? 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=80&w=2070&auto=format&fit=crop' }}"
                    class="h-52 w-full object-cover"
                >

                <div class="p-5 flex flex-col flex-1">

                    <!-- GAME TITLE -->
                    <h3 class="text-xl font-bold mb-2">
                        {{ $game->title }}
                    </h3>

                    <!-- DESCRIPTION -->
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                        {{ Str::limit($game->detail->short_description ?? $game->description, 80) }}
                    </p>

                    <!-- PUBLISHER -->
                    <div class="text-xs text-[#66c0f4] mb-3">
                        {{ $game->publisher->name ?? 'Unknown' }}
                    </div>

                    <!-- PRICE -->
                    @php
                        $disc = $game->detail->discount ?? 0;
                        $orig = $game->price;
                        $final = $disc > 0 ? $orig * (1 - $disc / 100) : $orig;
                    @endphp
                    <div class="flex justify-between items-center mt-auto">

                        @if($disc > 0)
                        <div class="flex items-center gap-2">
                            <span class="bg-[#4c6b22] text-[#beee11] text-xs font-black px-2 py-0.5 rounded">
                                -{{ $disc }}%
                            </span>
                            <div>
                                <div class="text-gray-500 line-through text-xs">
                                    Rp {{ number_format($orig, 0, ',', '.') }}
                                </div>
                                <div class="text-[#66c0f4] font-bold text-base">
                                    Rp {{ number_format($final, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        @else
                        <span class="text-[#66c0f4] font-bold text-lg">
                            {{ $orig == 0 ? 'Gratis' : 'Rp ' . number_format($orig, 0, ',', '.') }}
                        </span>
                        @endif

                        <button class="bg-[#2a475e] hover:bg-[#3b6a8b] px-4 py-2 rounded-lg text-sm transition">
                            View
                        </button>

                    </div>

                </div>
            </div>

        </a>

    @empty

        <div class="col-span-4 text-center py-20">

            <h2 class="text-3xl font-bold text-gray-300">

                Game not found

            </h2>

            <p class="text-gray-500 mt-3">

                Try another keyword.

            </p>

        </div>

    @endforelse

    </div>

<div class="flex justify-center mt-12">
    <div class="bg-[#16202d] border border-[#2a475e] rounded-xl px-4 py-2">
        {{ $games->links() }}
    </div>
</div>

    <!-- FOOTER -->
    <footer class="bg-[#171a21] border-t border-[#2a475e] mt-20">
        <div class="max-w-7xl mx-auto px-6 py-10 text-gray-400 text-sm">

            <div class="flex flex-col lg:flex-row justify-between gap-8">

                <div>
                    <h3 class="text-white text-lg font-bold mb-3">GAMESTORE</h3>
                    <p class="max-w-md leading-relaxed">
                        Digital distribution platform for games, downloadable content,
                        multiplayer experiences, and gaming communities.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-10">
                    <div>
                        <h4 class="text-white font-semibold mb-3">Links</h4>
                        <div class="space-y-2">
                            <a href="#" class="block hover:text-white">Home</a>
                            <a href="#" class="block hover:text-white">Games</a>
                            <a href="#" class="block hover:text-white">News</a>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-white font-semibold mb-3">Support</h4>
                        <div class="space-y-2">
                            <a href="#" class="block hover:text-white">Help Center</a>
                            <a href="#" class="block hover:text-white">Contact</a>
                            <a href="#" class="block hover:text-white">Privacy</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-[#2a475e] mt-8 pt-6 text-center text-xs text-gray-500">
                © 2026 GameStore. All rights reserved.
            </div>
        </div>
    </footer>
<script>

const searchInput = document.getElementById('search-input');
const searchResults = document.getElementById('search-results');

searchInput.addEventListener('input', async function () {

    const query = this.value.trim();

    if(query.length === 0){

        searchResults.classList.add('hidden');
        return;

    }

    try {

        const response = await fetch(`/search-games?search=${query}`);

        const games = await response.json();

        let html = '';

        if(games.length > 0){

            games.forEach(game => {

                html += `
                    <a href="/game/${game.game_id}"
                       class="flex items-center gap-4 p-3 hover:bg-[#1f2f42] transition">

                        <img
                            src="${game.thumbnail_url}"
                            class="w-24 h-14 object-cover rounded"
                        >

                        <div>

                            <div class="text-white font-semibold">
                                ${game.title}
                            </div>

                            <div class="text-[#66c0f4] text-sm">
                                Rp ${Number(game.price).toLocaleString('id-ID')}
                            </div>

                        </div>

                    </a>
                `;

            });

        } else {

            html = `
                <div class="p-4 text-gray-400">
                    Game not found
                </div>
            `;

        }

        searchResults.innerHTML = html;
        searchResults.classList.remove('hidden');

    } catch(error){

        console.log(error);

    }

});

// klik luar
document.addEventListener('click', function(e){

    if(!searchInput.contains(e.target) &&
       !searchResults.contains(e.target)){

        searchResults.classList.add('hidden');

    }

});

</script>
</body>
</html>
