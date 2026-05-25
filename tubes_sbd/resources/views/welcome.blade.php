@php
    use Illuminate\Support\Str;

    $fallbackImage = 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?q=70&w=1400&auto=format&fit=crop';
    $imageFor = fn ($game) => $game?->thumbnail_url ?: $fallbackImage;
    $discountFor = fn ($game) => (int) ($game?->detail?->discount ?? 0);
    $finalPriceFor = fn ($game) => max(0, (float) ($game?->price ?? 0) * (1 - ($discountFor($game) / 100)));
    $formatPrice = fn ($price) => (float) $price <= 0 ? 'Gratis' : 'Rp ' . number_format($price, 0, ',', '.');
    $descriptionFor = fn ($game, $limit = 130) => Str::limit($game?->detail?->short_description ?: $game?->description ?: 'Temukan dunia baru, tantangan seru, dan pengalaman bermain pilihan di PlayMart.', $limit);
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
    $leadRecommended = $recommendedGames->first();
    $sideRecommended = $recommendedGames->skip(1)->take(4);
@endphp

@extends('layouts.store')

@push('styles')
    <style>
        .store-panel {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.88), rgba(7, 17, 29, 0.9)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.12), rgba(45, 115, 255, 0.05));
            border: 1px solid rgba(42, 71, 94, 0.9);
            box-shadow: 0 18px 44px rgba(0, 0, 0, 0.3);
        }
        .hero-panel {
            min-height: clamp(380px, 30vw, 480px);
            background: #07111d;
        }
        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: inherit;
            box-shadow: inset 0 0 0 1px rgba(102, 192, 244, 0.08);
        }
        .hero-copy {
            max-width: 520px;
            text-shadow: 0 2px 16px rgba(0, 0, 0, 0.7);
        }
        .game-card {
            background: #0f1923;
            border: 1px solid rgba(42, 71, 94, 0.95);
            transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .game-card:hover {
            transform: translateY(-3px);
            border-color: rgba(102, 192, 244, 0.78);
            box-shadow: 0 0 20px rgba(17, 141, 255, 0.2);
        }
        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(42, 71, 94, 0.95);
            background: rgba(15, 25, 35, 0.82);
            color: #d7e3f2;
            transition: border-color .2s ease, color .2s ease, background .2s ease;
        }
        .icon-button:hover {
            border-color: rgba(102, 192, 244, 0.78);
            color: #fff;
            background: rgba(22, 32, 45, 0.96);
        }
        .price-discount {
            background: #4c6b22;
            color: #beee11;
        }
        .hero-dot.is-active {
            width: 28px;
            background: #118dff;
        }
        [data-hero-carousel] {
            touch-action: pan-y;
        }
        [data-hero-carousel] [data-hero-slide] {
            cursor: grab;
            user-select: none;
        }
        [data-hero-carousel] img {
            -webkit-user-drag: none;
            user-select: none;
        }
        [data-hero-carousel].is-dragging [data-hero-slide] {
            cursor: grabbing;
        }
        .store-scrollbar {
            scrollbar-color: #2a475e #07111d;
        }
        @media (max-width: 768px) {
            .hero-panel { min-height: 560px; }
        }
    </style>
@endpush

