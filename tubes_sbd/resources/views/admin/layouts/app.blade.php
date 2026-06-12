<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hub - @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        [x-cloak] { display: none !important; }

        :root {

            --admin-bg: #050a12;
            --admin-card: rgba(255, 255, 255, 0.02);
            --admin-accent: #118dff;
            --admin-accent-glow: rgba(17, 141, 255, 0.3);
        }

        body {
            background: var(--admin-bg);
            color: #c6d4df;
            font-family: 'Figtree', sans-serif;
            overflow: hidden;
        }

        .admin-sidebar {
            background: rgba(15, 25, 35, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid rgba(255, 255, 255, 0.05);
        }

        .premium-card {
            background: var(--admin-card);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }
        .premium-card:hover {
            border-color: rgba(17, 141, 255, 0.2);
            background: rgba(255, 255, 255, 0.04);
        }

        .nav-link-premium {
            display: flex;
            align-items: center;
            padding: 0.875rem 1.5rem;
            color: rgba(255, 255, 255, 0.4);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 0.75rem;
            border-radius: 1rem;
            margin: 0.25rem 0.75rem;
            transition: all 0.3s ease;
        }
        .nav-link-premium:hover, .nav-link-premium.active {
            color: #fff;
            background: rgba(17, 141, 255, 0.1);
            box-shadow: inset 0 0 20px rgba(17, 141, 255, 0.05);
        }
        .nav-link-premium.active {
            color: var(--admin-accent);
            border: 1px solid rgba(17, 141, 255, 0.2);
        }

        .admin-header {
            background: rgba(15, 25, 35, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .mobile-bottom-nav {
            background: rgba(15, 25, 35, 0.95);
            backdrop-filter: blur(30px);
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(17, 141, 255, 0.3); }

        /* Mobile Drawer Sidebar */
        .mobile-sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
            z-index: 100;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }
        .mobile-sidebar-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .mobile-sidebar-drawer {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 288px;
            background: rgba(10, 20, 32, 0.98);
            backdrop-filter: blur(30px);
            border-right: 1px solid rgba(255, 255, 255, 0.07);
            z-index: 101;
            transform: translateX(-100%);
            transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-col: column;
            overflow-y: auto;
            box-shadow: 20px 0 60px rgba(0,0,0,0.5);
        }
        .mobile-sidebar-drawer.open {
            transform: translateX(0);
        }

        .hamburger-btn {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.08);
            cursor: pointer;
            transition: all 0.2s ease;
        }
        @media (max-width: 1024px) {
            .hamburger-btn {
                display: flex;
            }
        }
        .hamburger-btn:hover {
            background: rgba(17, 141, 255, 0.15);
            border-color: rgba(17, 141, 255, 0.3);
        }
        .hamburger-btn .bar {
            display: block;
            width: 18px;
            height: 2px;
            background: #c6d4df;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .hamburger-btn.active .bar:nth-child(1) {
            transform: translateY(6px) rotate(45deg);
        }
        .hamburger-btn.active .bar:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }
        .hamburger-btn.active .bar:nth-child(3) {
            transform: translateY(-6px) rotate(-45deg);
        }

        @media (max-width: 1024px) {
            .main-content { padding-bottom: 80px; }
        }
    </style>
    @stack('styles')
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Mobile Sidebar Overlay -->
    <div class="mobile-sidebar-overlay lg:hidden" id="mobileOverlay" onclick="closeMobileSidebar()"></div>

    <!-- Mobile Sidebar Drawer -->
    <div class="mobile-sidebar-drawer lg:hidden flex flex-col" id="mobileSidebar">
        <!-- Header Drawer -->
        <div class="p-6 border-b border-white/5 flex items-center justify-between">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="h-10 w-10 rounded-xl bg-white/5 p-1.5 transition-all group-hover:scale-110 group-hover:bg-[#118dff]/10">
                    <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div>
                    <span class="text-xl font-black tracking-tighter text-white block leading-none">ADMIN<span class="text-[#118dff]">HUB</span></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-gray-500">PlayMart Core</span>
                </div>
            </a>
            <button onclick="closeMobileSidebar()" class="h-9 w-9 rounded-xl bg-white/5 flex items-center justify-center border border-white/10 text-gray-400 hover:text-white hover:bg-white/10 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <!-- Nav Items Drawer -->
        <nav class="flex-1 overflow-y-auto py-5">
            <div class="px-6 mb-3">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-600">Management</h3>
            </div>
            <ul class="space-y-1">
                @php
                    $menuItemsMobile = [
                        ['route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Dashboard'],
                        ['route' => 'admin.games.index', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'label' => 'Games'],
                        ['route' => 'admin.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Users'],
                        ['route' => 'admin.payments.index', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Payments'],
                        ['route' => 'admin.reviews.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z', 'label' => 'Reviews'],
                        ['route' => 'admin.developers.index', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'label' => 'Developers'],
                        ['route' => 'admin.publishers.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Publishers'],
                        ['route' => 'admin.genres.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'label' => 'Genres'],
                        ['route' => 'admin.categories.index', 'icon' => 'M4 7h16M4 12h16M4 17h10', 'label' => 'Categories'],
                    ];
                @endphp
                @foreach($menuItemsMobile as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" onclick="closeMobileSidebar()" class="nav-link-premium {{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path></svg>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <!-- Back to Store -->
        <div class="p-6 border-t border-white/5">
            <a href="{{ route('home') }}" class="flex items-center justify-center w-full px-6 py-3.5 rounded-2xl bg-white text-black font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Main Store
            </a>
        </div>
    </div>

    <!-- Desktop Sidebar -->
    <aside class="w-72 admin-sidebar hidden lg:flex flex-col shadow-2xl">
        <div class="p-8 border-b border-white/5">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-4 group">
                <div class="h-12 w-12 rounded-2xl bg-white/5 p-2 transition-all group-hover:scale-110 group-hover:bg-[#118dff]/10">
                    <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div>
                    <span class="text-2xl font-black tracking-tighter text-white block leading-none">ADMIN<span class="text-[#118dff]">HUB</span></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-gray-500">PlayMart Core</span>
                </div>
            </a>
        </div>

        <nav class="flex-1 overflow-y-auto py-6">
            <div class="px-8 mb-4">
                <h3 class="text-[10px] font-black uppercase tracking-[0.3em] text-gray-600">Management</h3>
            </div>
            <ul class="space-y-1">
                @php
                    $menuItems = [
                        ['route' => 'admin.dashboard', 'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z', 'label' => 'Dashboard'],
                        ['route' => 'admin.games.index', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z', 'label' => 'Games'],
                        ['route' => 'admin.users.index', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Users'],
                        ['route' => 'admin.payments.index', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Payments'],
                        ['route' => 'admin.reviews.index', 'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z', 'label' => 'Reviews'],
                        ['route' => 'admin.developers.index', 'icon' => 'M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4', 'label' => 'Developers'],
                        ['route' => 'admin.publishers.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label' => 'Publishers'],
                        ['route' => 'admin.genres.index', 'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z', 'label' => 'Genres'],
                        ['route' => 'admin.categories.index', 'icon' => 'M4 7h16M4 12h16M4 17h10', 'label' => 'Categories'],
                    ];
                @endphp
                
                @foreach($menuItems as $item)
                    <li>
                        <a href="{{ route($item['route']) }}" class="nav-link-premium {{ request()->routeIs($item['route'] . '*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $item['icon'] }}"></path></svg>
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="p-8 border-t border-white/5">
            <a href="{{ route('home') }}" class="flex items-center justify-center w-full px-6 py-4 rounded-2xl bg-white text-black font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Main Store
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Header -->
        <header class="admin-header flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8 lg:py-5 z-40">
            <div class="flex items-center gap-3">
                <!-- Hamburger Button (Mobile Only) -->
                <button id="hamburgerBtn" class="hamburger-btn lg:hidden" onclick="toggleMobileSidebar()" aria-label="Toggle navigation">
                    <div class="flex flex-col gap-[5px]">
                        <span class="bar"></span>
                        <span class="bar"></span>
                        <span class="bar"></span>
                    </div>
                </button>
                <div class="lg:hidden h-10 w-10 rounded-xl bg-white/5 p-2">
                    <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <h1 class="truncate text-lg font-black tracking-tight text-white sm:text-2xl">@yield('title', 'Dashboard')</h1>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="hidden sm:flex flex-col items-end">
                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Active Session</span>
                    <span class="text-xs font-bold text-[#66c0f4]">{{ auth()->user()->name }}</span>
                </div>
                
                <div class="h-10 w-px bg-white/5 mx-2"></div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="h-11 w-11 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center border border-red-500/20 hover:bg-red-500 hover:text-white transition-all active:scale-90 shadow-lg shadow-red-500/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="main-content flex-1 overflow-y-auto px-4 py-6 sm:px-6 lg:p-8 store-scrollbar">
            <!-- Breadcrumbs or Back to Home for Mobile -->
            <div class="lg:hidden mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 px-6 py-3 rounded-xl bg-[#118dff]/10 text-[#118dff] font-black text-xs uppercase tracking-widest border border-[#118dff]/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Store Front
                </a>
            </div>

            @if(session('success'))
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-5 flex items-center gap-4 text-emerald-400 font-bold shadow-xl shadow-emerald-500/5">
                    <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 rounded-2xl bg-red-500/10 border border-red-500/20 p-5 flex items-center gap-4 text-red-400 font-bold shadow-xl shadow-red-500/5">
                    <div class="h-10 w-10 rounded-xl bg-red-500/20 flex items-center justify-center shrink-0">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </div>

        <!-- Mobile Bottom Nav -->
        <nav class="fixed bottom-0 left-0 right-0 h-20 mobile-bottom-nav lg:hidden z-50 flex items-center justify-around px-4">
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.dashboard') ? 'text-[#118dff]' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Home</span>
            </a>
            <a href="{{ route('admin.games.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.games.*') ? 'text-[#118dff]' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Games</span>
            </a>
            <a href="{{ route('admin.payments.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.payments.*') ? 'text-[#118dff]' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Pay</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex flex-col items-center gap-1 {{ request()->routeIs('admin.users.*') ? 'text-[#118dff]' : 'text-gray-500' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                <span class="text-[8px] font-black uppercase tracking-widest">Users</span>
            </a>
        </nav>
    </main>


    <x-confirm-modal />

    @stack('scripts')



    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileOverlay');
            const btn = document.getElementById('hamburgerBtn');
            const isOpen = sidebar.classList.contains('open');
            if (isOpen) {
                closeMobileSidebar();
            } else {
                sidebar.classList.add('open');
                overlay.classList.add('open');
                btn.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileOverlay');
            const btn = document.getElementById('hamburgerBtn');
            sidebar.classList.remove('open');
            overlay.classList.remove('open');
            btn.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Close on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeMobileSidebar();
        });
    </script>
</body>
</html>
