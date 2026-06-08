@php
    use Illuminate\Support\Str;

    $fallbackImage = 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?q=70&w=1400&auto=format&fit=crop';
    $imageFor = fn ($game) => $game?->thumbnail_url ?: $fallbackImage;
    $discountFor = fn ($game) => (int) ($game?->detail?->discount ?? 0);
    $finalPriceFor = fn ($game) => max(0, (float) ($game?->price ?? 0) * (1 - ($discountFor($game) / 100)));
    $formatPrice = fn ($price) => (float) $price <= 0 ? 'Gratis' : 'Rp ' . number_format($price, 0, ',', '.');
    $descriptionFor = fn ($game, $limit = 130) => Str::limit($game?->detail?->short_description ?: $game?->description ?: 'Temukan dunia baru, tantangan seru, dan pengalaman bermain pilihan di PlayMart.', $limit);
    $releaseDateFor = fn ($game) => $game?->release_date ? $game->release_date->translatedFormat('j M Y') : 'Tanggal belum tersedia';
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
    $leadRecommended = $recommendedGames->first();
    $sideRecommended = $recommendedGames->skip(1)->take(4);
@endphp

@extends('layouts.store')

@push('styles')
    <style>
        .store-panel {
            background:
                linear-gradient(180deg, rgba(15, 25, 35, 0.92), rgba(7, 17, 29, 0.94)),
                linear-gradient(135deg, rgba(102, 192, 244, 0.14), rgba(45, 115, 255, 0.06));
            border: 1px solid rgba(42, 71, 94, 0.88);
            box-shadow: 0 22px 54px rgba(0, 0, 0, 0.34);
        }
        .store-home {
            display: flex;
            flex-direction: column;
        }
        #discover {
            order: 10;
        }
        #recommended {
            order: 20;
            margin-top: 2rem !important;
        }
        #store-showcase {
            order: 60;
            margin-top: 4.5rem !important;
        }
        #browse-categories {
            order: 30;
            margin-top: 4.5rem !important;
        }
        #budget-deals {
            order: 40;
            margin-top: 4.5rem !important;
        }
        .hero-panel {
            --hero-height: 620px;
            --hero-title-size: 4.25rem;
            --hero-title-line: .98;
            --hero-title-lines: 4;
            --hero-title-height: calc(var(--hero-title-size) * var(--hero-title-line) * var(--hero-title-lines));
            --hero-subtitle-height: 1.6rem;
            --hero-description-height: 3.5rem;
            --hero-copy-width: 760px;
            --hero-media-width: 560px;
            --hero-media-height: 520px;
            height: var(--hero-height) !important;
            min-height: var(--hero-height) !important;
            max-height: var(--hero-height) !important;
            background: #07111d;
            isolation: isolate;
            overflow: hidden;
        }
        .hero-panel::after {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            border-radius: inherit;
            box-shadow:
                inset 0 0 0 1px rgba(102, 192, 244, 0.12),
                inset 0 -140px 180px rgba(5, 10, 18, 0.42);
        }
        .hero-copy {
            display: flex;
            height: 100%;
            width: min(100%, var(--hero-copy-width));
            max-width: var(--hero-copy-width);
            min-width: 0;
            min-height: 0;
            flex-direction: column;
            justify-content: center;
            overflow: hidden;
            text-shadow: 0 2px 16px rgba(0, 0, 0, 0.7);
        }
        .hero-kicker {
            flex: 0 0 auto;
            align-self: flex-start;
            margin-bottom: 1.35rem !important;
        }
        .hero-title {
            display: -webkit-box;
            flex: 0 0 var(--hero-title-height);
            height: var(--hero-title-height) !important;
            max-width: 100%;
            overflow: hidden;
            overflow-wrap: break-word;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 4;
            line-clamp: 4;
            font-size: var(--hero-title-size) !important;
            line-height: var(--hero-title-line) !important;
            margin: 0 !important;
        }
        .hero-subtitle {
            display: -webkit-box;
            flex: 0 0 var(--hero-subtitle-height);
            height: var(--hero-subtitle-height) !important;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 1;
            align-items: center;
            margin-top: 1.1rem !important;
        }
        .hero-description {
            display: -webkit-box;
            flex: 0 0 var(--hero-description-height);
            height: var(--hero-description-height) !important;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            margin-top: 1.25rem !important;
        }
        .hero-slide-grid {
            height: 100%;
            min-height: 0;
            box-sizing: border-box;
            grid-template-columns: minmax(0, 1fr);
            overflow: hidden;
            padding-top: 2.25rem !important;
            padding-bottom: 2.25rem !important;
        }
        .hero-actions {
            flex: 0 0 auto;
            min-height: 4.4rem;
            margin-top: 2rem !important;
        }
        .hero-media-card {
            width: var(--hero-media-width) !important;
            height: var(--hero-media-height) !important;
            min-height: var(--hero-media-height) !important;
            max-height: var(--hero-media-height) !important;
            min-width: 0;
            justify-self: end;
            background: linear-gradient(180deg, rgba(15, 25, 35, .72), rgba(5, 10, 18, .9));
            border: 1px solid rgba(102, 192, 244, .22);
            box-shadow: 0 24px 70px rgba(0, 0, 0, .46);
            backdrop-filter: blur(16px);
        }
        .hero-preview-frame {
            aspect-ratio: 16 / 9;
            flex: 0 0 auto;
            width: 100%;
            overflow: hidden;
            border: 1px solid rgba(102, 192, 244, .16);
            background: #07111d;
        }
        .hero-preview-image {
            height: 100%;
            width: 100%;
            object-fit: cover;
        }
        .hero-tag-list {
            flex: 0 0 4.7rem;
            height: 4.7rem !important;
            max-height: 4.7rem;
            overflow: hidden;
            align-content: flex-start;
        }
        .hero-meta-pill {
            border: 1px solid rgba(102, 192, 244, .26);
            background: rgba(7, 17, 29, .78);
            color: #d7e9f8;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: calc(50% - .25rem);
        }
        .hero-fact-grid {
            flex: 0 0 auto;
            min-height: 4.9rem;
        }
        .game-card {
            background: #0f1923;
            border: 1px solid rgba(42, 71, 94, 0.95);
            transition: transform .22s ease, border-color .22s ease, box-shadow .22s ease, background .22s ease;
        }
        .game-card:hover {
            transform: translateY(-4px);
            background: #16202d;
            border-color: rgba(102, 192, 244, 0.78);
            box-shadow: 0 18px 40px rgba(17, 141, 255, 0.16);
        }
        .game-card img,
        .budget-cover,
        .showcase-thumb,
        .popular-thumb {
            transition: transform .3s ease, filter .3s ease;
        }
        .game-card:hover img,
        .budget-card:hover .budget-cover,
        .showcase-row:hover .showcase-thumb,
        .showcase-row.is-active .showcase-thumb,
        .popular-row:hover .popular-thumb {
            transform: scale(1.045);
            filter: saturate(1.08);
        }
        .icon-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(42, 71, 94, 0.95);
            background: rgba(15, 25, 35, 0.82);
            color: #d7e3f2;
            transition: border-color .2s ease, color .2s ease, background .2s ease, transform .2s ease;
        }
        .icon-button:hover {
            border-color: rgba(102, 192, 244, 0.78);
            color: #fff;
            background: rgba(22, 32, 45, 0.96);
            transform: translateY(-1px);
        }
        .price-discount {
            background: linear-gradient(180deg, #9fd24b, #73a928);
            color: #102008;
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .18);
        }
        .hero-dot.is-active {
            width: 34px;
            background: #66c0f4;
            box-shadow: 0 0 16px rgba(102, 192, 244, .62);
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
        .showcase-tabs {
            border-bottom: 1px solid rgba(42, 71, 94, 0.68);
        }
        .showcase-tab {
            position: relative;
            flex: 0 0 auto;
            padding: 0 0 12px;
            color: rgba(229, 236, 245, 0.72);
            font-size: clamp(1rem, 1.5vw, 1.42rem);
            font-weight: 900;
            line-height: 1.15;
            transition: color .18s ease;
        }
        .showcase-tab::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -1px;
            left: 0;
            height: 3px;
            border-radius: 999px;
            background: #66c0f4;
            box-shadow: 0 0 16px rgba(102, 192, 244, 0.58);
            opacity: 0;
            transform: scaleX(.64);
            transition: opacity .18s ease, transform .18s ease;
        }
        .showcase-tab:hover,
        .showcase-tab.is-active {
            color: #fff;
        }
        .showcase-tab.is-active::after {
            opacity: 1;
            transform: scaleX(1);
        }
        .showcase-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(380px, 500px);
            gap: 28px;
        }
        .showcase-row {
            position: relative;
            background: linear-gradient(90deg, rgba(22, 32, 45, .92), rgba(15, 25, 35, .88));
            border: 1px solid rgba(42, 71, 94, 0.5);
            box-shadow: 0 10px 24px rgba(0, 0, 0, .16);
            transition: background .18s ease, border-color .18s ease, transform .18s ease, box-shadow .18s ease;
        }
        .showcase-row::before {
            content: "";
            position: absolute;
            inset-block: 12px;
            left: 0;
            width: 3px;
            border-radius: 999px;
            background: #66c0f4;
            opacity: 0;
            transition: opacity .18s ease;
        }
        .showcase-row:hover,
        .showcase-row.is-active {
            background: linear-gradient(90deg, rgba(42, 71, 94, .98), rgba(22, 32, 45, .98));
            border-color: rgba(102, 192, 244, .72);
            box-shadow: 0 16px 34px rgba(17, 141, 255, .12);
        }
        .showcase-row.is-active {
            transform: translateX(5px);
        }
        .showcase-row:hover::before,
        .showcase-row.is-active::before {
            opacity: 1;
        }
        .showcase-detail {
            background:
                linear-gradient(180deg, rgba(42, 71, 94, .76), rgba(15, 25, 35, .98)),
                radial-gradient(circle at 15% 0%, rgba(102, 192, 244, .16), transparent 18rem);
            border: 1px solid rgba(102, 192, 244, .18);
            box-shadow: 0 22px 54px rgba(0, 0, 0, .34);
        }
        .showcase-tag {
            border: 1px solid rgba(102, 192, 244, .13);
            background: rgba(73, 97, 121, .7);
            color: #f0f6ff;
        }
        .category-carousel {
            position: relative;
            isolation: isolate;
            padding-inline: 58px;
        }
        .category-carousel::before,
        .category-carousel::after {
            content: "";
            position: absolute;
            top: 0;
            bottom: 42px;
            z-index: 2;
            width: 70px;
            pointer-events: none;
        }
        .category-carousel::before {
            left: 0;
            background: linear-gradient(90deg, #07111d, rgba(7, 17, 29, 0));
        }
        .category-carousel::after {
            right: 0;
            background: linear-gradient(270deg, #07111d, rgba(7, 17, 29, 0));
        }
        .category-track {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: minmax(240px, calc((100% - 96px) / 5));
            gap: 24px;
            overflow-x: auto;
            overscroll-behavior-inline: contain;
            padding: 12px 4px 30px;
            scroll-behavior: smooth;
            scroll-padding-inline: 2px;
            scroll-snap-type: x mandatory;
            -webkit-overflow-scrolling: touch;
        }
        .category-track::-webkit-scrollbar {
            display: none;
        }
        .category-card {
            position: relative;
            min-height: 210px;
            overflow: hidden;
            border: 1px solid rgba(102, 192, 244, .14);
            border-radius: 8px;
            background-position: center;
            background-size: cover;
            scroll-snap-align: start;
            transform-origin: center;
            box-shadow: 0 18px 36px rgba(0, 0, 0, .22);
            transition: border-color .24s ease, filter .24s ease, transform .24s ease, box-shadow .24s ease;
            will-change: transform;
        }
        .category-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, .05), transparent 38%, rgba(5, 10, 18, .42)),
                rgba(5, 10, 18, .1);
        }
        .category-card:hover {
            border-color: rgba(102, 192, 244, .68);
            filter: brightness(1.08);
            transform: translateY(-6px) scale(1.035);
            box-shadow: 0 24px 52px rgba(17, 141, 255, .14);
        }
        .category-card:focus-visible {
            outline: 3px solid rgba(102, 192, 244, .72);
            outline-offset: 4px;
        }
        .category-label {
            position: relative;
            z-index: 1;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .18);
            background: rgba(255, 255, 255, .9);
            color: #111827;
            box-shadow: 0 10px 26px rgba(0, 0, 0, .28);
            letter-spacing: .04em;
            transition: background .2s ease, color .2s ease, transform .2s ease;
        }
        .category-card:hover .category-label {
            background: #fff;
            color: #050a12;
            transform: scale(1.03);
        }
        .category-arrow {
            position: absolute;
            top: 50%;
            z-index: 4;
            display: inline-flex;
            height: 58px;
            width: 46px;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border: 1px solid rgba(102, 192, 244, .26);
            border-radius: 8px;
            background: rgba(7, 17, 29, .78);
            color: rgba(229, 236, 245, .82);
            box-shadow: 0 18px 38px rgba(0, 0, 0, .32);
            backdrop-filter: blur(14px);
            transition: border-color .18s ease, color .18s ease, opacity .18s ease, transform .18s ease, background .18s ease;
        }
        .category-arrow:hover {
            border-color: rgba(102, 192, 244, .78);
            background: rgba(15, 25, 35, .96);
            color: #fff;
            transform: translateY(-50%) scale(1.08);
        }
        .category-arrow.is-disabled {
            opacity: .32;
            pointer-events: none;
        }
        .category-arrow-left {
            left: 6px;
        }
        .category-arrow-right {
            right: 6px;
        }
        .category-dots {
            min-height: 12px;
        }
        .category-dot {
            height: 8px;
            width: 16px;
            border-radius: 999px;
            background: rgba(255, 255, 255, .28);
            transition: width .2s ease, background .2s ease, box-shadow .2s ease, opacity .2s ease;
        }
        .category-dot.is-active {
            width: 34px;
            background: #66c0f4;
            box-shadow: 0 0 16px rgba(102, 192, 244, .58);
        }
        .budget-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 24px;
        }
        .budget-card {
            min-width: 0;
            border: 1px solid rgba(42, 71, 94, .5);
            border-radius: 8px;
            background: rgba(15, 25, 35, .72);
            box-shadow: 0 12px 26px rgba(0, 0, 0, .18);
            transition: transform .2s ease, filter .2s ease, border-color .2s ease, box-shadow .2s ease;
        }
        .budget-card:hover {
            border-color: rgba(102, 192, 244, .66);
            filter: brightness(1.1);
            transform: translateY(-4px);
            box-shadow: 0 18px 38px rgba(17, 141, 255, .14);
        }
        .budget-cover {
            aspect-ratio: 16 / 9;
            width: 100%;
            object-fit: cover;
        }
        .budget-title {
            min-height: 44px;
            background: rgba(15, 25, 35, .86);
        }
        .budget-price-strip {
            margin-left: auto;
            width: fit-content;
            max-width: 100%;
            background: #07111d;
        }
        .budget-more-link {
            background: rgba(255, 255, 255, .82);
            color: #111827;
            transition: background .18s ease, transform .18s ease;
        }
        .budget-more-link:hover {
            background: #fff;
            transform: translateY(-1px);
        }
        .section-heading {
            font-size: clamp(1.55rem, 2.2vw, 2.25rem);
            letter-spacing: -0.015em;
        }
        .store-link {
            color: #58a9ff;
            transition: color .18s ease, transform .18s ease;
        }
        .store-link:hover {
            color: #fff;
            transform: translateX(2px);
        }
        .popular-row {
            transition: background .18s ease, border-color .18s ease, transform .18s ease;
        }
        .popular-row:hover {
            transform: translateX(3px);
        }
        .skeleton-card {
            min-height: 132px;
            border-radius: 8px;
            background: linear-gradient(90deg, rgba(15, 25, 35, .85), rgba(42, 71, 94, .35), rgba(15, 25, 35, .85));
            background-size: 220% 100%;
            animation: playmart-shimmer 1.35s ease-in-out infinite;
        }
        @keyframes playmart-shimmer {
            0% { background-position: 120% 0; }
            100% { background-position: -120% 0; }
        }
        @media (min-width: 1024px) {
            .hero-slide-grid {
                grid-template-columns: minmax(0, 1fr) var(--hero-media-width) !important;
            }
        }
        @media (max-width: 1280px) {
            .hero-panel {
                --hero-height: 560px;
                --hero-title-size: 3.4rem;
                --hero-title-lines: 3;
                --hero-copy-width: 620px;
                --hero-media-width: 500px;
                --hero-media-height: 480px;
            }
            .hero-title {
                -webkit-line-clamp: 3;
                line-clamp: 3;
            }
            .hero-tag-list {
                flex-basis: 4rem;
                height: 4rem !important;
                max-height: 4rem;
            }
            .hero-fact-grid {
                min-height: 4.6rem;
                padding-top: 1rem !important;
            }
            .showcase-grid {
                grid-template-columns: 1fr;
            }
            .category-carousel {
                padding-inline: 50px;
            }
            .category-track {
                grid-auto-columns: minmax(230px, calc((100% - 72px) / 4));
            }
            .budget-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (max-width: 768px) {
            .hero-panel {
                --hero-height: 540px;
                --hero-title-size: 3rem;
                --hero-title-line: 1;
                --hero-title-lines: 3;
                --hero-copy-width: 100%;
            }
            .hero-copy {
                max-width: 100%;
            }
            .hero-title {
                -webkit-line-clamp: 3;
                line-clamp: 3;
            }
            .hero-slide-grid {
                align-items: center;
                padding-top: 2rem !important;
                padding-bottom: 2rem !important;
            }
            .hero-actions {
                margin-top: 1.45rem !important;
            }
        }
        @media (max-width: 640px) {
            .hero-panel {
                --hero-height: 520px;
                --hero-title-size: 2.55rem;
                --hero-title-lines: 3;
                --hero-description-height: 3.15rem;
            }
            .showcase-row {
                grid-template-columns: 112px minmax(0, 1fr);
            }
            .showcase-price {
                grid-column: 1 / -1;
                justify-self: stretch;
            }
            .category-track {
                grid-auto-columns: minmax(225px, 82%);
            }
            .category-carousel {
                padding-inline: 42px;
            }
            .category-carousel::before,
            .category-carousel::after {
                width: 44px;
                bottom: 36px;
            }
            .category-card {
                min-height: 180px;
            }
            .category-arrow {
                height: 48px;
                width: 36px;
            }
            .category-arrow-left {
                left: 0;
            }
            .category-arrow-right {
                right: 0;
            }
            .budget-grid {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 420px) {
            .hero-panel {
                --hero-height: 500px;
                --hero-title-size: 2.2rem;
                --hero-title-lines: 2;
            }
            .hero-title {
                -webkit-line-clamp: 2;
                line-clamp: 2;
            }
        }
    </style>
@endpush

@section('content')
    <main class="store-home pb-12">
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
                            class="absolute inset-0 h-full w-full scale-[1.02] object-cover"
                            @if($slideIndex === 0)
                                fetchpriority="high"
                                loading="eager"
                            @else
                                loading="lazy"
                            @endif
                            decoding="async"
                        >
                        <div class="absolute inset-0 bg-gradient-to-r from-[#050a12] via-[#07111d]/88 to-[#07111d]/32"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#050a12]/86 via-[#050a12]/18 to-[#050a12]/16"></div>

                        <div class="hero-slide-grid relative z-10 grid items-center gap-8 px-6 py-10 md:px-12 lg:px-20">
                            <div class="hero-copy">
                                <span class="hero-kicker inline-flex rounded-md border border-[#66c0f4]/25 bg-[#063b80]/90 px-4 py-2 text-xs font-black uppercase tracking-wider text-[#66c0f4] shadow-lg shadow-blue-950/30">
                                    Featured
                                </span>

                                <h1 class="hero-title font-black uppercase tracking-tight text-white">
                                    {{ $heroGame->title }}
                                </h1>

                                <p class="hero-subtitle mt-5 text-sm font-black uppercase tracking-[0.26em] text-[#66c0f4] md:text-base">
                                    {{ $heroGame->genres->take(3)->pluck('name')->join(' / ') ?: 'Explore. Play. Collect.' }}
                                </p>

                                <p class="hero-description mt-5 max-w-2xl text-base font-semibold leading-7 text-gray-200 md:text-lg">
                                    {{ $descriptionFor($heroGame, 150) }}
                                </p>

                                <div class="hero-actions flex flex-wrap items-center gap-4">
                                    @if($featuredDiscount > 0)
                                        <span class="price-discount rounded-md px-3 py-2 text-lg font-black">-{{ $featuredDiscount }}%</span>
                                        <span class="text-sm font-semibold text-gray-500 line-through">{{ $formatPrice($heroGame->price) }}</span>
                                    @endif
                                    <span class="text-3xl font-black text-white">{{ $formatPrice($featuredFinal) }}</span>
                                    <a href="{{ url('/game/' . $heroGame->game_id) }}" class="steam-blue ml-0 rounded-lg px-8 py-4 text-base font-black text-white shadow-lg shadow-blue-950/40 transition hover:-translate-y-0.5 hover:brightness-110 sm:ml-3">
                                        View Game
                                    </a>
                                    <a href="{{ url('/game/' . $heroGame->game_id) }}" class="icon-button h-12 w-12 rounded-lg" aria-label="Open {{ $heroGame->title }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0-7 7m7-7H3"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="hero-media-card hidden overflow-hidden rounded-lg p-4 lg:flex lg:flex-col">
                                <div class="hero-preview-frame rounded-md">
                                    <img
                                        src="{{ $imageFor($heroGame) }}"
                                        alt="{{ $heroGame->title }} preview"
                                        class="hero-preview-image"
                                        loading="lazy"
                                        decoding="async"
                                    >
                                </div>
                                <div class="hero-tag-list mt-4 flex flex-wrap gap-2">
                                    @foreach($heroGame->genres->take(3) as $heroGenre)
                                        <span class="hero-meta-pill rounded px-3 py-1.5 text-xs font-black uppercase">{{ $heroGenre->name }}</span>
                                    @endforeach
                                    @foreach($heroGame->categories->take(2) as $heroCategory)
                                        <span class="hero-meta-pill rounded px-3 py-1.5 text-xs font-black uppercase">{{ $heroCategory->name }}</span>
                                    @endforeach
                                </div>
                                <div class="hero-fact-grid mt-auto grid grid-cols-2 gap-3 pt-5 text-sm font-bold text-gray-300">
                                    <div class="rounded-md border border-[#2a475e]/70 bg-[#07111d]/70 p-3">
                                        <span class="block text-xs uppercase tracking-[0.18em] text-gray-500">Rilis</span>
                                        <span class="mt-1 block text-white">{{ $releaseDateFor($heroGame) }}</span>
                                    </div>
                                    <div class="rounded-md border border-[#2a475e]/70 bg-[#07111d]/70 p-3">
                                        <span class="block text-xs uppercase tracking-[0.18em] text-gray-500">Harga</span>
                                        <span class="mt-1 block text-white">{{ $formatPrice($featuredFinal) }}</span>
                                    </div>
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

                    <div class="absolute bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-2 rounded-full border border-white/10 bg-[#050a12]/60 px-3 py-2 backdrop-blur">
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

        @if($showcaseTabs->isNotEmpty())
            <section id="store-showcase" class="store-container mt-12" data-showcase>
                <div class="showcase-tabs store-scrollbar flex gap-8 overflow-x-auto">
                    @foreach($showcaseTabs as $tabIndex => $tab)
                        <button
                            type="button"
                            class="showcase-tab whitespace-nowrap {{ $tabIndex === 0 ? 'is-active' : '' }}"
                            data-showcase-tab
                            data-showcase-tab-key="{{ $tab['key'] }}"
                            aria-controls="showcase-panel-{{ $tab['key'] }}"
                            aria-selected="{{ $tabIndex === 0 ? 'true' : 'false' }}"
                        >
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-7">
                    @foreach($showcaseTabs as $tabIndex => $tab)
                        <div
                            id="showcase-panel-{{ $tab['key'] }}"
                            class="{{ $tabIndex === 0 ? '' : 'hidden' }}"
                            data-showcase-panel
                            data-showcase-panel-key="{{ $tab['key'] }}"
                        >
                            <div class="showcase-grid">
                                <div class="min-w-0 space-y-3">
                                    @foreach($tab['games'] as $gameIndex => $game)
                                        @php
                                            $discount = $discountFor($game);
                                            $finalPrice = $finalPriceFor($game);
                                            $rowTags = $game->genres
                                                ->take(3)
                                                ->pluck('name')
                                                ->merge($game->categories->take(1)->pluck('name'))
                                                ->filter()
                                                ->values();
                                        @endphp

                                        <a
                                            href="{{ url('/game/' . $game->game_id) }}"
                                            class="showcase-row grid items-center gap-5 overflow-hidden rounded-lg p-3 sm:grid-cols-[220px_minmax(0,1fr)_120px]"
                                            data-showcase-item
                                            data-showcase-index="{{ $gameIndex }}"
                                        >
                                            <span class="block overflow-hidden rounded-md">
                                                <img
                                                    src="{{ $imageFor($game) }}"
                                                    alt="{{ $game->title }}"
                                                    class="showcase-thumb h-[86px] w-full object-cover sm:h-[98px]"
                                                    loading="lazy"
                                                    decoding="async"
                                                >
                                            </span>

                                            <span class="min-w-0">
                                                <span class="block truncate text-[1.28rem] font-black leading-tight text-white">{{ $game->title }}</span>
                                                <span class="mt-2 block truncate text-[0.95rem] font-bold text-gray-300">
                                                    {{ $rowTags->isNotEmpty() ? $rowTags->join(', ') : 'PlayMart' }}
                                                </span>
                                                <span class="mt-3 block text-sm font-bold text-gray-400">Dirilis: {{ $releaseDateFor($game) }}</span>
                                            </span>

                                            <span class="showcase-price flex min-h-11 items-center justify-center rounded-md border border-[#2a475e]/70 bg-[#07111d] px-3 text-base font-black text-white shadow-inner shadow-black/20">
                                                {{ $formatPrice($finalPrice) }}
                                            </span>
                                        </a>
                                    @endforeach
                                </div>

                                <aside class="min-w-0">
                                    @foreach($tab['games'] as $gameIndex => $game)
                                        @php
                                            $discount = $discountFor($game);
                                            $finalPrice = $finalPriceFor($game);
                                            $previewImages = $game->screenshots
                                                ->pluck('url')
                                                ->prepend($imageFor($game))
                                                ->filter()
                                                ->unique()
                                                ->take(3)
                                                ->values();
                                            $detailTags = $game->genres
                                                ->take(3)
                                                ->pluck('name')
                                                ->merge($game->categories->take(3)->pluck('name'))
                                                ->filter()
                                                ->values();
                                        @endphp

                                        <a
                                            href="{{ url('/game/' . $game->game_id) }}"
                                            class="showcase-detail block overflow-hidden rounded-lg p-5 {{ $gameIndex === 0 ? '' : 'hidden' }}"
                                            data-showcase-detail
                                            data-showcase-index="{{ $gameIndex }}"
                                        >
                                            <div class="p-1">
                                                <h3 class="line-clamp-2 text-2xl font-black leading-tight text-white">{{ $game->title }}</h3>
                                                <p class="mt-4 text-sm font-bold text-gray-300">Ulasan Umum Pengguna</p>
                                                <p class="text-sm font-black text-[#d8b35a]">Bercampur ({{ number_format($game->reviews_count ?? 0, 0, ',', '.') }})</p>

                                                <div class="mt-4 flex flex-wrap gap-2.5">
                                                    @forelse($detailTags as $tag)
                                                        <span class="showcase-tag rounded-md px-3 py-1.5 text-sm font-black">{{ $tag }}</span>
                                                    @empty
                                                        <span class="showcase-tag rounded-md px-3 py-1.5 text-sm font-black">Game</span>
                                                    @endforelse
                                                </div>
                                            </div>

                                            <div class="mt-5 space-y-3">
                                                @foreach($previewImages as $previewIndex => $previewImage)
                                                    <img
                                                        src="{{ $previewImage }}"
                                                        alt="{{ $game->title }} preview {{ $previewIndex + 1 }}"
                                                        class="w-full rounded-md border border-[#2a475e]/60 object-cover {{ $previewIndex === 0 ? 'aspect-[16/9]' : 'aspect-[16/7]' }}"
                                                        loading="lazy"
                                                        decoding="async"
                                                    >
                                                @endforeach
                                            </div>

                                            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-[#2a475e] pt-4">
                                                <span class="text-sm font-black text-gray-300">{{ $releaseDateFor($game) }}</span>
                                                <span class="flex items-center gap-2">
                                                    @if($discount > 0)
                                                        <span class="price-discount rounded-md px-2.5 py-1 text-sm font-black">-{{ $discount }}%</span>
                                                    @endif
                                                    <span class="text-lg font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </aside>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        @if($browseCategoryCards->isNotEmpty())
            <section id="browse-categories" class="store-container mt-20">
                <h2 class="section-heading font-black text-white">Telusuri Berdasarkan Kategori</h2>

                <div class="category-carousel mt-8" data-category-carousel>
                    @if($browseCategoryCards->count() > 1)
                        <button type="button" class="category-arrow category-arrow-left" data-category-prev aria-label="Kategori sebelumnya">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.8" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                            </svg>
                        </button>
                    @endif

                    <div class="category-track store-scrollbar" data-category-track>
                        @foreach($browseCategoryCards as $card)
                            <a
                                href="{{ $card['url'] }}"
                                class="category-card flex items-center justify-center"
                                style="background-image: {{ $card['overlay'] }}, url('{{ $card['image'] }}');"
                            >
                                <span class="category-label px-5 py-3 text-center text-base font-black text-balance md:text-lg">
                                    {{ $card['label'] }}
                                </span>
                            </a>
                        @endforeach
                    </div>

                    @if($browseCategoryCards->count() > 1)
                        <button type="button" class="category-arrow category-arrow-right" data-category-next aria-label="Kategori berikutnya">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-full w-full" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.8" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    @endif

                    @if($browseCategoryCards->count() > 1)
                        <div class="category-dots mt-5 flex flex-wrap justify-center gap-2" data-category-dots>
                            <button type="button" class="category-dot is-active" data-category-dot data-category-page="0" aria-label="Lihat kategori halaman 1"></button>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        @if($budgetDeals->isNotEmpty())
            <section id="budget-deals" class="store-container mt-20">
                <div class="mb-7 flex flex-wrap items-center justify-between gap-5">
                    <h2 class="section-heading font-black text-white">Di Bawah Rp 90.000</h2>

                    <div class="flex flex-wrap items-center gap-2 text-base font-bold text-gray-200">
                        <span class="mr-1">Lebih banyak lagi:</span>
                        @foreach($budgetQuickLinks as $quickLink)
                            <a href="{{ $quickLink['url'] }}" class="budget-more-link rounded px-5 py-2 text-sm font-black">
                                {{ $quickLink['label'] }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="budget-grid">
                    @foreach($budgetDeals as $game)
                        @php
                            $discount = $discountFor($game);
                            $finalPrice = $finalPriceFor($game);
                        @endphp

                        <a href="{{ url('/game/' . $game->game_id) }}" class="budget-card block overflow-hidden">
                            <img
                                src="{{ $imageFor($game) }}"
                                alt="{{ $game->title }}"
                                class="budget-cover"
                                loading="lazy"
                                decoding="async"
                            >

                            <div class="budget-title flex items-center px-3 py-2">
                                <h3 class="line-clamp-1 text-sm font-black leading-tight text-white">{{ $game->title }}</h3>
                            </div>

                            <div class="budget-price-strip flex items-center rounded-tl-md text-sm font-black text-white">
                                @if($discount > 0)
                                    <span class="price-discount self-stretch px-3 py-2 text-base">-{{ $discount }}%</span>
                                    <span class="px-2 text-xs font-bold text-gray-500 line-through">{{ $formatPrice($game->price) }}</span>
                                @endif
                                <span class="px-3 py-2">{{ $formatPrice($finalPrice) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif

        <section id="recommended" class="store-container mt-20 grid gap-8 xl:grid-cols-[minmax(0,1fr)_430px]">
            <div class="min-w-0 space-y-8">
                <section>
                    <div class="mb-3 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="flex h-7 w-7 items-center justify-center text-[#118dff]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="m12 2 2.95 6.35 6.95.85-5.12 4.77 1.33 6.88L12 17.45l-6.11 3.4 1.33-6.88L2.1 9.2l6.95-.85L12 2Z"/>
                                </svg>
                            </span>
                            <h2 class="section-heading font-black uppercase tracking-tight text-white">Featured & Recommended</h2>
                        </div>
                        <a href="{{ route('games.search', request()->only('search', 'genre', 'category')) }}" class="store-link hidden items-center gap-2 text-sm font-black sm:inline-flex">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    @if($leadRecommended)
                        <div class="grid gap-5 lg:grid-cols-[1.12fr_1fr]">
                            @php
                                $discount = $discountFor($leadRecommended);
                                $finalPrice = $finalPriceFor($leadRecommended);
                            @endphp
                            <a href="{{ url('/game/' . $leadRecommended->game_id) }}" class="block">
                                <article class="game-card relative min-h-[320px] overflow-hidden rounded-lg md:min-h-[380px]">
                                    <img src="{{ $imageFor($leadRecommended) }}" alt="{{ $leadRecommended->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/48 to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-6">
                                        <h3 class="line-clamp-2 text-3xl font-black uppercase leading-none text-white md:text-4xl">{{ $leadRecommended->title }}</h3>
                                        <p class="mt-4 max-w-md text-base font-semibold leading-relaxed text-gray-300 line-clamp-2">{{ $descriptionFor($leadRecommended, 115) }}</p>
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

                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($sideRecommended as $game)
                                    @php
                                        $discount = $discountFor($game);
                                        $finalPrice = $finalPriceFor($game);
                                    @endphp
                                    <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                                        <article class="game-card relative min-h-[170px] overflow-hidden rounded-lg">
                                            <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                            <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/56 to-transparent"></div>
                                            <span class="icon-button absolute right-3 top-3 h-8 w-8 rounded-md" aria-hidden="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.49-2.02-4.5-4.5-4.5-1.72 0-3.22.96-3.98 2.38A4.49 4.49 0 0 0 8.5 3.75C6.02 3.75 4 5.76 4 8.25c0 7.22 8 11.5 8 11.5s9-4.28 9-11.5Z"/>
                                                </svg>
                                            </span>
                                            <div class="absolute inset-x-0 bottom-0 p-4">
                                                <h3 class="line-clamp-2 text-lg font-black uppercase leading-tight text-white">{{ $game->title }}</h3>
                                                <p class="mt-2 line-clamp-1 text-sm font-semibold text-gray-400">{{ $descriptionFor($game, 60) }}</p>
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
                            <h2 class="section-heading font-black uppercase tracking-tight text-white">Game Lagi Diskon</h2>
                        </div>
                        <a href="{{ route('games.search', array_merge(request()->only('search', 'genre', 'category'), ['discount' => 1])) }}" class="store-link hidden items-center gap-2 text-sm font-black sm:inline-flex">
                            View All
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                        @forelse($discountedGames as $game)
                            @php
                                $discount = $discountFor($game);
                                $finalPrice = $finalPriceFor($game);
                            @endphp
                            <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                                <article class="game-card relative min-h-[150px] overflow-hidden rounded-lg">
                                    <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="absolute inset-0 h-full w-full object-cover" loading="lazy" decoding="async">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#050a12] via-[#050a12]/52 to-transparent"></div>
                                    <span class="icon-button absolute right-2 top-2 h-8 w-8 rounded-md" aria-hidden="true">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0-2.49-2.02-4.5-4.5-4.5-1.72 0-3.22.96-3.98 2.38A4.49 4.49 0 0 0 8.5 3.75C6.02 3.75 4 5.76 4 8.25c0 7.22 8 11.5 8 11.5s9-4.28 9-11.5Z"/>
                                        </svg>
                                    </span>
                                    <div class="absolute inset-x-0 bottom-0 p-3">
                                        <h3 class="line-clamp-2 text-base font-black uppercase leading-tight text-white">{{ $game->title }}</h3>
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
                            <div class="store-panel rounded-lg p-8 text-gray-300 sm:col-span-2 lg:col-span-3 xl:col-span-6">Belum ada game yang lagi diskon.</div>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="store-panel h-fit rounded-lg p-6 xl:sticky xl:top-24">
                <div class="mb-6 flex items-center gap-3">
                    <span class="flex h-8 w-8 items-center justify-center text-[#ff7b22]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2c2.8 2.2 4.2 4.64 4.2 7.32 0 1.1-.25 2.03-.74 2.8.73-.27 1.48-.82 2.24-1.66.9 1.08 1.35 2.32 1.35 3.73A6.92 6.92 0 0 1 12 21a6.92 6.92 0 0 1-7.05-6.81c0-2.7 1.43-5.13 4.3-7.29-.2 1.56.1 2.8.92 3.72.86.96 1.97 1.32 3.35 1.08.72-2.22.21-5.46-1.52-9.7Z"/>
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-2xl font-black text-white">Popular Right Now</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($popularGames as $rank => $game)
                        @php
                            $discount = $discountFor($game);
                            $finalPrice = $finalPriceFor($game);
                            $purchaseCount = (int) ($game->paid_purchases_count ?? 0);
                        @endphp
                        <a href="{{ url('/game/' . $game->game_id) }}" class="popular-row grid grid-cols-[34px_118px_1fr] items-center gap-3 rounded-lg border border-transparent p-2.5 hover:border-[#2a475e] hover:bg-[#0f1923]/78">
                            <span class="text-center text-xl font-black text-[#66c0f4]/80">{{ $rank + 1 }}</span>
                            <span class="block overflow-hidden rounded-md">
                                <img src="{{ $imageFor($game) }}" alt="{{ $game->title }}" class="popular-thumb h-16 w-[118px] object-cover" loading="lazy" decoding="async">
                            </span>
                            <span class="min-w-0">
                                <span class="block truncate text-base font-black uppercase text-white">{{ $game->title }}</span>
                                <span class="mt-1 inline-flex items-center gap-1 rounded bg-[#0b2a44]/90 px-2 py-0.5 text-[11px] font-black uppercase text-[#66c0f4]">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h16M8 17v-5m4 5V8m4 9v-8"/>
                                    </svg>
                                    {{ number_format($purchaseCount, 0, ',', '.') }}
                                </span>
                                <span class="mt-2 flex flex-wrap items-center gap-2 text-sm">
                                    @if($discount > 0)
                                        <span class="price-discount rounded px-2 py-0.5 text-xs font-black">-{{ $discount }}%</span>
                                    @endif
                                    <span class="font-black text-white">{{ $formatPrice($finalPrice) }}</span>
                                </span>
                            </span>
                        </a>
                    @empty
                        <div class="rounded-lg border border-[#2a475e] bg-[#07111d]/80 p-5 text-sm text-gray-300">Belum ada game populer.</div>
                    @endforelse
                </div>

                <a href="{{ route('games.search', array_merge(request()->only('search', 'genre', 'category'), ['sort' => 'popular'])) }}" class="mt-7 flex items-center justify-center gap-2 rounded-lg border border-[#2a475e] px-4 py-3.5 text-center text-sm font-black text-[#58a9ff] transition hover:border-[#66c0f4] hover:bg-[#0f1923] hover:text-white">
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

            document.querySelectorAll('[data-showcase]').forEach((showcase) => {
                const tabs = Array.from(showcase.querySelectorAll('[data-showcase-tab]'));
                const panels = Array.from(showcase.querySelectorAll('[data-showcase-panel]'));

                const activatePanel = (key) => {
                    tabs.forEach((tab) => {
                        const isActive = tab.dataset.showcaseTabKey === key;
                        tab.classList.toggle('is-active', isActive);
                        tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    });

                    panels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.showcasePanelKey !== key);
                    });
                };

                panels.forEach((panel) => {
                    const items = Array.from(panel.querySelectorAll('[data-showcase-item]'));
                    const details = Array.from(panel.querySelectorAll('[data-showcase-detail]'));

                    const activateItem = (index) => {
                        items.forEach((item) => {
                            item.classList.toggle('is-active', Number(item.dataset.showcaseIndex) === index);
                        });

                        details.forEach((detail) => {
                            detail.classList.toggle('hidden', Number(detail.dataset.showcaseIndex) !== index);
                        });
                    };

                    items.forEach((item) => {
                        const index = Number(item.dataset.showcaseIndex);
                        item.addEventListener('mouseenter', () => activateItem(index));
                        item.addEventListener('focus', () => activateItem(index));
                    });

                    activateItem(0);
                });

                tabs.forEach((tab) => {
                    tab.addEventListener('click', () => {
                        activatePanel(tab.dataset.showcaseTabKey);
                    });
                });
            });

            document.querySelectorAll('[data-category-carousel]').forEach((carousel) => {
                const track = carousel.querySelector('[data-category-track]');
                const previous = carousel.querySelector('[data-category-prev]');
                const next = carousel.querySelector('[data-category-next]');
                const dotsContainer = carousel.querySelector('[data-category-dots]');
                let dots = Array.from(carousel.querySelectorAll('[data-category-dot]'));
                let activePage = 0;
                let scrollFrame = null;

                if (!track) {
                    return;
                }

                const cards = () => Array.from(track.children);
                const maxScroll = () => Math.max(track.scrollWidth - track.clientWidth, 0);
                const gapSize = () => Number.parseFloat(window.getComputedStyle(track).columnGap || '0') || 0;
                const visibleCardCount = () => {
                    const firstCard = cards()[0];

                    if (!firstCard) {
                        return 1;
                    }

                    const cardWidth = firstCard.getBoundingClientRect().width;
                    const step = Math.max(cardWidth + gapSize(), 1);

                    return Math.max(1, Math.floor((track.clientWidth + gapSize()) / step));
                };
                const pageTargets = () => {
                    const items = cards();

                    if (items.length === 0) {
                        return [0];
                    }

                    const visible = visibleCardCount();
                    const pages = Math.max(1, Math.ceil(items.length / visible));
                    const max = maxScroll();

                    return Array.from({ length: pages }, (_, index) => {
                        if (index === pages - 1) {
                            return max;
                        }

                        const card = items[Math.min(index * visible, items.length - 1)];

                        return Math.min(card.offsetLeft, max);
                    });
                };
                const renderDots = () => {
                    if (!dotsContainer) {
                        return;
                    }

                    const targets = pageTargets();
                    dotsContainer.classList.toggle('hidden', targets.length <= 1);

                    if (dots.length === targets.length) {
                        return;
                    }

                    dotsContainer.innerHTML = '';

                    targets.forEach((_, index) => {
                        const dot = document.createElement('button');
                        dot.type = 'button';
                        dot.className = 'category-dot';
                        dot.dataset.categoryDot = '';
                        dot.dataset.categoryPage = String(index);
                        dot.setAttribute('aria-label', `Lihat kategori halaman ${index + 1}`);
                        dot.addEventListener('click', () => scrollToPage(index));
                        dotsContainer.appendChild(dot);
                    });

                    dots = Array.from(dotsContainer.querySelectorAll('[data-category-dot]'));
                };
                const currentPage = () => {
                    const targets = pageTargets();

                    return targets.reduce((nearest, target, index) => {
                        return Math.abs(track.scrollLeft - target) < Math.abs(track.scrollLeft - targets[nearest])
                            ? index
                            : nearest;
                    }, 0);
                };
                const updateControls = () => {
                    renderDots();
                    activePage = currentPage();

                    dots.forEach((dot, index) => {
                        dot.classList.toggle('is-active', index === activePage);
                        dot.setAttribute('aria-current', index === activePage ? 'true' : 'false');
                    });

                    const atStart = track.scrollLeft <= 2;
                    const atEnd = track.scrollLeft >= maxScroll() - 2;

                    previous?.classList.toggle('is-disabled', atStart);
                    previous?.toggleAttribute('disabled', atStart);
                    next?.classList.toggle('is-disabled', atEnd);
                    next?.toggleAttribute('disabled', atEnd);
                };
                const scrollToPage = (index) => {
                    const targets = pageTargets();
                    const targetIndex = Math.max(0, Math.min(index, targets.length - 1));

                    track.scrollTo({
                        left: targets[targetIndex],
                        behavior: 'smooth',
                    });
                };

                previous?.addEventListener('click', () => {
                    scrollToPage(activePage - 1);
                });

                next?.addEventListener('click', () => {
                    scrollToPage(activePage + 1);
                });

                track.addEventListener('scroll', () => {
                    if (scrollFrame) {
                        window.cancelAnimationFrame(scrollFrame);
                    }

                    scrollFrame = window.requestAnimationFrame(updateControls);
                });

                window.addEventListener('resize', () => {
                    renderDots();
                    scrollToPage(Math.min(activePage, pageTargets().length - 1));
                    window.requestAnimationFrame(updateControls);
                });

                renderDots();
                updateControls();
            });
        });
    </script>
@endpush