@section('content')
    <main class="pb-12">
        @if($featuredGames->isNotEmpty())
                <section class="store-container relative pt-5" data-hero-carousel>
                @foreach($featuredGames as $slideIndex => $heroGame)
                    @php
                        $featuredDiscount = $discountFor($heroGame);
                        $featuredFinal = $finalPriceFor($heroGame);
                    @endphp

                    <article class="hero-panel store-panel relative overflow-hidden rounded-lg {{ $slideIndex === 0 ? '' : 'hidden' }}" data-hero-slide>
                        <img
                            src="{{ $imageFor($heroGame) }}"
                            alt="{{ $heroGame->title }}"
                            class="absolute inset-0 h-full w-full object-cover"
                            @if($slideIndex === 0)
                                fetchpriority="high"
                                loading="eager"
                            @else
                                loading="lazy"
                            @endif
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-[#050a12] via-[#07111d]/82 to-[#07111d]/10"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#050a12]/70 via-transparent to-transparent"></div>

                        <div class="relative z-10 flex min-h-[380px] items-center px-8 py-10 md:min-h-[450px] md:px-20">
                            <div class="hero-copy">
                                <span class="mb-5 inline-flex rounded-md bg-[#063b80]/90 px-4 py-2 text-xs font-black uppercase tracking-wider text-[#66c0f4] shadow-lg shadow-blue-950/30">
                                    Featured
                                </span>

                                <h1 class="text-5xl font-black uppercase leading-none text-white md:text-7xl">
                                    {{ $heroGame->title }}
                                </h1>

                                <p class="mt-4 text-base font-black uppercase tracking-[0.34em] text-[#118dff]">
                                    Survive. Explore. Conquer.
                                </p>

                                <p class="mt-4 max-w-lg text-sm leading-relaxed text-gray-300 md:text-base">
                                    {{ $descriptionFor($heroGame, 150) }}
                                </p>

                                <div class="mt-6 flex flex-wrap items-center gap-3">
                                    @if($featuredDiscount > 0)
                                        <span class="price-discount rounded px-3 py-1.5 text-lg font-black">-{{ $featuredDiscount }}%</span>
                                        <span class="text-xs font-semibold text-gray-500 line-through">{{ $formatPrice($heroGame->price) }}</span>
                                    @endif
                                    <span class="text-2xl font-black text-white">{{ $formatPrice($featuredFinal) }}</span>
                                    <a href="{{ url('/game/' . $heroGame->game_id) }}" class="steam-blue ml-0 rounded-lg px-7 py-3 text-base font-bold text-white shadow-lg shadow-blue-950/40 transition hover:brightness-110 sm:ml-4">
                                        View Game
                                    </a>
                                    <a href="{{ url('/game/' . $heroGame->game_id) }}" class="icon-button h-12 w-12 rounded-lg" aria-label="Open {{ $heroGame->title }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach

                @if($featuredGames->count() > 1)
                    <button type="button" class="icon-button absolute left-4 top-1/2 z-30 h-14 w-14 -translate-y-1/2 rounded-lg bg-[#0f1923]/90 shadow-2xl shadow-black/40 backdrop-blur md:left-6" data-hero-prev aria-label="Previous featured game">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19 8 12l7-7"/>
                        </svg>
                    </button>
                    <button type="button" class="icon-button absolute right-4 top-1/2 z-30 h-14 w-14 -translate-y-1/2 rounded-lg bg-[#0f1923]/90 shadow-2xl shadow-black/40 backdrop-blur md:right-6" data-hero-next aria-label="Next featured game">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                        </svg>
                    </button>

                    <div class="absolute bottom-4 left-1/2 z-20 flex -translate-x-1/2 gap-2">
                        @foreach($featuredGames as $slideIndex => $heroGame)
                            <button type="button" class="hero-dot h-2 w-5 rounded-full bg-white/35 transition-all {{ $slideIndex === 0 ? 'is-active' : '' }}" data-hero-dot data-slide-index="{{ $slideIndex }}" aria-label="Show featured game {{ $slideIndex + 1 }}"></button>
                        @endforeach
                    </div>
                @endif
            </section>
        @else
            <section class="store-container pt-5">
                <div class="hero-panel store-panel flex items-center rounded-lg p-8 md:p-20">
                    <div>
                        <span class="mb-5 inline-flex rounded-md bg-[#063b80]/90 px-4 py-2 text-xs font-black uppercase tracking-wider text-[#66c0f4]">Featured</span>
                        <h1 class="text-5xl font-black uppercase">PlayMart</h1>
                        <p class="mt-4 max-w-xl text-gray-300">Tambahkan data game lewat admin supaya halaman store bisa menampilkan hero dan rekomendasi.</p>
                    </div>
                </div>
            </section>
        @endif

        <section id="discover" class="store-container mt-4">
            <x-game-search
                :value="$search"
                :categories="$categories"
                :genres="$genres"
                :selected-category="$selectedCategory"
                :selected-genre="$selectedGenre"
                :contained="false"
            />
        </section>

        <section id="recommended" class="store-container mt-4 grid gap-5 xl:grid-cols-[1fr_360px]">
            <div class="min-w-0 space-y-5">
                <section>
                    <div class="mb-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center text-[#118dff]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="m12 2 2.95 6.35 6.95.85-5.12 4.77 1.33 6.88L12 17.45l-6.11 3.4 1.33-6.88L2.1 9.2l6.95-.85L12 2Z"/>
                                </svg>
                            </span>
                            <h2 class="text-xl font-black text-white md:text-2xl uppercase tracking-tight">Featured & Recommended</h2>
                        </div>
                        <a href="{{ route('games.search', request()->only('search', 'genre', 'category')) }}" class="hidden items-center gap-2 text-sm font-bold text-[#118dff] transition hover:text-white sm:inline-flex">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    @if($leadRecommended)
                        <div class="grid gap-4 lg:grid-cols-[1.15fr_1fr]">
                            @php
                                $discount = $discountFor($leadRecommended);
                                $finalPrice = $finalPriceFor($leadRecommended);
                            @endphp
                            <a href="{{ url('/game/' . $leadRecommended->game_id) }}" class="block">
                                <article class="game-card relative min-h-[280px] overflow-hidden rounded-lg md:min-h-[320px]">
                                    <img src="{{ $imageFor($leadRecommended) }}" alt="{{ $leadRecommended->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/48 to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-6">
                                        <h3 class="text-3xl font-black uppercase leading-none text-white md:text-4xl group-hover:text-[#66c0f4]">{{ $leadRecommended->title }}</h3>
                                        <p class="mt-3 max-w-md text-sm leading-relaxed text-gray-300 line-clamp-2">{{ $descriptionFor($leadRecommended, 115) }}</p>
                                        <div class="mt-4 flex flex-wrap items-center gap-3">
                                            @if($discount > 0)
                                                <span class="price-discount rounded-md px-3 py-2 text-base font-black">-{{ $discount }}%</span>
                                                <span class="text-xs font-semibold text-gray-500 line-through">{{ $formatPrice($leadRecommended->price) }}</span>
                                            @endif
                                            <span class="text-xl font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                        </div>
                                    </div>
                                </article>
                            </a>

                            <div class="grid gap-2 sm:grid-cols-2">
                                @foreach($sideRecommended as $game)
                                    @php
                                        $discount = $discountFor($game);
                                        $finalPrice = $finalPriceFor($game);
                                    @endphp
                                    <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                                        <article class="game-card relative min-h-[150px] overflow-hidden rounded-lg">
                                            <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/56 to-transparent"></div>
                                            <span class="icon-button absolute right-3 top-3 h-8 w-8 rounded-md" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.49-2.02-4.5-4.5-4.5-1.72 0-3.22.96-3.98 2.38A4.49 4.49 0 0 0 8.5 3.75C6.02 3.75 4 5.76 4 8.25c0 7.22 8 11.5 8 11.5s9-4.28 9-11.5Z"/>
                                                </svg>
                                            </span>
                                            <div class="absolute inset-x-0 bottom-0 p-4">
                                                <h3 class="truncate text-lg font-black uppercase text-white">{{ $game->title }}</h3>
                                                <p class="mt-1 line-clamp-1 text-xs text-gray-400">{{ $descriptionFor($game, 60) }}</p>
                                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                                    @if($discount > 0)
                                                        <span class="price-discount rounded px-2 py-1 text-xs font-black">-{{ $discount }}%</span>
                                                        <span class="text-xs text-gray-500 line-through">{{ $formatPrice($game->price) }}</span>
                                                    @endif
                                                    <span class="text-sm font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                                </div>
                                            </div>
                                        </article>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="store-panel rounded-lg p-8 text-center text-gray-300">Game not found</div>
                    @endif
                </section>

                <section id="events">
                    <div class="mb-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center text-[#118dff]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2c2.8 2.2 4.2 4.64 4.2 7.32 0 1.1-.25 2.03-.74 2.8.73-.27 1.48-.82 2.24-1.66.9 1.08 1.35 2.32 1.35 3.73A6.92 6.92 0 0 1 12 21a6.92 6.92 0 0 1-7.05-6.81c0-2.7 1.43-5.13 4.3-7.29-.2 1.56.1 2.8.92 3.72.86.96 1.97 1.32 3.35 1.08.72-2.22.21-5.46-1.52-9.7Z"/>
                                </svg>
                            </span>
                            <h2 class="text-xl font-black text-white md:text-2xl uppercase tracking-tight">New Releases</h2>
                        </div>
                        <a href="{{ route('games.search', request()->only('search', 'genre', 'category')) }}" class="hidden items-center gap-2 text-sm font-bold text-[#118dff] transition hover:text-white sm:inline-flex">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6">
                        @forelse($newReleases as $game)
                            @php
                                $discount = $discountFor($game);
                                $finalPrice = $finalPriceFor($game);
                            @endphp
                            <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                                <article class="game-card relative min-h-[120px] overflow-hidden rounded-lg">
                                    <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/52 to-transparent"></div>
                                    <span class="icon-button absolute right-2 top-2 h-8 w-8 rounded-md" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.49-2.02-4.5-4.5-4.5-1.72 0-3.22.96-3.98 2.38A4.49 4.49 0 0 0 8.5 3.75C6.02 3.75 4 5.76 4 8.25c0 7.22 8 11.5 8 11.5s9-4.28 9-11.5Z"/>
                                        </svg>
                                    </span>
                                    <div class="absolute inset-x-0 bottom-0 p-3">
                                        <h3 class="line-clamp-1 text-base font-black uppercase leading-tight text-white">{{ $game->title }}</h3>
                                        <div class="mt-2 flex flex-wrap items-center gap-2">
                                            @if($discount > 0)
                                                <span class="price-discount rounded px-2 py-0.5 text-xs font-black">-{{ $discount }}%</span>
                                            @endif
                                            <span class="text-xs font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                        </div>
                                    </div>
                                </article>
                            </a>
                        @empty
                            <div class="store-panel rounded-lg p-8 text-gray-300 sm:col-span-2 lg:col-span-3 2xl:col-span-6">Belum ada rilisan baru.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="store-panel h-fit rounded-lg p-5 xl:sticky xl:top-24">
                <div class="mb-5 flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center text-[#ff7b22]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2c2.8 2.2 4.2 4.64 4.2 7.32 0 1.1-.25 2.03-.74 2.8.73-.27 1.48-.82 2.24-1.66.9 1.08 1.35 2.32 1.35 3.73A6.92 6.92 0 0 1 12 21a6.92 6.92 0 0 1-7.05-6.81c0-2.7 1.43-5.13 4.3-7.29-.2 1.56.1 2.8.92 3.72.86.96 1.97 1.32 3.35 1.08.72-2.22.21-5.46-1.52-9.7Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-xl font-black text-white">Popular Right Now</h2>
                        <p class="mt-0.5 text-xs font-bold text-gray-400">Diurutkan dari pembelian berhasil.</p>
                    </div>
                </div>

                <div class="space-y-3">
                    @forelse($popularGames as $rank => $game)
                        @php
                            $discount = $discountFor($game);
                            $finalPrice = $finalPriceFor($game);
                            $purchaseCount = (int) ($game->paid_purchases_count ?? 0);
                        @endphp
                        <a href="{{ url('/game/' . $game->game_id) }}" class="grid grid-cols-[24px_100px_1fr] items-center gap-3 rounded-lg border border-transparent p-2 transition hover:border-[#2a475e] hover:bg-[#0f1923]/78">
                            <span class="text-center text-base font-black text-gray-500">{{ $rank + 1 }}</span>
                            <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="h-14 w-[100px] rounded-md object-cover" loading="lazy" decoding="async">
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-black uppercase text-white">{{ $game->title }}</span>
                                <span class="mt-0.5 inline-flex items-center gap-1 rounded bg-[#0b2a44]/90 px-2 py-0.5 text-[10px] font-black uppercase text-[#66c0f4]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h16M8 17v-5m4 5V8m4 9v-8"/>
                                    </svg>
                                    {{ number_format($purchaseCount, 0, ',', '.') }}
                                </span>
                                <span class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                                    @if($discount > 0)
                                        <span class="price-discount rounded px-2 py-0.5 font-black">-{{ $discount }}%</span>
                                    @endif
                                    <span class="font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                </span>
                            </span>
                        </a>
                    @empty
                        <div class="rounded-lg border border-[#2a475e] bg-[#07111d]/80 p-5 text-sm text-gray-300">Belum ada game populer.</div>
                    @endforelse
                </div>

                <a href="{{ route('games.search', array_merge(request()->only('search', 'genre', 'category'), ['sort' => 'popular'])) }}" class="mt-6 flex items-center justify-center gap-2 rounded-lg border border-[#2a475e] px-4 py-3 text-center text-sm font-black text-[#118dff] transition hover:border-[#66c0f4] hover:bg-[#0f1923] hover:text-white">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h16M8 17v-5m4 5V8m4 9v-8"/>
                    </svg>
                    View Top Sellers
                </a>
            </aside>
        </section>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-hero-carousel]').forEach((carousel) => {
                const slides = Array.from(carousel.querySelectorAll('[data-hero-slide]'));
                const dots = Array.from(carousel.querySelectorAll('[data-hero-dot]'));
                const previous = carousel.querySelector('[data-hero-prev]');
                const next = carousel.querySelector('[data-hero-next]');
                const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                const interactiveSelector = 'a, button, input, select, textarea, summary, details';
                const autoplayDelay = 5200;
                const dragThreshold = 55;
                let activeIndex = 0;
                let autoplayTimer = null;
                let pointerStartX = 0;
                let pointerCurrentX = 0;
                let isDragging = false;
                let didDrag = false;
                let suppressClick = false;

                const showSlide = (index) => {
                    if (slides.length === 0) {
                        return;
                    }

                    activeIndex = (index + slides.length) % slides.length;

                    slides.forEach((slide, slideIndex) => {
                        slide.classList.toggle('hidden', slideIndex !== activeIndex);
                    });

                    dots.forEach((dot, dotIndex) => {
                        dot.classList.toggle('is-active', dotIndex === activeIndex);
                    });
                };

                const stopAutoplay = () => {
                    window.clearInterval(autoplayTimer);
                    autoplayTimer = null;
                };

                const startAutoplay = () => {
                    if (slides.length <= 1 || reduceMotion || autoplayTimer) {
                        return;
                    }

                    autoplayTimer = window.setInterval(() => {
                        showSlide(activeIndex + 1);
                    }, autoplayDelay);
                };

                const restartAutoplay = () => {
                    stopAutoplay();
                    startAutoplay();
                };

                const finishDrag = () => {
                    if (!isDragging) {
                        return;
                    }

                    const deltaX = pointerCurrentX - pointerStartX;
                    carousel.classList.remove('is-dragging');
                    isDragging = false;

                    if (Math.abs(deltaX) >= dragThreshold) {
                        showSlide(activeIndex + (deltaX < 0 ? 1 : -1));
                        suppressClick = true;
                        window.setTimeout(() => {
                            suppressClick = false;
                            didDrag = false;
                        }, 120);
                    }

                    restartAutoplay();
                };

                previous?.addEventListener('click', () => {
                    showSlide(activeIndex - 1);
                    restartAutoplay();
                });

                next?.addEventListener('click', () => {
                    showSlide(activeIndex + 1);
                    restartAutoplay();
                });

                dots.forEach((dot) => {
                    dot.addEventListener('click', () => {
                        showSlide(Number(dot.dataset.slideIndex));
                        restartAutoplay();
                    });
                });

                carousel.addEventListener('pointerdown', (event) => {
                    if (slides.length <= 1 || event.button !== 0 || event.target.closest(interactiveSelector)) {
                        return;
                    }

                    stopAutoplay();
                    isDragging = true;
                    didDrag = false;
                    pointerStartX = event.clientX;
                    pointerCurrentX = event.clientX;
                    carousel.classList.add('is-dragging');
                    carousel.setPointerCapture?.(event.pointerId);
                });

                carousel.addEventListener('pointermove', (event) => {
                    if (!isDragging) {
                        return;
                    }

                    pointerCurrentX = event.clientX;

                    if (Math.abs(pointerCurrentX - pointerStartX) > 8) {
                        didDrag = true;
                    }
                });

                carousel.addEventListener('pointerup', finishDrag);
                carousel.addEventListener('pointercancel', finishDrag);
                carousel.addEventListener('pointerleave', finishDrag);

                carousel.addEventListener('click', (event) => {
                    if (!suppressClick || !didDrag) {
                        return;
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    suppressClick = false;
                    didDrag = false;
                }, true);

                carousel.addEventListener('focusin', stopAutoplay);
                carousel.addEventListener('focusout', startAutoplay);

                startAutoplay();
            });
        });
    </script>
@endpush
