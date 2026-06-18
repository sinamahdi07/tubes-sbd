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

    <style>
        body { font-family: 'Figtree', sans-serif; }
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

        .mobile-nav-item {
            transition: color 0.2s ease, background-color 0.2s ease, transform 0.2s ease;
            position: relative;
        }
        .mobile-nav-item.is-active {
            color: #66c0f4;
            background: rgba(17, 141, 255, 0.12);
        }
        .active-indicator {
            position: absolute;
            bottom: 4px;
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

    <div class="pb-20 lg:pb-0">
        @yield('content')
        <x-store-footer />
    </div>

    <nav class="safe-bottom fixed inset-x-0 bottom-0 z-[9999] grid min-h-16 grid-cols-5 items-center border-t border-white/10 bg-[#07111d]/96 px-1 pt-1 shadow-[0_-12px_30px_rgba(0,0,0,0.45)] backdrop-blur-2xl sm:inset-x-3 sm:bottom-3 sm:rounded-2xl sm:border lg:hidden">
        
        {{-- Search --}}
        <a href="{{ route('home') }}" class="mobile-nav-item flex min-w-0 flex-col items-center justify-center gap-0.5 rounded-lg py-1.5 {{ request()->routeIs('home') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M3 11.5 12 4l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-8.5Z" />
            </svg>
            <span class="text-[8px] font-black uppercase tracking-wide">Store</span>
            @if(request()->routeIs('home')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('games.search') }}" class="mobile-nav-item flex min-w-0 flex-col items-center justify-center gap-0.5 rounded-lg py-1.5 {{ request()->routeIs('games.search') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="text-[8px] font-black uppercase tracking-wide">Search</span>
            @if(request()->routeIs('games.search')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ route('cart.index') }}" class="mobile-nav-item relative flex min-w-0 flex-col items-center justify-center gap-0.5 rounded-lg py-1.5 {{ request()->routeIs('cart.*') ? 'is-active' : 'text-gray-400' }}">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -right-3 -top-2 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#118dff] px-1 text-[9px] font-black text-white ring-2 ring-[#07111d]">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            </div>
            <span class="text-[8px] font-black uppercase tracking-wide">Cart</span>
            @if(request()->routeIs('cart.*')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ auth()->check() ? route('chat.index') : route('login') }}" class="mobile-nav-item flex min-w-0 flex-col items-center justify-center gap-0.5 rounded-lg py-1.5 {{ request()->routeIs('chat.*') ? 'is-active' : 'text-gray-400' }}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" />
            </svg>
            <span class="text-[8px] font-black uppercase tracking-wide">Chat</span>
            @if(request()->is('chat*')) <div class="active-indicator"></div> @endif
        </a>

        <a href="{{ auth()->check() ? route('profile.show') : route('login') }}" class="mobile-nav-item flex min-w-0 flex-col items-center justify-center gap-0.5 rounded-lg py-1.5 {{ request()->routeIs('profile.*') ? 'is-active' : 'text-gray-400' }}">
            <div class="h-6 w-6 overflow-hidden rounded-full border {{ request()->routeIs('profile.show') ? 'border-[#66c0f4]' : 'border-gray-500' }}">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'Guest') }}&background=0D8ABC&color=fff" alt="Profile" class="h-full w-full object-cover">
            </div>
            <span class="text-[8px] font-black uppercase tracking-wide">Me</span>
            @if(request()->routeIs('profile.*')) <div class="active-indicator"></div> @endif
        </a>
    </nav>

    <x-confirm-modal />

    @stack('scripts')
</body>
</html>
