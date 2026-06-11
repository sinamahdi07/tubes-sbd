@php
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
    $wishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
@endphp
<!DOCTYPE html> {{-- Pastikan ini tidak ada di app.blade.php jika sudah ada --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>@yield('title', 'PlayMart - Store')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style> /* Ini akan di-push ke head */
        html { scroll-behavior: smooth; }
        body {
            background:
                radial-gradient(circle at 16% -8%, rgba(45, 115, 255, 0.18), transparent 28rem),
                radial-gradient(circle at 86% 4%, rgba(102, 192, 244, 0.12), transparent 24rem),
                linear-gradient(180deg, #050a12 0%, #07111d 42%, #091523 100%);
            color: #fff;
            font-family: Arial, Helvetica, sans-serif;
            overflow-x: hidden;
        }
        .steam-blue { background: linear-gradient(135deg, #06bfff, #2d73ff); }
        .top-nav {
            position: sticky;
            top: 0;
            z-index: 1001; /* Di atas segalanya kecuali mobile bottom nav */
            background: rgba(5, 10, 18, 0.95); /* Sedikit lebih gelap */
            border-bottom: 1px solid rgba(42, 71, 94, 0.76);
            backdrop-filter: blur(18px);
            box-shadow: 0 12px 34px rgba(0, 0, 0, 0.26);
        }
        .store-container {
            width: min(100% - 32px, 1700px);
            margin-inline: auto;
        }
        .nav-link {
            position: relative;
            color: rgba(229, 236, 245, 0.76);
            transition: color .2s ease;
        }
        .nav-link:hover,
        .nav-link.is-active { color: #fff; }
        .nav-link.is-active::after {
            content: ""; /* Indikator aktif di desktop */
            position: absolute;
            left: 0;
            right: 0;
            bottom: -22px;
            height: 3px;
            border-radius: 999px;
            background: #118dff;
            box-shadow: 0 0 16px rgba(17, 141, 255, 0.8);
        }
            @media (max-width: 1024px) {
            .store-container { width: min(100% - 24px, 1700px); }
            .nav-link.is-active::after { display: none; } /* Sembunyikan indikator aktif di mobile */
        }

        /* Custom style untuk navigasi bawah agar lebih cantik */
        .mobile-nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .active-indicator {
            width: 12px;
            height: 2px;
            margin-top: 2px;
            border-radius: 999px;
            background: #66c0f4;
            box-shadow: 0 0 10px #66c0f4;
        }
        .nav-center-glow {
            background: linear-gradient(135deg, #66c0f4 0%, #2d73ff 100%);
            box-shadow: 0 0 25px rgba(102, 192, 244, 0.4), inset 0 1px 1px rgba(255, 255, 255, 0.3);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen text-white">
    {{-- Navigasi atas: Dipaksa sembunyi di mobile via wrapper untuk memastikan tidak muncul --}}
    <div class="hidden lg:block">
        <x-store-nav />
    </div>

    <div class="{{ request()->routeIs('home') ? 'pb-24 lg:pb-0' : '' }}">
        @yield('content')
    </div>

    {{-- Navigasi bawah Mobile --}}
    <nav class="fixed bottom-0 left-0 right-0 z-[9999] flex h-20 w-full items-center justify-around border-t border-[#2a475e]/30 bg-[#07111d]/98 pb-safe px-1 shadow-[0_-10px_40px_rgba(0,0,0,0.8)] backdrop-blur-3xl lg:hidden">
        
        {{-- Library --}}
        <a href="{{ route('profile.games') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('profile.games') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
            <span class="text-[7px] font-black uppercase tracking-widest">Library</span>
            @if(request()->routeIs('profile.games')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Friends --}}
        <a href="{{ route('friends.index') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('friends.*') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="text-[7px] font-black uppercase tracking-widest">Social</span>
            @if(request()->routeIs('friends.*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Wishlist (With Notification) --}}
        <a href="{{ url('/wishlist') }}" class="mobile-nav-item relative flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->is('wishlist*') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                @if($wishlistCount > 0)
                    <span class="absolute -right-2 -top-1.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-[#3bd2ff] text-[7px] font-black text-[#07111d] ring-2 ring-[#07111d] animate-pulse">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </div>
            <span class="text-[7px] font-black uppercase tracking-widest">Wish</span>
            @if(request()->is('wishlist*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Store --}}
        <a href="{{ route('games.search') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('games.search') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-[7px] font-black uppercase tracking-widest">Store</span>
            @if(request()->routeIs('games.search')) <div class="active-indicator"></div> @endif
        </a>


        {{-- Chat --}}
        <a href="{{ url('/chat') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->is('chat*') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
            </svg>
            <span class="text-[7px] font-black uppercase tracking-widest">Chat</span>
            @if(request()->is('chat*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Cart (With Notification) --}}
        <a href="{{ route('cart.index') }}" class="mobile-nav-item relative flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('cart.index') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -right-2 -top-1.5 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-[#ff4b4b] text-[7px] font-black text-white ring-2 ring-[#07111d] animate-bounce">
                        {{ $cartCount }}
                    </span>
                @endif
            </div>
            <span class="text-[7px] font-black uppercase tracking-widest">Cart</span>
            @if(request()->routeIs('cart.index')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile.show') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('profile.show') ? 'text-[#66c0f4] is-active' : 'text-gray-400' }}">
            <div class="h-6 w-6 overflow-hidden rounded-full border-2 {{ request()->routeIs('profile.show') ? 'border-[#66c0f4]' : 'border-gray-500' }}">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}&background=0D8ABC&color=fff" alt="Profile" class="h-full w-full object-cover">
            </div>
            <span class="text-[7px] font-black uppercase tracking-widest">Me</span>
            @if(request()->routeIs('profile.show')) <div class="active-indicator"></div> @endif
        </a>
    </nav>

    <x-store-footer />

    @stack('scripts')
</body>
</html>
