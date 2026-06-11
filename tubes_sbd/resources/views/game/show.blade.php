<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <title>{{ $game->title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body { 
            background-color: #07111d;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }

        .wishlist-button.is-active {
            border-color: #66c0f4;
            background: rgba(102, 192, 244, .12);
            color: #66c0f4;
        }

        .related-game-card img {
            transition: transform .28s ease, filter .28s ease;
        }

        .related-game-card:hover img {
            transform: scale(1.05);
            filter: saturate(1.12);
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Spotify-style Hero Overlay */
        .hero-gradient {
            background: linear-gradient(to bottom, transparent 0%, rgba(7, 17, 29, 0.4) 50%, rgba(7, 17, 29, 1) 100%);
        }

        .spotify-fab {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            transition: all 0.2s ease;
        }

        .spotify-fab:active {
            transform: scale(0.95);
            filter: brightness(0.9);
        }

        .sticky-buy-bar {
            box-shadow: 0 -10px 25px rgba(0,0,0,0.5);
        }
    </style>
</head>

<body class="text-white min-h-screen pb-32 lg:pb-10">

    @php
        // Definisikan harga dan diskon agar bisa dipakai di seluruh halaman
        $discount = $game->detail->discount ?? 0;
        $originalPrice = $game->price;
        $finalPrice = $discount > 0 ? $originalPrice * (1 - $discount / 100) : $originalPrice;

        // Inisialisasi status user
        $isPurchased = false;
        $isInCart = false;
        $isWishlisted = false;

        if(auth()->check()) {
            $userId = auth()->id();
            $isPurchased = \App\Models\Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
                ->where('payments.user_id', $userId)
                ->where('payment_items.game_id', $game->game_id)
                ->where('payments.status', \App\Models\Payment::STATUS_PAID)
                ->exists();

            $isInCart = \App\Models\Cart::where('user_id', $userId)
                ->where('game_id', $game->game_id)
                ->exists();

            $isWishlisted = \App\Models\Wishlist::where('user_id', $userId)
                ->where('game_id', $game->game_id)
                ->exists();
        }
    @endphp

    <x-store-nav />

    <!-- TOP HERO -->
    <section class="relative h-[45vh] sm:h-[50vh] md:h-[65vh] overflow-hidden">
        <img
            src="{{ $game->thumbnail_url }}"
            class="absolute inset-0 w-full h-full object-cover"
        >
        <div class="absolute inset-0 hero-gradient"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex flex-col justify-end pb-8">
            <div class="w-full">
                <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-[#66c0f4] mb-2 opacity-90">
                    Official Store
                </p>
                
                <h1 class="text-5xl sm:text-6xl md:text-8xl font-black mb-6 tracking-tighter leading-[0.85] uppercase italic">
                    {{ $game->title }}
                </h1>

                {{-- Discount Badge --}}
                @if($game->detail && $game->detail->discount > 0)
                <div class="inline-block bg-[#beee11] text-[#102008] font-black text-xs px-4 py-1.5 rounded-full mb-4 shadow-xl">
                    -{{ $game->detail->discount }}%
                </div>
                @endif

                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-[10px] sm:text-xs text-[#66c0f4] font-bold uppercase tracking-[0.2em]">
                    <span>Developer: 
                        @if($game->developer)
                            <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="text-white hover:text-[#66c0f4] transition">{{ $game->developer->name }}</a>
                        @else
                            -
                        @endif
                    </span>
                    <span>Publisher: 
                        @if($game->publisher)
                            <a href="{{ route('games.search', ['publisher' => $game->publisher->publisher_id]) }}" class="text-white hover:text-[#66c0f4] transition">{{ $game->publisher->name }}</a>
                        @else
                            -
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- FLOATING ACTION AREA (MOBILE ONLY) -->
    <div class="relative z-20 -mt-8 px-6 lg:hidden h-1">
        {{-- Space reserved for floating area (button removed as requested) --}}
    </div>

    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-6 py-8 md:py-12">
        <div class="grid lg:grid-cols-3 gap-6 md:gap-10">
            <!-- LEFT -->
            <div class="lg:col-span-2 space-y-8 md:space-y-12">
                @if($game->screenshots->count() > 0)
                    <div class="text-[10px] font-black text-gray-500 uppercase tracking-[0.4em] mb-4">Gallery</div>
                    <!-- MAIN SCREENSHOT VIEWER -->
                    <div class="relative rounded-xl md:rounded-2xl overflow-hidden bg-black mb-4 md:mb-6 w-full shadow-2xl" style="aspect-ratio: 16/9;">
                        <img id="main-screenshot"
                             src="{{ $game->screenshots->first()->url }}"
                             class="w-full h-full object-contain transition-all duration-300"
                             alt="{{ $game->title }} screenshot">
                    </div>

                    <!-- SCREENSHOT THUMBNAILS -->
                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-2 md:gap-3">
                        @foreach($game->screenshots as $i => $shot)
                            <button onclick="changeScreenshot('{{ $shot->url }}', this)"
                                    class="screenshot-thumb rounded-lg md:rounded-xl overflow-hidden border-2 {{ $i === 0 ? 'border-[#66c0f4]' : 'border-transparent' }} hover:border-[#66c0f4] transition focus:outline-none"
                                    title="Screenshot {{ $i + 1 }}">
                                <img src="{{ $shot->url }}"
                                     class="w-full aspect-video object-cover"
                                     alt="Screenshot thumbnail {{ $i + 1 }}">
                            </button>
                        @endforeach
                    </div>
                @else
                    <!-- MAIN IMAGE (FALLBACK) -->
                    <img
                        src="{{ $game->thumbnail_url }}"
                        class="w-full h-[200px] sm:h-[300px] md:h-[400px] object-cover rounded-xl md:rounded-2xl mb-4"
                    >
                @endif

                @if($game->trailers->count() > 0)
                    <div class="glass-panel p-4 sm:p-8 rounded-2xl">
                        <div class="mb-3 md:mb-6 flex items-center justify-between">
                            <div class="min-w-0">
                                <p class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">
                                    Media
                                </p>
                                <h2 class="mt-2 text-2xl font-black uppercase tracking-wider text-white">
                                    Game Trailer
                                </h2>
                            </div>

                            <span class="shrink-0 rounded bg-[#0f1923] px-2 py-1 text-[10px] font-bold uppercase tracking-widest text-gray-400">
                                {{ $game->trailers->count() }} trailer
                            </span>
                        </div>

                        <div class="grid gap-4 {{ $game->trailers->count() > 1 ? 'md:grid-cols-2' : '' }}">
                            @foreach($game->trailers->take(2) as $trailer) {{-- Hanya ambil 2 agar compact --}}
                                <article class="overflow-hidden rounded-xl border border-[#2a475e] bg-[#0f1923]">
                                    @if($trailer->embed_url)
                                        <div class="aspect-video bg-black">
                                            <iframe
                                                src="{{ $trailer->embed_url }}"
                                                title="{{ $trailer->title ?: $game->title . ' Trailer' }}"
                                                class="h-full w-full"
                                                loading="lazy"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                referrerpolicy="strict-origin-when-cross-origin"
                                                allowfullscreen
                                            ></iframe>
                                        </div>
                                    @else
                                        <a
                                            href="{{ $trailer->url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="flex aspect-video items-center justify-center bg-[#0a1018] p-6 text-center text-sm font-black uppercase tracking-widest text-[#66c0f4] transition hover:bg-[#111b28] hover:text-white"
                                        >
                                            Buka Trailer
                                        </a>
                                    @endif

                                    <div class="border-t border-[#2a475e] p-3">
                                        <h3 class="line-clamp-1 font-bold text-white">
                                            {{ $trailer->title ?: $game->title . ' Trailer' }}
                                        </h3>
                                        <a
                                            href="{{ $trailer->url }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-2 inline-flex text-sm font-semibold text-[#66c0f4] transition hover:text-white"
                                        >
                                            Buka di tab baru
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- DESCRIPTION -->
                <div class="glass-panel p-8 sm:p-16 rounded-[2.5rem] md:rounded-[3rem]">

                    <h2 class="text-2xl md:text-4xl font-black uppercase tracking-tight mb-8 text-white border-b border-white/10 pb-4">About This Game</h2>

                    @if($game->detail && $game->detail->short_description)
                        <p class="text-white font-bold text-xl md:text-3xl mb-8 md:mb-10 leading-tight">
                            {{ $game->detail->short_description }}
                        </p>
                    @endif

                    <p class="text-gray-300 leading-relaxed text-lg md:text-xl font-medium">
                        {{ $game->description }}
                    </p>

                    @if($game->detail && $game->detail->minimum_requirements)
                        @php
                            $requirementsText = trim(html_entity_decode(strip_tags($game->detail->minimum_requirements)));
                            $requirementsText = preg_replace("/\r\n?/", "\n", $requirementsText);
                            $requirementsText = preg_replace('/\s+(OS|Processor|Memory|Graphics|DirectX|Storage|Network|Sound Card|Additional Notes|Requires a 64-bit processor and operating system):/i', "\n$1:", $requirementsText);
                            $requirementsText = preg_replace('/(OS|Processor|Memory|Graphics|DirectX|Storage|Network|Sound Card|Additional Notes|Requires a 64-bit processor and operating system):/i', "\n$1:", $requirementsText);
                            $requirementRows = collect(preg_split("/\n+/", $requirementsText))
                                ->map(fn ($line) => trim($line, " \t\n\r\0\x0B-•"))
                                ->filter()
                                ->map(function ($line) {
                                    if (preg_match('/^([^:]{2,64}):\s*(.+)$/', $line, $matches)) {
                                        return [
                                            'label' => trim($matches[1]),
                                            'value' => trim($matches[2]),
                                        ];
                                    }

                                    return [
                                        'label' => null,
                                        'value' => $line,
                                    ];
                                })
                                ->values();
                        @endphp

                        <div class="mt-10 md:mt-16 pt-8 border-t border-[#2a475e]/50">
                            <h3 class="text-2xl font-black text-white mb-6 flex items-center gap-3 uppercase tracking-tighter">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#66c0f4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                System Requirements
                            </h3>

                            <div class="rounded-2xl border border-[#2a475e]/60 bg-gradient-to-br from-[#0f1923] to-[#07111d] p-5 md:p-8 shadow-2xl">
                                <div class="mb-5 flex items-center justify-between gap-3 border-b border-[#2a475e]/70 pb-4">
                                    <p class="text-[#66c0f4] text-xs font-black uppercase tracking-widest">Minimum Requirements</p>
                                    <span class="rounded-lg bg-[#16202d] px-3 py-1 text-xs font-bold text-gray-400">PC Specs</span>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                                    @foreach($requirementRows as $requirement)
                                        <div class="group relative overflow-hidden rounded-xl border border-[#2a475e]/40 bg-[#16202d]/60 p-4 transition-all hover:border-[#66c0f4]/40 hover:bg-[#1b2838] {{ empty($requirement['label']) ? 'md:col-span-2' : '' }}">
                                            <div class="absolute inset-y-0 left-0 w-1 bg-[#66c0f4]/20 group-hover:bg-[#66c0f4] transition-colors"></div>
                                            @if($requirement['label'])
                                                <p class="text-[10px] font-black uppercase tracking-[0.15em] text-[#66c0f4]/80 mb-1">
                                                    {{ $requirement['label'] }}
                                                </p>
                                            @endif
                                            <p class="text-sm font-semibold leading-relaxed text-gray-200">
                                                {{ $requirement['value'] }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                @if($requirementRows->isEmpty())
                                    <div class="rounded-xl border border-[#2a475e]/60 bg-[#07111d] p-4 text-sm font-semibold text-gray-400">
                                        Minimum requirements belum tersedia.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div>
                <div class="glass-panel rounded-2xl p-6 lg:sticky lg:top-24 hidden lg:block">
                    <!-- GAME IMAGE -->
                    <img
                        src="{{ $game->thumbnail_url }}"
                        class="rounded-lg md:rounded-xl mb-4 md:mb-6 w-full hidden lg:block"
                    >

                    <!-- STATS
                    -->

                    <!-- RELEASE -->
                    <div class="mb-4">
                        <div class="text-gray-400 text-sm mb-1">
                            Release Date
                        </div>
                        <div class="text-sm font-bold">
                            {{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->translatedFormat('d F Y') : '-' }}
                        </div>
                    </div>

                    <!-- DEVELOPER -->
                    <div class="mb-4">
                        <div class="text-gray-400 text-sm mb-1">
                            Developer
                        </div>
                        @if($game->developer)
                            <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" 
                               class="text-[#66c0f4] font-medium hover:underline block">
                                {{ $game->developer->name }}
                            </a>
                        @else
                            <div class="text-white">-</div>
                        @endif
                    </div>

                    <!-- PUBLISHER -->
                    <div class="mb-4">
                        <div class="text-gray-400 text-sm mb-1">
                            Publisher
                        </div>
                        @if($game->publisher)
                            <a href="{{ route('games.search', ['publisher' => $game->publisher->publisher_id]) }}" 
                               class="text-[#66c0f4] font-medium hover:underline block">
                                {{ $game->publisher->name }}
                            </a>
                        @else
                            <div class="text-white">-</div>
                        @endif
                    </div>

                    <!-- GENRES -->
                    <div class="mb-4">
                        <div class="text-gray-400 text-sm mb-2">
                            Genres
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($game->genres as $genre)
                                <span class="bg-[#2a475e]
                                             px-3 py-1
                                             rounded-lg
                                             text-sm">
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- CATEGORIES -->
                    @if($game->categories->isNotEmpty())
                    <div class="mb-4">

                        <div class="text-gray-400 text-sm mb-2">

                            Categories

                        </div>

                        <div class="flex flex-wrap gap-2">

                            @foreach($game->categories as $category)

                                <span class="bg-[#2a475e]
                                             px-3 py-1
                                             rounded-lg
                                             text-sm">

                                    {{ $category->name }}

                                </span>

                            @endforeach

                        </div>

                    </div>
                    @endif

                    <!-- PLATFORMS -->
                    @if($game->platforms->count() > 0)
                    <div class="mb-6">

                        <div class="text-gray-400 text-sm mb-2">Available On</div>

                        <div class="flex items-center gap-3">

                            @foreach($game->platforms as $platform)

                                <div class="flex items-center gap-1.5
                                            bg-[#2a475e]
                                            px-3 py-2
                                            rounded-lg
                                            text-white
                                            text-sm
                                            hover:bg-[#3a5a74]
                                            transition"
                                     title="{{ $platform->name }}">

                                    @if($platform->icon)
                                        {!! $platform->icon !!}
                                    @endif

                                    <span>{{ $platform->name }}</span>

                                </div>

                            @endforeach

                        </div>

                    </div>
                    @endif


                    <!-- PRICE + DISCOUNT -->
                    @php
                        $discount = $game->detail->discount ?? 0;
                        $originalPrice = $game->price;
                        $finalPrice = $discount > 0
                            ? $originalPrice * (1 - $discount / 100)
                            : $originalPrice;
                    @endphp

                    <div class="bg-[#0f1923] p-5 rounded-xl">
                        @if($discount > 0)
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-[#4c6b22] text-[#beee11] font-black text-lg px-3 py-1 rounded">
                                    -{{ $discount }}%
                                </span>
                                <span class="text-gray-400 line-through text-base">
                                    Rp {{ number_format($originalPrice, 0, ',', '.') }}
                                </span>
                            </div>
                        @endif
                        <div class="text-3xl font-bold text-[#66c0f4]">
                            {{ $originalPrice == 0 ? 'Gratis' : 'Rp ' . number_format($finalPrice, 0, ',', '.') }}
                        </div>
                    </div>

                    {{-- Website --}}
                    @if($game->detail && $game->detail->website)
                    <a href="{{ $game->detail->website }}" target="_blank"
                       class="flex items-center gap-2 text-[#66c0f4] text-sm hover:underline mt-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Official Website
                    </a>
                    @endif

                    <!-- DESKTOP BUTTONS -->
                    @if($isPurchased)
                        <div class="w-full mt-5 py-4 rounded-xl text-lg font-bold bg-green-600/20 border border-green-600 text-green-400 text-center">
                            ✓ Already Owned
                        </div>
                    @elseif($isInCart)
                        <a href="{{ route('cart.index') }}" class="block w-full mt-5 py-4 rounded-xl text-lg font-bold bg-[#0f1923] border border-[#66c0f4] text-[#66c0f4] text-center hover:bg-[#16202d] transition">
                            Already in Cart
                        </a>
                    @else
                        <form action="{{ route('cart.add', $game->game_id) }}" method="POST">
                            @csrf
                            <button class="steam-blue w-full mt-5 py-4 rounded-xl text-lg font-bold hover:opacity-90 transition">
                                Add to Cart
                            </button>
                        </form>
                    @endif

                    @auth
                        <form action="{{ route('wishlist.toggle', $game) }}" method="POST">
                            @csrf
                            <button class="wishlist-button {{ $isWishlisted ? 'is-active' : '' }} w-full mt-3 py-4 rounded-xl text-lg font-bold border border-[#2a475e] bg-[#0f1923] text-gray-200 hover:border-[#66c0f4] hover:text-[#66c0f4] transition flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $isWishlisted ? 'fill-[#66c0f4]' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z"/>
                                </svg>
                                {{ $isWishlisted ? 'In Wishlist' : 'Add to Wishlist' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="w-full mt-3 py-4 rounded-xl text-lg font-bold border border-[#2a475e] bg-[#0f1923] text-gray-200 hover:border-[#66c0f4] hover:text-[#66c0f4] transition flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z"/>
                            </svg>
                            Add to Wishlist
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- STICKY MOBILE BUY BAR -->
        <div class="fixed bottom-[88px] left-0 right-0 z-[100] lg:hidden">
            <div class="mx-4 mb-4">
                <div class="sticky-buy-bar bg-[#0d1c2e]/95 backdrop-blur-2xl flex items-center justify-between gap-4 rounded-3xl p-5 shadow-[0_-15px_40px_rgba(0,0,0,0.7)] border border-white/10">
                    <div class="flex flex-col">
                        @if($discount > 0)
                            <span class="text-[11px] text-gray-500 line-through font-bold">Rp {{ number_format($originalPrice, 0, ',', '.') }}</span>
                        @endif
                        <span class="text-xl font-black text-[#66c0f4]">
                            {{ $originalPrice == 0 ? 'FREE' : 'Rp' . number_format($finalPrice, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex gap-2">
                        @auth
                            <form action="{{ route('wishlist.toggle', $game) }}" method="POST">
                                @csrf
                                <button class="flex h-12 w-12 items-center justify-center rounded-xl bg-[#0f1923] border border-[#2a475e] transition active:scale-90">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 {{ $isWishlisted ? 'fill-[#66c0f4] text-[#66c0f4]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z"/>
                                    </svg>
                                </button>
                            </form>
                        @endauth

                        @if($isPurchased)
                            <div class="px-6 h-12 flex items-center justify-center rounded-xl bg-green-600 font-black text-xs uppercase tracking-widest text-white shadow-lg">Owned</div>
                        @elseif($isInCart)
                            <a href="{{ route('cart.index') }}" class="px-6 h-12 flex items-center justify-center rounded-xl bg-[#66c0f4] font-black text-xs uppercase tracking-widest text-[#07111d] shadow-lg">In Cart</a>
                        @else
                            <form action="{{ route('cart.add', $game->game_id) }}" method="POST">
                                @csrf
                                <button class="steam-blue px-6 h-12 flex items-center justify-center rounded-xl font-black text-xs uppercase tracking-widest text-white shadow-lg active:scale-95 transition">Add to Cart</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <section
            class="mt-6 md:mt-10 bg-[#16202d] p-4 sm:p-8 rounded-2xl border border-[#2a475e]"
            data-review-root
            data-reviews-url="{{ route('games.reviews.index', $game) }}"
            data-is-authenticated="{{ auth()->check() ? '1' : '0' }}"
            @auth
                data-login-url=""
            @else
                data-login-url="{{ route('login') }}"
            @endauth
        >
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between border-b border-[#2a475e]/40 pb-8">
                <div class="text-left">
                    <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter text-white">Community Reviews</h2>
                    <p class="mt-2 text-sm font-medium text-gray-400">
                        Pendapat komunitas tentang game ini.
                    </p>
                </div>

                <div class="relative overflow-hidden flex flex-col items-center justify-center rounded-2xl border border-[#2a475e] bg-gradient-to-b from-[#1b2838] to-[#0f1923] p-6 shadow-2xl min-w-[260px] text-center">
                    <p class="relative z-10 text-3xl font-black text-[#66c0f4] mb-1" data-review-label>No Reviews</p>
                    <p class="text-[10px] uppercase font-black tracking-[0.2em] text-gray-500" data-review-percent>0% Positive</p>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-[#2a475e] bg-[#0f1923] p-4 sm:p-5" data-review-form-shell>
                @auth
                    <form data-review-form class="space-y-4">
                        <div class="flex flex-wrap gap-3">
                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-green-500/50 bg-green-600/15 px-4 py-3 font-bold text-green-200 transition has-[:checked]:bg-green-600 has-[:checked]:text-white">
                                <input type="radio" name="is_recommended" value="1" checked class="sr-only">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11v9M7 11H4.5A1.5 1.5 0 0 0 3 12.5v6A1.5 1.5 0 0 0 4.5 20H7m0-9 4.2-7.2A2 2 0 0 1 15 5v4h4.2a2 2 0 0 1 1.95 2.45l-1.35 6A2 2 0 0 1 17.85 19H7" />
                                </svg>
                                Like
                            </label>
                            <label class="inline-flex cursor-pointer items-center gap-2 rounded-xl border border-red-500/50 bg-red-600/15 px-4 py-3 font-bold text-red-200 transition has-[:checked]:bg-red-600 has-[:checked]:text-white">
                                <input type="radio" name="is_recommended" value="0" class="sr-only">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 13V4M7 13H4.5A1.5 1.5 0 0 1 3 11.5v-6A1.5 1.5 0 0 1 4.5 4H7m0 9 4.2 7.2A2 2 0 0 0 15 19v-4h4.2a2 2 0 0 0 1.95-2.45l-1.35-6A2 2 0 0 0 17.85 5H7" />
                                </svg>
                                Dislike
                            </label>
                        </div>

                        <textarea
                            name="body"
                            rows="4"
                            maxlength="2000"
                            placeholder="Tulis review kamu..."
                            class="w-full resize-y rounded-xl border border-[#316282] bg-[#07111d] px-4 py-3 text-white outline-none focus:border-[#66c0f4]"
                            data-review-body
                        ></textarea>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-gray-400" data-review-form-message>
                                Kamu bisa menulis lebih dari satu review untuk game ini.
                            </p>
                            <button type="submit" class="steam-blue rounded-xl px-6 py-3 font-black text-white transition hover:opacity-90">
                                Submit Review
                            </button>
                        </div>
                    </form>
                @else
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <p class="text-gray-300">Login dan beli game ini untuk menulis review.</p>
                        <a href="{{ route('login') }}" class="rounded-xl border border-[#66c0f4] px-5 py-3 font-bold text-[#66c0f4] transition hover:bg-[#66c0f4] hover:text-[#07111d]">
                            Login
                        </a>
                    </div>
                @endauth
            </div>

            <div class="mt-6 space-y-4" data-review-list>
                <div class="rounded-xl border border-[#2a475e] bg-[#0f1923] p-4 text-gray-400 text-sm">
                    Loading reviews...
                </div>
            </div>
        </section>

        @if(($relatedGames ?? collect())->isNotEmpty())
            <section class="mt-6 md:mt-10 bg-[#16202d] p-5 sm:p-8 rounded-2xl border border-[#2a475e]">
                <div class="mb-4 md:mb-6 flex items-end justify-between">
                    <div class="min-w-0">
                        <h2 class="text-3xl font-bold">Game Lainnya</h2>
                        <p class="mt-2 text-gray-400">
                            Rekomendasi game lain yang mungkin kamu suka.
                        </p>
                    </div>

                    <a href="{{ route('games.search') }}" class="text-sm font-black uppercase tracking-widest text-[#66c0f4] transition hover:text-white">
                        Lihat Semua
                    </a>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach($relatedGames as $relatedGame)
                        @php
                            $relatedDiscount = $relatedGame->discount_percent;
                            $relatedFinalPrice = $relatedGame->final_price;
                        @endphp

                        <a
                            href="{{ url('/game/' . $relatedGame->game_id) }}"
                            class="related-game-card group overflow-hidden rounded-2xl border border-[#2a475e] bg-[#0f1923] transition hover:-translate-y-1 hover:border-[#66c0f4] hover:bg-[#1f2f42]"
                        >
                            <div class="aspect-video overflow-hidden bg-black">
                                <img
                                    src="{{ $relatedGame->thumbnail_url ?: 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=70&w=800&auto=format&fit=crop' }}"
                                    alt="{{ $relatedGame->title }}"
                                    class="h-full w-full object-cover"
                                    loading="lazy"
                                >
                            </div>

                            <div class="p-4 md:p-5">
                                <h3 class="line-clamp-2 min-h-[3rem] text-sm md:text-lg font-black leading-tight text-white group-hover:text-[#66c0f4]">
                                    {{ $relatedGame->title }}
                                </h3>

                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @foreach($relatedGame->genres->take(2) as $genre)
                                        <span class="rounded bg-[#2a475e] px-2 py-0.5 text-[10px] md:text-xs font-bold text-gray-400">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <div class="mt-4 flex items-center justify-between gap-3">
                                    @if($relatedDiscount > 0)
                                        <span class="rounded bg-[#4c6b22] px-2 py-1 text-xs font-black text-[#beee11]">
                                            -{{ $relatedDiscount }}%
                                        </span>
                                    @endif

                                    <span class="ml-auto text-lg font-black text-[#66c0f4]">
                                        {{ $relatedFinalPrice <= 0 ? 'Gratis' : 'Rp ' . number_format($relatedFinalPrice, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

    </section>

    <script>
        function changeScreenshot(url, btn) {
            document.getElementById('main-screenshot').src = url;

            // Update border highlight on thumbnails
            if (btn) {
                document.querySelectorAll('.screenshot-thumb').forEach(el => {
                    el.classList.remove('border-[#66c0f4]');
                    el.classList.add('border-transparent');
                });
                btn.classList.remove('border-transparent');
                btn.classList.add('border-[#66c0f4]');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-review-root]');

            if (!root) {
                return;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const reviewsUrl = root.dataset.reviewsUrl;
            const isAuthenticated = root.dataset.isAuthenticated === '1';
            const reviewList = root.querySelector('[data-review-list]');
            const reviewForm = root.querySelector('[data-review-form]');
            const reviewBody = root.querySelector('[data-review-body]');
            const formMessage = root.querySelector('[data-review-form-message]');
            const formShell = root.querySelector('[data-review-form-shell]');
            let refreshTimer = null;

            const escapeText = (value) => {
                const div = document.createElement('div');
                div.textContent = value || '';
                return div.innerHTML;
            };

            const renderStats = (stats) => {
                document.querySelectorAll('[data-review-label]').forEach((target) => {
                    target.textContent = stats.label;
                });

                document.querySelectorAll('[data-review-percent]').forEach((target) => {
                    target.textContent = `${stats.percentage}% dari ${stats.total} review menyukai game ini`;
                });
            };

            const renderReviewForm = (payload) => {
                if (!isAuthenticated || !formShell) {
                    return;
                }

                if (!payload.can_review) {
                    formShell.innerHTML = '<p class="text-gray-300">Kamu harus membeli game ini sebelum memberi review.</p>';
                    return;
                }

                if (formMessage) {
                    formMessage.textContent = 'Kamu bisa menulis lebih dari satu review untuk game ini.';
                }
            };

            const sentimentIcon = (isLiked) => isLiked
                ? `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11v9M7 11H4.5A1.5 1.5 0 0 0 3 12.5v6A1.5 1.5 0 0 0 4.5 20H7m0-9 4.2-7.2A2 2 0 0 1 15 5v4h4.2a2 2 0 0 1 1.95 2.45l-1.35 6A2 2 0 0 1 17.85 19H7" /></svg>`
                : `<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 13V4M7 13H4.5A1.5 1.5 0 0 1 3 11.5v-6A1.5 1.5 0 0 1 4.5 4H7m0 9 4.2 7.2A2 2 0 0 0 15 19v-4h4.2a2 2 0 0 0 1.95-2.45l-1.35-6A2 2 0 0 0 17.85 5H7" /></svg>`;

            const renderReviews = (reviews) => {
                if (!reviewList) {
                    return;
                }

                if (reviews.length === 0) {
                    reviewList.innerHTML = '<div class="rounded-xl border border-[#2a475e] bg-[#0f1923] p-5 text-gray-400">Belum ada review. Jadilah reviewer pertama.</div>';
                    return;
                }

                reviewList.innerHTML = reviews.map((review) => `
                    <article class="rounded-2xl border border-[#2a475e] bg-[#0f1923] p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="flex gap-4">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] font-black text-white">
                                    ${escapeText(review.initial)}
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="font-black text-white">${escapeText(review.user_name)}</h3>
                                        <span class="inline-flex items-center gap-1.5 rounded px-2 py-1 text-xs font-black ${review.is_recommended ? 'bg-green-600/20 text-green-300' : 'bg-red-600/20 text-red-300'}">
                                            ${sentimentIcon(review.is_recommended)}
                                            ${review.is_recommended ? 'Like' : 'Dislike'}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">${escapeText(review.updated_at || review.created_at)}</p>
                                </div>
                            </div>
                            ${review.is_owner ? `<button type="button" class="rounded-lg border border-red-500/40 px-3 py-2 text-xs font-bold text-red-200 transition hover:bg-red-600 hover:text-white" data-review-delete="${review.id}">Delete</button>` : ''}
                        </div>
                        <p class="mt-4 whitespace-pre-line leading-relaxed text-gray-300">${escapeText(review.body)}</p>
                    </article>
                `).join('');
            };

            const loadReviews = async () => {
                const response = await fetch(reviewsUrl, {
                    headers: { Accept: 'application/json' },
                });

                if (!response.ok) {
                    throw new Error('Failed to load reviews.');
                }

                const payload = await response.json();
                renderStats(payload.stats);
                renderReviewForm(payload);
                renderReviews(payload.reviews || []);
            };

            reviewForm?.addEventListener('submit', async (event) => {
                event.preventDefault();

                const formData = new FormData(reviewForm);

                if (formMessage) {
                    formMessage.textContent = 'Saving review...';
                }

                const response = await fetch(reviewsUrl, {
                    method: 'POST',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: formData,
                });

                if (!response.ok) {
                    const error = await response.json().catch(() => ({}));
                    if (formMessage) {
                        formMessage.textContent = error.message || 'Review gagal disimpan.';
                    }
                    return;
                }

                const payload = await response.json();
                renderStats(payload.stats);
                renderReviewForm(payload);
                renderReviews(payload.reviews || []);
                reviewForm.reset();
                reviewForm.querySelector('input[name="is_recommended"][value="1"]')?.click();
                if (formMessage) {
                    formMessage.textContent = 'Review baru tersimpan. Kamu bisa menulis review lagi.';
                }
            });

            reviewList?.addEventListener('click', async (event) => {
                const button = event.target.closest('[data-review-delete]');

                if (!button) {
                    return;
                }

                const response = await fetch(`${reviewsUrl}/${button.dataset.reviewDelete}`, {
                    method: 'DELETE',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (response.ok) {
                    const payload = await response.json();
                    renderStats(payload.stats);
                    renderReviewForm(payload);
                    renderReviews(payload.reviews || []);
                }
            });

            loadReviews().catch(() => {
                if (reviewList) {
                    reviewList.innerHTML = '<div class="rounded-xl border border-red-500/40 bg-red-950/30 p-5 text-red-200">Review gagal dimuat.</div>';
                }
            });

            refreshTimer = window.setInterval(() => {
                loadReviews().catch(() => {});
            }, 5000);

            window.addEventListener('beforeunload', () => window.clearInterval(refreshTimer));
        });
    </script>
    <x-store-footer />
</body>
</html>
