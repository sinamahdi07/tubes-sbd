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
                    <a href="{{ route('cart.index') }}" class="hover:text-white relative">
                        Cart
                        @php
                            $cartCount = auth()->check() ? auth()->user()->carts()->sum('quantity') : 0;
                        @endphp
                        @if($cartCount > 0)
                            <span class="absolute -top-2 -right-3 bg-red-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-store-user-menu />
            </div>
        </div>
    </nav>



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
@yield('content')

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
