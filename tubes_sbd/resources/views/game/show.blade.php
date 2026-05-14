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

    <!-- TOP HERO -->
    <section class="relative h-[500px]">

        <img
            src="{{ $game->thumbnail_url }}"
            class="absolute inset-0 w-full h-full object-cover"
        >

        <div class="absolute inset-0 bg-black/70"></div>

        <div class="relative z-10 max-w-7xl mx-auto px-6 h-full flex items-end pb-16">

            <div>

                <h1 class="text-6xl font-bold mb-4">

                    {{ $game->title }}

                </h1>

                <div class="flex gap-4 text-sm text-[#66c0f4]">

                    <span>
                        Developer:
                        {{ $game->developer->name ?? '-' }}
                    </span>

                    <span>
                        Publisher:
                        {{ $game->publisher->name ?? '-' }}
                    </span>

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

                    <h2 class="text-3xl font-bold mb-6">

                        About This Game

                    </h2>

                    <p class="text-gray-300 leading-relaxed text-lg">

                        {{ $game->description }}

                    </p>

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

                    <!-- PRICE -->
                    <div class="bg-[#0f1923]
                                p-5
                                rounded-xl
                                flex justify-between items-center">

                        <div class="text-3xl font-bold text-[#66c0f4]">

                            {{ $game->price == 0 ? 'Gratis' : 'Rp ' . number_format($game->price, 0, ',', '.') }}

                        </div>

                    </div>

                    <!-- BUTTON -->
                    <button class="steam-blue
                                   w-full
                                   mt-5
                                   py-4
                                   rounded-xl
                                   text-lg
                                   font-bold
                                   hover:opacity-90
                                   transition">

                        Add to Cart

                    </button>

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