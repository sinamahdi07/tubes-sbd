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
                    @auth
                        <a href="{{ route('friends.index') }}" class="hover:text-white">Teman</a>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-white">Teman</a>
                    @endauth
                    <a href="#" class="hover:text-white">About</a>
                    <a href="#" class="hover:text-white">Support</a>
                    <a href="{{ route('cart.index') }}">Cart</a>
                </div>
            </div>

            <div class="flex items-center gap-4">

                @auth
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}"
                           class="flex items-center gap-2 px-4 py-2 rounded text-sm font-bold transition"
                           style="background: linear-gradient(90deg, #1a44c2, #06bfff); color: #fff;">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Admin Panel
                        </a>
                    @endif

                    <a href="{{ route('friends.index') }}" class="text-sm text-gray-300 hover:text-white font-semibold">
                        Teman
                    </a>

                    <span class="text-sm text-gray-300">Halo, <span class="text-[#66c0f4] font-semibold">{{ auth()->user()->name }}</span></span>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-700 hover:bg-red-700 px-4 py-2 rounded text-sm font-semibold transition text-white">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-300 hover:text-white font-semibold">
                        Login
                    </a>
                    <a href="{{ route('register') }}"
                       class="bg-[#5c7e10] hover:bg-[#7ea64b] px-4 py-2 rounded text-sm font-semibold transition">
                        Register
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero-bg min-h-[650px] relative flex items-center">

        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 w-full grid lg:grid-cols-3 gap-8 items-center">

            <!-- LEFT MENU -->
            <div class="glass rounded-2xl p-5 hidden lg:block">
                <h2 class="text-xl font-bold mb-4 text-[#66c0f4]">
                    Browse Categories
                </h2>

                <div class="space-y-2 text-gray-300 text-sm">

    <a href="/"
       class="sidebar-item block p-3 rounded-lg transition
       {{ !request('genre') ? 'bg-[#66c0f4] text-black font-semibold' : '' }}">
        All Games
    </a>

    @foreach($genres as $genre)

        <a href="/?genre={{ $genre->genre_id }}"
           class="sidebar-item block p-3 rounded-lg transition

           {{ request('genre') == $genre->genre_id
               ? 'bg-[#66c0f4] text-black font-semibold'
               : '' }}">

            {{ $genre->name }}

        </a>

    @endforeach

</div>
            </div>

            <!-- FEATURED GAME -->
            <div class="lg:col-span-2">
                <div class="glass rounded-3xl overflow-hidden shadow-2xl border border-[#2a475e]">

                    <img
                    src="{{ $featuredGame->thumbnail_url }}"
                    class="w-full h-[420px] object-cover"
                    >

                    <div class="p-8 bg-gradient-to-b from-[#1b2838] to-[#0f1923]">
                        <div class="flex flex-col lg:flex-row justify-between gap-6">

                            <div>
                                <h1 class="text-5xl font-bold mb-4 leading-tight">
                                    {{ $featuredGame->title }}
                                </h1>

                                <p class="text-gray-300 max-w-2xl leading-relaxed text-lg">
                                    {{ Str::limit($featuredGame->description, 200) }}
                                </p>
                            </div>

                            <div class="flex flex-col justify-end items-start lg:items-end gap-4">
                                <div class="flex items-center gap-3">
                                    <span class="bg-[#4c6b22] text-[#beee11] px-3 py-2 rounded font-bold text-lg">
                                        -35%
                                    </span>

                                    <div>
                                        <div class="text-gray-400 line-through text-sm">
                                            Rp 500
                                        </div>
                                        <div class="text-3xl font-bold text-white">
                                            Rp {{ number_format($featuredGame->price, 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>

                                <a
                                    href="{{ url('/game/' . $featuredGame->game_id) }}"
                                    class="steam-blue px-8 py-4 rounded-xl font-bold text-lg hover:opacity-90 transition inline-block"
                                >
                                    View Game
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
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

                <div class="p-5" flex flex-col flex-1>

                    <!-- GAME TITLE -->
                    <h3 class="text-xl font-bold mb-2">
                        {{ $game->title }}
                    </h3>

                    <!-- DESCRIPTION -->
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">

                        {{ Str::limit($game->description, 80) }}

                    </p>

                    <!-- PUBLISHER -->
                    <div class="text-xs text-[#66c0f4] mb-3">

                        Publisher:
                        {{ $game->publisher->name ?? 'Unknown' }}

                    </div>

                    <!-- PRICE -->
                    <div class="flex justify-between items-center mt-auto">

                        <span class="text-[#66c0f4] font-bold text-lg">

                            Rp {{ number_format($game->price, 0, ',', '.') }}

                        </span>

                        <button class="bg-[#2a475e]
                                       hover:bg-[#3b6a8b]
                                       px-4 py-2
                                       rounded-lg
                                       text-sm
                                       transition">

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
