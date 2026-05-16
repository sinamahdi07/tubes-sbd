<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>{{ $game->title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #1b2838;
        }

        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }
    </style>
</head>

<body class="text-white min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-[#171a21] border-b border-[#2a475e] sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <div class="flex items-center gap-10">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full steam-blue flex items-center justify-center font-bold text-xl">
                        G
                    </div>
                    <h1 class="text-2xl font-bold tracking-wide text-[#66c0f4]">
                        PlayMart
                    </h1>
                </a>

                <div class="hidden md:flex gap-8 text-sm uppercase tracking-wider font-semibold text-gray-300">
                    <a href="{{ route('home') }}" class="hover:text-white">Store</a>
                    <a href="#" class="hover:text-white">About</a>
                    <a href="#" class="hover:text-white">Support</a>
                    <a href="{{ route('cart.index') }}" class="hover:text-white">Cart</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <x-store-user-menu />
            </div>
        </div>
    </nav>

    <!-- TOP HERO -->
    <section class="relative h-[500px]">

        <img
            src="{{ $game->thumbnail_url }}"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex items-end pb-16">

            <div>

                {{-- Discount Badge --}}
                @if($game->detail && $game->detail->discount > 0)
                <div class="inline-block bg-[#4c6b22] text-[#beee11] font-bold text-sm px-3 py-1 rounded mb-3">
                    -{{ $game->detail->discount }}%
                </div>
                @endif

                <h1 class="text-6xl font-bold mb-4">
                    {{ $game->title }}
                </h1>

                {{-- Short description --}}
                @if($game->detail && $game->detail->short_description)
                <p class="text-gray-300 text-base max-w-2xl mb-4">
                    {{ $game->detail->short_description }}
                </p>
                @endif

                <div class="flex flex-wrap gap-4 text-sm text-[#66c0f4]">
                    <span>Developer: {{ $game->developer->name ?? '-' }}</span>
                    <span>Publisher: {{ $game->publisher->name ?? '-' }}</span>
                </div>

            </div>

        </div>

    </section>

    <!-- CONTENT -->
    <section class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid lg:grid-cols-3 gap-10">

            <!-- LEFT -->
            <div class="lg:col-span-2">

                @if($game->screenshots->count() > 0)
                    <!-- MAIN SCREENSHOT VIEWER -->
                    <div class="relative rounded-2xl overflow-hidden bg-black mb-6 w-full" style="aspect-ratio: 16/9;">
                        <img id="main-screenshot"
                             src="{{ $game->screenshots->first()->url }}"
                             class="w-full h-full object-contain transition-all duration-300"
                             alt="{{ $game->title }} screenshot">
                    </div>

                    <!-- SCREENSHOT THUMBNAILS -->
                    <div class="grid grid-cols-4 sm:grid-cols-5 md:grid-cols-6 gap-3 mb-10">
                        @foreach($game->screenshots as $i => $shot)
                            <button onclick="changeScreenshot('{{ $shot->url }}', this)"
                                    class="screenshot-thumb rounded-xl overflow-hidden border-2 {{ $i === 0 ? 'border-[#66c0f4]' : 'border-transparent' }} hover:border-[#66c0f4] transition focus:outline-none"
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
                        class="w-full h-[500px] object-cover rounded-2xl mb-6"
                    >
                @endif

                <!-- DESCRIPTION -->
                <div class="bg-[#16202d] p-8 rounded-2xl border border-[#2a475e]">

                    <h2 class="text-3xl font-bold mb-6">About This Game</h2>

                    @if($game->detail && $game->detail->short_description)
                        <p class="text-[#66c0f4] font-semibold text-base mb-4 leading-relaxed">
                            {{ $game->detail->short_description }}
                        </p>
                    @endif

                    <p class="text-gray-300 leading-relaxed text-lg">
                        {{ $game->description }}
                    </p>

                    @if($game->detail && $game->detail->minimum_requirements)
                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-white mb-3">System Requirements</h3>
                        <div class="bg-[#0f1923] rounded-xl p-5 text-gray-400 text-sm leading-relaxed">
                            {{ $game->detail->minimum_requirements }}
                        </div>
                    </div>
                    @endif

                </div>

            </div>

            <!-- RIGHT SIDEBAR -->
            <div>

                <div class="bg-[#16202d]
                            rounded-2xl
                            p-6
                            border border-[#2a475e]
                            sticky top-5">

                    <!-- GAME IMAGE -->
                    <img
                        src="{{ $game->thumbnail_url }}"
                        class="rounded-xl mb-6"
                    >

                    <!-- REVIEW -->
                    <div class="mb-5">

                        <div class="text-gray-400 text-sm mb-1">

                            Reviews

                        </div>

                        <div class="text-[#66c0f4] font-bold text-lg">

                            {{ $game->reviews ?? 'Very Positive' }}

                        </div>

                    </div>

                    <!-- RELEASE -->
                    <div class="mb-5">

                        <div class="text-gray-400 text-sm mb-1">

                            Release Date

                        </div>

                        <div>

                            {{ $game->release_date }}

                        </div>

                    </div>

                    <!-- GENRES -->
                    <div class="mb-6">

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

                    <!-- BUTTON -->
                    @php
                        $isPurchased = false;
                        if(auth()->check()) {
                            $isPurchased = \App\Models\Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
                                ->where('payments.user_id', auth()->id())
                                ->where('payment_items.game_id', $game->game_id)
                                ->where('payments.status', 'completed')
                                ->exists();
                        }
                    @endphp

                    @if($isPurchased)
                        <div class="w-full mt-5 py-4 rounded-xl text-lg font-bold bg-green-600/20 border border-green-600 text-green-400 text-center">
                            ✓ Already Owned
                        </div>
                    @else
                        <form action="{{ route('cart.add', $game->game_id) }}" method="POST">
                            @csrf
                            <button class="steam-blue w-full mt-5 py-4 rounded-xl text-lg font-bold hover:opacity-90 transition">
                                Add to Cart
                            </button>
                        </form>
                    @endif

                </div>

            </div>

        </div>

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
    </script>
</body>
</html>
