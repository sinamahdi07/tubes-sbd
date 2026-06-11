@php
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
    $wishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'PlayMart'))</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body { font-family: 'Figtree', sans-serif; background-color: #050a12; }
        .nav-link.is-active::after {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            bottom: -22px;
            height: 3px;
            border-radius: 999px;
            background: #118dff;
            box-shadow: 0 0 16px rgba(17, 141, 255, 0.8);
        }

        @media (max-width: 640px) {
            html { font-size: 17px; }
            .store-container { width: min(100% - 20px, 1700px); }
        }

        /* Custom style untuk navigasi bawah agar lebih cantik dan BESAR */
        .mobile-nav-item {
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
        }
        .mobile-nav-item.is-active {
            color: #66c0f4;
            transform: translateY(-10px) scale(1.1);
        }
        .active-indicator {
            position: absolute;
            bottom: -6px;
            width: 6px;
            height: 6px;
            border-radius: 999px;
            background: #66c0f4;
            box-shadow: 0 0 15px #66c0f4;
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen text-white bg-[#050a12] antialiased">
    <x-store-nav />

    <div class="pb-36 lg:pb-0">
        @yield('content')
        <x-store-footer />
    </div>

    {{-- Navigasi bawah Mobile Universal - LEBIH GEDE --}}
    <nav class="fixed bottom-6 left-4 right-4 z-[9999] flex h-24 items-center justify-around rounded-[2.5rem] border-2 border-white/10 bg-[#07111d]/95 pb-safe px-3 shadow-[0_25px_60px_rgba(0,0,0,0.8)] backdrop-blur-3xl lg:hidden">
        
        {{-- Search --}}
        <a href="{{ route('games.search') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('games.search') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider">Search</span>
            @if(request()->routeIs('games.search')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Wishlist --}}
        <a href="{{ url('/wishlist') }}" class="mobile-nav-item relative flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->is('wishlist*') ? 'is-active' : 'text-gray-400' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                @if($wishlistCount > 0)
                    <span class="absolute -right-2 -top-1 flex h-3 w-3 items-center justify-center rounded-full bg-[#3bd2ff] text-[7px] font-black text-[#07111d] ring-1 ring-[#07111d]">
                        {{ $wishlistCount }}
                    </span>
                @endif
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider">Wish</span>
            @if(request()->is('wishlist*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Cart --}}
        <a href="{{ route('cart.index') }}" class="mobile-nav-item relative flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('cart.index') ? 'is-active' : 'text-gray-400' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -right-2 -top-1 flex h-3 w-3 items-center justify-center rounded-full bg-success text-[7px] font-black text-[#07111d] ring-1 ring-[#07111d]">
                        {{ $cartCount }}
                    </span>
                @endif
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider">Cart</span>
            @if(request()->routeIs('cart.index')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Store Center --}}
        <a href="{{ route('home') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center -mt-10">
            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-tr from-[#118dff] to-[#66c0f4] text-white shadow-[0_15px_35px_rgba(17,141,255,0.5)] border-4 border-[#050a12] active:scale-95 transition-transform">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            <span class="text-[10px] font-black uppercase tracking-widest text-[#66c0f4] mt-1">Store</span>
        </a>

        {{-- Chat --}}
        <a href="{{ url('/chat') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->is('chat*') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider">Chat</span>
            @if(request()->is('chat*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Friends --}}
        <a href="{{ route('friends.index') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('friends.*') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="text-[9px] font-black uppercase tracking-wider">Social</span>
            @if(request()->routeIs('friends.*')) <div class="active-indicator"></div> @endif
        </a>

        {{-- Profile --}}
        <a href="{{ route('profile.show') }}" class="mobile-nav-item flex flex-1 flex-col items-center justify-center gap-1.5 {{ request()->routeIs('profile.show') ? 'is-active' : 'text-gray-400' }}">
            <div class="h-8 w-8 overflow-hidden rounded-full border-2 {{ request()->routeIs('profile.show') ? 'border-[#66c0f4]' : 'border-gray-500' }}">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}&background=0D8ABC&color=fff" alt="Profile" class="h-full w-full object-cover">
            </div>
            <span class="text-[9px] font-black uppercase tracking-wider">Me</span>
            @if(request()->routeIs('profile.show')) <div class="active-indicator"></div> @endif
        </a>
    </nav>

    @stack('scripts')
</body>
</html>
