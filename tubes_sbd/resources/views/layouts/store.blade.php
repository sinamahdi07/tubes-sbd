@php
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('GAMESTORE.png') }}">
    <title>@yield('title', 'PlayMart - Store')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
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
            background: rgba(5, 10, 18, 0.88);
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
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -22px;
            height: 3px;
            border-radius: 999px;
            background: #118dff;
            box-shadow: 0 0 16px rgba(17, 141, 255, 0.8);
        }
        @media (max-width: 768px) {
            .store-container { width: min(100% - 24px, 1700px); }
            .nav-link.is-active::after { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen text-white">
    <x-store-nav />

    @yield('content')

    <x-store-footer />

    @stack('scripts')
</body>
</html>
