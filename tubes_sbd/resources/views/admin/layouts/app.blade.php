<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #171d25;
            color: #c6d4df;
            font-family: 'Motiva Sans', Arial, Helvetica, sans-serif;
            overflow-x: hidden;
        }

        .admin-sidebar {
            background: #1b2838;
            border-right: 1px solid #2a475e;
        }

        .admin-content {
            background: #171d25;
        }

        .steam-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid #2a475e;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        .nav-link {
            color: #c6d4df;
            transition: all 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #fff;
            background: #2a475e;
            border-left: 3px solid #66c0f4;
        }

        .steam-btn-primary {
            background: linear-gradient(to bottom, #47bfff 5%, #1a44c2 100%);
            color: white;
            border: none;
            transition: .2s ease;
        }

        .steam-btn-primary:hover {
            background: linear-gradient(to bottom, #66c0f4 5%, #1a44c2 100%);
            transform: scale(1.02);
        }

        .steam-btn-danger {
            background: linear-gradient(to right, #8b0000, #ff4c4c);
            color: white;
            border: none;
            transition: .2s ease;
        }
        
        .steam-btn-danger:hover {
            background: linear-gradient(to right, #a52a2a, #ff6666);
            transform: scale(1.02);
        }

        .steam-table th {
            background: #1b2838;
            color: #66c0f4;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.05em;
        }

        .steam-table tr {
            border-bottom: 1px solid #2a475e;
        }

        .steam-table tr:hover {
            background: rgba(42, 71, 94, 0.4);
        }
        
        .steam-input {
            background: #222c36;
            border: 1px solid #1b2838;
            color: #fff;
            border-radius: 4px;
        }
        .steam-input:focus {
            outline: none;
            border-color: #66c0f4;
            box-shadow: 0 0 5px rgba(102, 192, 244, 0.5);
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <!-- Sidebar -->
    <aside class="w-64 admin-sidebar flex flex-col hidden md:flex">
        <div class="p-6 border-b border-gray-700/50 flex items-center gap-3">
            <svg class="w-8 h-8 text-[#66c0f4]" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
            </svg>
            <span class="text-xl font-bold text-white tracking-wider">ADMIN</span>
        </div>

        <nav class="flex-1 overflow-y-auto py-4">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.games.index') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.games.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Games
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users.index') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.developers.index') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.developers.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                        Developers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.publishers.index') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.publishers.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Publishers
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.genres.index') }}" class="nav-link flex items-center px-6 py-3 {{ request()->routeIs('admin.genres.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                        Genres
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t border-gray-700/50">
            <a href="/" class="flex items-center justify-center w-full px-4 py-2 text-sm text-gray-300 bg-gray-800 hover:bg-gray-700 rounded transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Web
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col admin-content overflow-hidden">
        <!-- Header -->
        <header class="bg-[#1b2838] border-b border-[#2a475e] p-4 flex items-center justify-between shadow-sm">
            <h1 class="text-xl font-bold text-white">@yield('title', 'Dashboard')</h1>
            
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-400">Admin: <span class="text-[#66c0f4]">{{ auth()->user()->name ?? 'Administrator' }}</span></span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs px-3 py-1 bg-gray-700 hover:bg-red-600 text-white rounded transition">Logout</button>
                </form>
            </div>
        </header>

        <!-- Content Area -->
        <div class="flex-1 overflow-auto p-6">
            @if(session('success'))
                <div class="bg-green-900/50 border-l-4 border-green-500 text-green-200 p-4 mb-6 rounded shadow" role="alert">
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-900/50 border-l-4 border-red-500 text-red-200 p-4 mb-6 rounded shadow" role="alert">
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-900/50 border-l-4 border-red-500 text-red-200 p-4 mb-6 rounded shadow">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
