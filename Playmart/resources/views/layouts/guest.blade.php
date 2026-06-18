<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

         <title>@yield('title', 'PlayMart')</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">


        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased">
        <div class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-8 sm:px-6">
            <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(17,141,255,0.18),transparent_34rem)]"></div>
            <div class="relative w-full max-w-md overflow-hidden rounded-3xl border border-white/10 bg-[#0b1420]/85 px-5 py-6 shadow-2xl shadow-black/40 backdrop-blur-2xl sm:px-8 sm:py-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
