@php
    $discount = $game->discount_percent;
    $originalPrice = (float) $game->price;
    $finalPrice = $game->final_price;
    $displayPrice = $originalPrice <= 0 ? 'FREE' : 'Rp' . number_format($finalPrice, 0, ',', '.');

    $isPurchased = false;
    $isInCart = false;
    $isWishlisted = (bool) $isWishlisted;

    if (auth()->check()) {
        $userId = auth()->id();
        $isPurchased = \App\Models\Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
            ->where('payments.user_id', $userId)
            ->where('payment_items.game_id', $game->game_id)
            ->where('payments.status', \App\Models\Payment::STATUS_PAID)
            ->exists();
        $isInCart = \App\Models\Cart::where('user_id', $userId)
            ->where('game_id', $game->game_id)
            ->exists();
    }

    $mainImage = $game->detail?->header_image ?: $game->thumbnail_url;
    $galleryImages = collect([$mainImage, $game->thumbnail_url])
        ->merge($game->screenshots->pluck('url'))
        ->filter()
        ->unique()
        ->values();
    $primaryTrailer = $game->trailers->first();
    $negativeReviewPercentage = $totalReviews > 0 ? 100 - $reviewPercentage : 0;
    $reviewLabelId = match ($reviewLabel) {
        'Overwhelmingly Positive' => 'Luar Biasa Positif',
        'Very Positive' => 'Sangat Positif',
        'Mostly Positive' => 'Sebagian Besar Positif',
        'Mixed' => 'Bercampur',
        'Mostly Negative' => 'Sebagian Besar Negatif',
        'Very Negative' => 'Sangat Negatif',
        default => 'Belum Ada Ulasan',
    };
    $primaryGenre = $game->genres->first();
    $shortDescription = $game->detail?->short_description ?: \Illuminate\Support\Str::limit($game->description, 220);
    $featureItems = $game->categories->take(4);
    $recommendedRequirements = $game->detail?->recommended_requirements;

    $parseRequirements = static function (?string $requirements): array {
        if (blank($requirements)) {
            return ['requires_64_bit' => false, 'items' => []];
        }

        $text = preg_replace('/<br\s*\/?>/i', ' ', $requirements);
        $text = html_entity_decode(strip_tags($text), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace('/\s+/', ' ', trim($text));
        $text = preg_replace('/^(minimum|recommended)\s*:\s*/i', '', $text);

        $requires64Bit = str_contains(strtolower($text), 'requires a 64-bit processor and operating system');
        $text = preg_replace('/requires a 64-bit processor and operating system\s*/i', '', $text);

        $pattern = '/\b(OS|Processor|Memory|Graphics|DirectX|Network|Storage|Sound Card|Additional Notes)\s*:\s*/i';
        preg_match_all($pattern, $text, $matches, PREG_OFFSET_CAPTURE);

        $items = [];
        foreach ($matches[1] as $index => $match) {
            $fullMatch = $matches[0][$index];
            $valueStart = $fullMatch[1] + strlen($fullMatch[0]);
            $valueEnd = $matches[0][$index + 1][1] ?? strlen($text);
            $value = trim(substr($text, $valueStart, $valueEnd - $valueStart), " \t\n\r\0\x0B,;.");

            if ($value !== '') {
                $items[] = [
                    'label' => strtoupper($match[0]),
                    'value' => $value,
                ];
            }
        }

        if ($items === [] && $text !== '') {
            $items[] = ['label' => 'DETAIL', 'value' => $text];
        }

        return ['requires_64_bit' => $requires64Bit, 'items' => $items];
    };

    $minimumRequirements = $parseRequirements($game->detail?->minimum_requirements);
    $recommendedRequirementsList = $parseRequirements($recommendedRequirements);
    $minimumRequirementMap = collect($minimumRequirements['items'])->keyBy('label');
    $recommendedRequirementMap = collect($recommendedRequirementsList['items'])->keyBy('label');
    $requirementOrder = collect([
        'OS',
        'PROCESSOR',
        'MEMORY',
        'GRAPHICS',
        'DIRECTX',
        'NETWORK',
        'STORAGE',
        'SOUND CARD',
        'ADDITIONAL NOTES',
        'DETAIL',
    ]);
    $requirementLabels = $minimumRequirementMap->keys()
        ->merge($recommendedRequirementMap->keys())
        ->unique()
        ->sortBy(function ($label) use ($requirementOrder) {
            $position = $requirementOrder->search($label);

            return $position === false ? 99 : $position;
        })
        ->values();
    $consolidatedRequirements = $requirementLabels
        ->map(function ($label) use ($minimumRequirementMap, $recommendedRequirementMap) {
            $value = data_get($minimumRequirementMap->get($label), 'value')
                ?: data_get($recommendedRequirementMap->get($label), 'value');

            return $value ? ['label' => $label, 'value' => $value] : null;
        })
        ->filter()
        ->values();

    $popularTags = $game->genres
        ->map(fn ($tag) => [
            'name' => $tag->name,
            'count' => $tag->games_count ?? 0,
            'url' => route('games.search', ['genre' => $tag->genre_id]),
        ])
        ->concat($game->categories->map(fn ($tag) => [
            'name' => $tag->name,
            'count' => $tag->games_count ?? 0,
            'url' => route('games.search', ['category' => $tag->category_id]),
        ]))
        ->take(8);
@endphp

@extends('layouts.store')

@section('title', $game->title . ' - PlayMart')

@push('styles')
    <style>
        .game-panel {
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: #0b1420;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.18);
        }

        .game-hero-title {
            font-size: 24px;
        }

        @media (min-width: 769px) {
            .game-hero-title {
                font-size: 32px;
            }
        }

        @media (min-width: 1024px) {
            .game-hero-title {
                font-size: 40px;
            }
        }

        [data-gallery-thumb][data-active='true'] {
            border-color: #118dff;
            box-shadow: 0 0 0 1px #118dff, 0 8px 20px rgba(17, 141, 255, 0.22);
        }

        [data-gallery-thumb][data-active='true'] img {
            filter: brightness(1);
        }

        [data-gallery-thumb] img {
            filter: brightness(0.6);
            transition: filter 150ms ease, transform 150ms ease;
        }

        [data-gallery-thumb]:hover img {
            filter: brightness(1);
            transform: scale(1.03);
        }
    </style>
@endpush

@section('content')
    <div class="relative min-h-screen overflow-hidden bg-[#050a12] text-[#f8fafc]">
        <header class="relative isolate overflow-hidden border-b border-white/[0.08] bg-[#050a12]">
            <img
                src="{{ $mainImage }}"
                alt=""
                class="absolute inset-0 -z-30 h-full w-full scale-[1.01] object-cover object-center opacity-55 blur-[1px] saturate-[0.8] lg:object-[72%_center] lg:opacity-60"
            >
            <div class="absolute inset-0 -z-20 bg-gradient-to-r from-[#02060c] via-[#050a12]/95 to-[#050a12]/55"></div>
            <div class="absolute inset-0 -z-20 bg-gradient-to-t from-[#050a12] via-black/25 to-black/65"></div>
            <div class="absolute inset-0 -z-20 bg-black/5 backdrop-blur-[1px]"></div>
            <div class="absolute inset-y-0 left-0 -z-10 w-full bg-[radial-gradient(circle_at_18%_45%,rgba(79,124,255,0.18),transparent_30rem)]"></div>

            <div class="store-container flex min-h-[350px] max-w-[1500px] flex-col pb-10 pt-6 sm:min-h-[410px] sm:pb-12 sm:pt-8 lg:min-h-[460px] lg:pb-14">
                <nav class="flex w-fit max-w-full flex-wrap items-center gap-2 rounded-full border border-white/10 bg-black/35 px-3 py-2 text-[13px] font-medium text-[#f8fafc]/80 shadow-lg shadow-black/20 backdrop-blur-md" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="transition hover:text-white">All Games</a>
                    <span class="text-white/35">›</span>
                    @if($primaryGenre)
                        <a href="{{ route('games.search', ['genre' => $primaryGenre->genre_id]) }}" class="transition hover:text-white">{{ $primaryGenre->name }}</a>
                        <span class="text-white/35">›</span>
                    @endif
                    <span class="max-w-[55vw] truncate text-[#f8fafc]/85">{{ $game->title }}</span>
                </nav>

                <div class="mt-auto max-w-4xl pt-16 sm:pt-20 lg:pt-24">
                    <div class="flex flex-wrap items-center gap-2.5">
                        <span class="inline-flex items-center gap-2 rounded-full border border-[#118dff]/35 bg-[#118dff]/15 px-3 py-1.5 text-[9px] font-black uppercase tracking-[0.16em] text-[#66c0f4] backdrop-blur-sm">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span>
                            Verified Release
                        </span>
                        @if($primaryGenre)
                            <a href="{{ route('games.search', ['genre' => $primaryGenre->genre_id]) }}" class="rounded-full border border-white/15 bg-black/40 px-3 py-1.5 text-[9px] font-bold uppercase tracking-[0.14em] text-white/80 backdrop-blur-md transition hover:border-[#66c0f4]/40 hover:text-white">
                                {{ $primaryGenre->name }}
                            </a>
                        @endif
                    </div>

                    <h1 class="game-hero-title text-balance mt-5 max-w-4xl font-black uppercase leading-tight tracking-[-0.035em] text-[#f8fafc] drop-shadow-[0_8px_24px_rgba(0,0,0,0.75)]">
                        {{ $game->title }}
                    </h1>

                    <div class="mt-4 flex flex-wrap items-center gap-3 text-sm">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1.5 font-semibold text-emerald-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                            {{ $reviewPercentage }}%
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-red-400/20 bg-red-400/10 px-3 py-1.5 font-semibold text-red-300">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14V4h3v10h-3Zm0 0-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2l1-7a2 2 0 0 1 2-2h10" /></svg>
                            {{ $negativeReviewPercentage }}%
                        </span>
                        <span class="font-medium text-[#f8fafc]/80">{{ number_format($totalReviews) }} ulasan</span>
                    </div>

                    @if($shortDescription)
                        <p class="mt-4 hidden max-w-2xl text-sm font-medium leading-6 text-white/80 drop-shadow-[0_2px_8px_rgba(0,0,0,0.9)] sm:block lg:text-base">
                            {{ \Illuminate\Support\Str::limit($shortDescription, 150) }}
                        </p>
                    @endif

                    <div class="mt-6 inline-flex max-w-full flex-wrap items-center gap-x-4 gap-y-3 rounded-2xl border border-white/10 bg-[#07111d]/65 px-3 py-2.5 text-xs font-bold text-white/80 shadow-xl shadow-black/20 backdrop-blur-xl sm:px-4 sm:text-sm">
                        @if($game->developer)
                            <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="group inline-flex items-center gap-3 transition hover:text-white">
                                <img
                                    src="https://ui-avatars.com/api/?name={{ urlencode($game->developer->name) }}&background=22c55e&color=03120a&bold=true"
                                    alt="{{ $game->developer->name }}"
                                    class="h-8 w-8 rounded-lg border border-white/20 object-cover shadow-lg sm:h-9 sm:w-9"
                                >
                                <span class="group-hover:text-emerald-300">{{ $game->developer->name }}</span>
                            </a>
                        @else
                            <div class="inline-flex items-center gap-3">
                                <span class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/20 bg-emerald-500 text-[9px] font-black text-[#03120a]">DEV</span>
                                <span>Unknown Developer</span>
                            </div>
                        @endif

                        <span class="hidden h-5 w-px bg-white/15 sm:block"></span>
                        <span class="inline-flex items-center gap-2 text-white/50">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 2v3m8-3v3M3 9h18M5 4h14a2 2 0 0 1 2 2v14H3V6a2 2 0 0 1 2-2Z" /></svg>
                            {{ $game->release_date?->format('Y') ?? 'TBA' }}
                        </span>

                        @if($totalReviews > 0)
                            <span class="hidden h-5 w-px bg-white/15 sm:block"></span>
                            <a href="#reviews" class="inline-flex items-center gap-2 text-[#66c0f4] transition hover:text-white">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z" /></svg>
                                {{ number_format($totalReviews) }} reviews
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <main class="store-container relative z-10 max-w-[1500px] pb-36 pt-2 sm:pt-4 lg:pb-20">
            <div class="grid gap-x-4 gap-y-10 lg:grid-cols-[minmax(0,1fr)_340px] xl:grid-cols-[minmax(0,1fr)_390px]">
                <section class="game-panel order-1 min-w-0 overflow-hidden rounded-xl p-2 sm:p-3" aria-label="Game media gallery">
                    <div class="group relative aspect-video overflow-hidden rounded-lg bg-black">
                        <img
                            id="game-gallery-main"
                            src="{{ $mainImage }}"
                            alt="{{ $game->title }} preview"
                            class="h-full w-full object-cover transition duration-500"
                        >
                        <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-black/25 via-transparent to-black/10"></div>

                        @if($primaryTrailer)
                            <button
                                type="button"
                                data-open-trailer
                                class="absolute left-1/2 top-1/2 flex h-16 w-16 -translate-x-1/2 -translate-y-1/2 items-center justify-center rounded-full border border-white/20 bg-black/75 text-white shadow-2xl backdrop-blur transition hover:scale-110 hover:bg-[#118dff] sm:h-20 sm:w-20"
                                aria-label="Play trailer"
                            >
                                <svg class="ml-1 h-7 w-7 fill-current sm:h-9 sm:w-9" viewBox="0 0 24 24"><path d="M8 5v14l11-7z" /></svg>
                            </button>
                        @endif

                        <button type="button" data-zoom-current class="absolute right-3 top-3 flex h-10 w-10 items-center justify-center rounded-lg border border-white/10 bg-black/60 text-white opacity-100 backdrop-blur transition hover:bg-black/85 lg:opacity-0 lg:group-hover:opacity-100" aria-label="Perbesar gambar">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Zm0-11v7m-3.5-3.5h7" /></svg>
                        </button>
                    </div>

                    <div class="mt-2 grid grid-cols-[38px_minmax(0,1fr)_38px] gap-2 sm:grid-cols-[44px_minmax(0,1fr)_44px]">
                        <button type="button" data-gallery-prev class="flex items-center justify-center rounded-lg border border-white/[0.08] bg-[#0f1923] text-[#94a3b8] transition hover:border-[#118dff]/50 hover:text-white active:scale-95" aria-label="Gambar sebelumnya">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m15 19-7-7 7-7" /></svg>
                        </button>

                        <div class="no-scrollbar flex snap-x gap-2 overflow-x-auto" data-gallery-strip>
                            @foreach($galleryImages as $index => $image)
                                <button
                                    type="button"
                                    data-gallery-thumb
                                    data-gallery-image="{{ $image }}"
                                    data-gallery-index="{{ $index }}"
                                    data-active="{{ $index === 0 ? 'true' : 'false' }}"
                                    class="aspect-video w-[78vw] max-w-40 shrink-0 snap-start overflow-hidden rounded-md border-2 border-transparent bg-black transition sm:w-36"
                                    aria-label="Tampilkan gambar {{ $index + 1 }}"
                                >
                                    <img src="{{ $image }}" alt="" class="h-full w-full object-cover">
                                </button>
                            @endforeach
                        </div>

                        <button type="button" data-gallery-next class="flex items-center justify-center rounded-lg border border-white/[0.08] bg-[#0f1923] text-[#94a3b8] transition hover:border-[#118dff]/50 hover:text-white active:scale-95" aria-label="Gambar berikutnya">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m9 5 7 7-7 7" /></svg>
                        </button>
                    </div>

                    <div class="mt-3 flex items-center justify-center gap-3" aria-label="Posisi galeri">
                        <div class="flex items-center gap-1.5">
                            @foreach($galleryImages as $index => $image)
                                <button type="button" data-gallery-dot data-gallery-index="{{ $index }}" class="h-1.5 rounded-full transition-all duration-150 {{ $index === 0 ? 'w-5 bg-[#118dff]' : 'w-1.5 bg-white/20 hover:bg-white/45' }}" aria-label="Buka gambar {{ $index + 1 }}"></button>
                            @endforeach
                        </div>
                        <span id="gallery-counter" class="text-xs font-semibold tabular-nums text-[#94a3b8]">1/{{ $galleryImages->count() }}</span>
                    </div>
                </section>

                <div class="order-3 space-y-4 lg:col-start-2 lg:row-span-2 lg:row-start-1 lg:self-start">
                <aside class="game-panel overflow-hidden rounded-xl lg:max-h-[calc(100vh-48px)] lg:overflow-y-auto lg:sticky lg:top-6">
                    <img src="{{ $game->thumbnail_url }}" alt="{{ $game->title }}" class="aspect-video w-full object-cover">

                    <div class="p-4 sm:p-5">
                        <p class="text-sm leading-6 text-[#c5d2df]">{{ $shortDescription }}</p>

                        <dl class="mt-6 space-y-3 text-[13px]">
                            <div class="grid grid-cols-[118px_minmax(0,1fr)] gap-3">
                                <dt class="font-medium uppercase text-[#71879a]">Semua ulasan:</dt>
                                <dd>
                                    <a href="#reviews" class="font-semibold text-[#66c0f4] transition hover:text-white">{{ $reviewLabelId }}</a>
                                    <span class="text-[#8495a5]">({{ number_format($totalReviews, 0, ',', '.') }})</span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-[118px_minmax(0,1fr)] gap-3">
                                <dt class="font-medium uppercase text-[#71879a]">Rekomendasi:</dt>
                                <dd class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                        {{ $reviewPercentage }}%
                                    </span>
                                    <span class="text-[#8495a5]">{{ number_format($recommendedReviews, 0, ',', '.') }} menyukai</span>
                                </dd>
                            </div>
                            <div class="grid grid-cols-[118px_minmax(0,1fr)] gap-3 pt-2">
                                <dt class="font-medium uppercase text-[#71879a]">Tanggal rilis:</dt>
                                <dd class="font-medium text-[#aebdca]">{{ $game->release_date?->translatedFormat('d M Y') ?? 'Segera hadir' }}</dd>
                            </div>
                            <div class="grid grid-cols-[118px_minmax(0,1fr)] gap-3 pt-2">
                                <dt class="font-medium uppercase text-[#71879a]">Pengembang:</dt>
                                <dd>
                                    @if($game->developer)
                                        <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="font-semibold text-[#66c0f4] transition hover:text-white">{{ $game->developer->name }}</a>
                                    @else
                                        <span class="text-[#aebdca]">-</span>
                                    @endif
                                </dd>
                            </div>
                            <div class="grid grid-cols-[118px_minmax(0,1fr)] gap-3">
                                <dt class="font-medium uppercase text-[#71879a]">Penerbit:</dt>
                                <dd>
                                    @if($game->publisher)
                                        <a href="{{ route('games.search', ['publisher' => $game->publisher->publisher_id]) }}" class="font-semibold text-[#66c0f4] transition hover:text-white">{{ $game->publisher->name }}</a>
                                    @else
                                        <span class="text-[#aebdca]">-</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-7 border-t border-white/10 pt-5">
                            <div class="flex flex-wrap items-end justify-between gap-3">
                                <div>
                                    @if($discount > 0)
                                        <div class="mb-1 flex items-center gap-2">
                                            <span class="rounded bg-[#4c6b22] px-2 py-1 text-xs font-black text-[#beee11]">-{{ $discount }}%</span>
                                            <span class="text-xs text-slate-500 line-through">Rp{{ number_format($originalPrice, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    <p class="text-3xl font-black tracking-tight text-[#f8fafc]">{{ $displayPrice }}</p>
                                </div>
                            </div>

                            <div class="mt-4 grid gap-3">
                        @if($isPurchased)
                            <span class="flex min-h-[52px] items-center justify-center rounded-lg border border-emerald-400/25 bg-emerald-400/10 px-5 text-sm font-black uppercase tracking-wider text-emerald-300">Sudah dimiliki</span>
                        @elseif($isInCart)
                            <a href="{{ route('cart.index') }}" class="flex min-h-[54px] items-center justify-center rounded-lg bg-gradient-to-r from-[#118dff] to-[#66c0f4] px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#118dff]/20 transition hover:-translate-y-0.5 hover:brightness-110 active:translate-y-0 active:scale-[0.98]">Lihat Cart</a>
                        @else
                            <form action="{{ route('cart.add', $game->game_id) }}" method="POST">
                                @csrf
                                <button class="flex min-h-[54px] w-full items-center justify-center rounded-lg bg-gradient-to-r from-[#118dff] to-[#66c0f4] px-5 py-3.5 text-sm font-bold text-white shadow-lg shadow-[#118dff]/20 transition hover:-translate-y-0.5 hover:brightness-110 active:translate-y-0 active:scale-[0.98]">
                                    <svg class="mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l2.1 11.1a2 2 0 0 0 2 1.65h7.9a2 2 0 0 0 1.96-1.6L20 8H6m3 12.25h.01m7.99 0h.01" /></svg>
                                    Add to Cart
                                </button>
                            </form>
                        @endif

                        @auth
                            <form action="{{ route('wishlist.toggle', $game) }}" method="POST">
                                @csrf
                                <button class="flex min-h-[50px] w-full items-center justify-center rounded-lg border border-white/15 bg-transparent px-5 py-3 text-sm font-semibold text-[#f8fafc] transition hover:border-[#118dff]/60 hover:bg-[#118dff]/10 hover:text-white active:scale-[0.98] {{ $isWishlisted ? 'border-rose-400/40 bg-rose-400/10 text-rose-200' : '' }}">
                                    <svg class="mr-2 h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z" /></svg>
                                    {{ $isWishlisted ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}
                                </button>
                            </form>
                        @endauth
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="grid content-start gap-4">
                    <section class="game-panel rounded-xl p-5">
                        <h2 class="text-lg font-semibold text-[#f8fafc]">Popular Tags</h2>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($popularTags as $tag)
                                <a href="{{ $tag['url'] }}" class="rounded-lg border border-white/[0.08] bg-[#0f1923] px-3 py-2 text-xs font-semibold text-[#94a3b8] transition hover:border-[#118dff]/35 hover:bg-[#118dff]/15 hover:text-[#f8fafc]">
                                    {{ $tag['name'] }} ({{ number_format($tag['count']) }})
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <section class="game-panel rounded-xl p-5">
                        <h2 class="text-lg font-semibold text-[#f8fafc]">User Reviews</h2>
                        @if($totalReviews > 0)
                            <div class="mt-4 flex items-baseline gap-2">
                                <span class="font-bold text-[#66c0f4]">{{ $reviewLabelId }}</span>
                                <span class="text-xs text-[#94a3b8]">({{ number_format($totalReviews) }} ulasan)</span>
                            </div>
                            <div class="mt-5 space-y-3 text-xs">
                                <div class="flex items-center gap-3">
                                    <span class="flex w-20 items-center gap-1.5 text-emerald-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                        Like
                                    </span>
                                    <span class="h-1.5 flex-1 overflow-hidden rounded-full bg-white/5"><span class="block h-full bg-emerald-400" style="width: {{ $reviewPercentage }}%"></span></span>
                                    <span class="w-9 text-right text-[#94a3b8]">{{ $reviewPercentage }}%</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <span class="flex w-20 items-center gap-1.5 text-red-300">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14V4h3v10h-3Zm0 0-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2l1-7a2 2 0 0 1 2-2h10" /></svg>
                                        Dislike
                                    </span>
                                    <span class="h-1.5 flex-1 overflow-hidden rounded-full bg-white/5"><span class="block h-full bg-[#ef4444]" style="width: {{ $negativeReviewPercentage }}%"></span></span>
                                    <span class="w-9 text-right text-[#94a3b8]">{{ $negativeReviewPercentage }}%</span>
                                </div>
                            </div>
                            <a href="#reviews" class="mt-5 inline-flex text-xs font-bold text-[#66c0f4] hover:text-white">Baca Ulasan</a>
                        @else
                            <div class="mt-5 rounded-xl border border-dashed border-white/10 bg-[#0f1923]/60 p-5 text-center">
                                <div class="mx-auto flex w-fit items-center gap-3 text-[#94a3b8]">
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                    <span class="h-7 w-px bg-white/10"></span>
                                    <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 14V4h3v10h-3Zm0 0-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2l1-7a2 2 0 0 1 2-2h10" /></svg>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-[#94a3b8]">Belum ada ulasan untuk game ini.</p>
                                @if($canReview)
                                    <a href="#simple-review-form" class="mt-4 inline-flex rounded-lg border border-[#118dff]/40 bg-[#118dff]/10 px-4 py-2 text-xs font-bold text-[#66c0f4] transition hover:bg-[#118dff]/20 hover:text-white">Tulis Ulasan</a>
                                @endif
                            </div>
                        @endif
                    </section>
                </div>
                </div>

                <div class="order-2 min-w-0 lg:col-start-1 lg:row-start-2">
                    <section class="game-panel rounded-xl p-5 sm:p-6">
                        <div>
                            <h2 class="text-lg font-semibold text-[#f8fafc] sm:text-xl">About This Game</h2>
                            <p class="mt-4 text-sm font-medium leading-7 text-[#f8fafc]/80 sm:text-[15px]">{{ $shortDescription }}</p>
                            @if($shortDescription !== $game->description)
                                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-[#94a3b8] sm:text-[15px]">{{ $game->description }}</p>
                            @endif
                        </div>

                        <div class="mt-8 border-t border-white/[0.08] pt-8">
                            <h2 class="text-lg font-semibold text-[#f8fafc] sm:text-xl">Key Features</h2>
                            <div class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                                @forelse($featureItems as $index => $feature)
                                    <article class="border-white/10 lg:border-r lg:pr-4 lg:last:border-0">
                                        <div class="flex items-center gap-2 text-[#66c0f4]">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-full border border-[#118dff]/30 bg-[#118dff]/10">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            </span>
                                            <h3 class="text-[10px] font-black uppercase tracking-wide text-slate-200">{{ $feature->name }}</h3>
                                        </div>
                                        <p class="mt-3 text-sm leading-6 text-[#94a3b8]">Nikmati fitur {{ strtolower($feature->name) }} yang terintegrasi dalam pengalaman bermain.</p>
                                    </article>
                                @empty
                                    @foreach($game->genres->take(4) as $genre)
                                        <article>
                                            <h3 class="text-xs font-black uppercase text-slate-200">{{ $genre->name }}</h3>
                                            <p class="mt-2 text-xs leading-5 text-slate-500">Pengalaman {{ strtolower($genre->name) }} yang dirancang untuk pemain PlayMart.</p>
                                        </article>
                                    @endforeach
                                @endforelse
                            </div>
                        </div>

                        @if($game->detail?->minimum_requirements || $recommendedRequirements)
                            <div class="mt-8 border-t border-white/[0.08] pt-8">
                                <h2 class="text-lg font-semibold text-[#f8fafc] sm:text-xl">System Requirements</h2>

                                @if($minimumRequirements['requires_64_bit'] || $recommendedRequirementsList['requires_64_bit'])
                                    <p class="mt-4 text-[10px] font-medium text-slate-500">Requires a 64-bit processor and operating system.</p>
                                @endif

                                <dl class="mt-5 grid gap-x-8 gap-y-3 sm:grid-cols-2">
                                    @foreach($consolidatedRequirements as $requirement)
                                        <div class="grid min-w-0 grid-cols-[96px_minmax(0,1fr)] gap-3 border-b border-white/[0.05] pb-3">
                                            <dt class="text-[11px] font-bold uppercase leading-5 text-[#94a3b8]">{{ $requirement['label'] }}</dt>
                                            <dd class="min-w-0 break-words text-[13px] font-medium leading-5 text-[#f8fafc]/75">{{ $requirement['value'] }}</dd>
                                        </div>
                                    @endforeach
                                </dl>
                            </div>
                        @endif
                    </section>
                </div>
            </div>

            <section id="reviews" class="game-panel mt-10 scroll-mt-28 rounded-xl p-5 sm:p-6 lg:p-8" data-review-root data-reviews-url="{{ route('games.reviews.index', $game) }}">
                <div class="flex flex-col gap-3 border-b border-white/10 pb-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-[#66c0f4]">Community</p>
                        <h2 class="mt-1 text-xl font-semibold text-[#f8fafc]">Player Reviews</h2>
                    </div>
                    @if($totalReviews > 0)
                        <div class="text-left sm:text-right">
                            <p class="flex items-center gap-2 text-2xl font-black text-emerald-300 sm:justify-end">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                {{ $reviewPercentage }}%
                            </p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Menyukai game ini</p>
                        </div>
                    @endif
                </div>

                <div class="mt-6 space-y-8">
                    <div>
                        @auth
                            @if($canReview)
                                <form id="simple-review-form" class="rounded-xl border border-[#118dff]/25 bg-[#118dff]/5 p-4 sm:p-5 lg:p-6">
                                    @csrf
                                    <div class="flex flex-col gap-1">
                                        <h3 class="text-lg font-bold text-white">Bagikan pengalamanmu</h3>
                                        <p class="text-sm text-[#94a3b8]">Pilih pendapatmu lalu tuliskan ulasan tentang game ini.</p>
                                    </div>

                                    <div class="mt-5 flex flex-wrap gap-3">
                                        <div>
                                            <input type="radio" class="peer sr-only" name="is_recommended" id="rec-yes" value="1" checked>
                                            <label for="rec-yes" class="flex min-h-11 min-w-32 cursor-pointer items-center justify-center gap-2 rounded-lg border border-white/10 bg-black/20 px-5 text-xs font-bold text-slate-500 transition hover:border-emerald-400/25 hover:text-slate-300 peer-checked:border-emerald-400/50 peer-checked:bg-emerald-400/10 peer-checked:text-emerald-300">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                                Like
                                            </label>
                                        </div>
                                        <div>
                                            <input type="radio" class="peer sr-only" name="is_recommended" id="rec-no" value="0">
                                            <label for="rec-no" class="flex min-h-11 min-w-32 cursor-pointer items-center justify-center gap-2 rounded-lg border border-white/10 bg-black/20 px-5 text-xs font-bold text-slate-500 transition hover:border-red-400/25 hover:text-slate-300 peer-checked:border-red-400/50 peer-checked:bg-red-400/10 peer-checked:text-red-300">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14V4h3v10h-3Zm0 0-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2l1-7a2 2 0 0 1 2-2h10" /></svg>
                                                Dislike
                                            </label>
                                        </div>
                                    </div>

                                    <div class="relative mt-4">
                                        <textarea name="body" data-review-body maxlength="500" class="form-field min-h-32 resize-y pb-9 text-sm" placeholder="Tulis review minimal 5 karakter..." required minlength="5"></textarea>
                                        <span data-review-counter class="pointer-events-none absolute bottom-3 right-3 text-[11px] font-medium tabular-nums text-[#71879a]">0/500</span>
                                    </div>

                                    <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <p class="flex items-center gap-2 text-xs leading-5 text-[#94a3b8]">
                                            <svg class="h-4 w-4 shrink-0 text-[#66c0f4]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9h.01M11 12h1v4h1m8-4a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                            Ulasanmu akan membantu pemain lain dalam memutuskan.
                                        </p>
                                        <button type="submit" class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-[#118dff] to-[#0aa7ff] px-6 py-3 text-xs font-bold text-white shadow-lg shadow-[#118dff]/20 transition hover:brightness-110 active:scale-[0.98] sm:w-auto">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m22 2-7 20-4-9-9-4 20-7Zm-11 11L22 2" /></svg>
                                            Posting Review
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="rounded-xl border border-dashed border-white/10 p-6 text-sm leading-6 text-slate-500">Beli game ini terlebih dahulu untuk menulis review.</div>
                            @endif
                        @else
                            <div class="rounded-xl border border-dashed border-white/10 p-6 text-sm leading-6 text-slate-500">
                                Login untuk menulis review.
                                <a href="{{ route('login') }}" class="mt-4 block font-bold text-[#66c0f4]">Login sekarang</a>
                            </div>
                        @endauth
                    </div>

                    <div class="border-t border-white/10 pt-7">
                        <div class="mb-5 flex flex-col gap-4 rounded-xl border border-white/10 bg-[#07111d]/65 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex flex-wrap items-center gap-2" role="group" aria-label="Filter ulasan">
                                <span class="mr-1 text-xs font-semibold text-[#94a3b8]">Filter:</span>
                                <button type="button" data-review-filter="all" data-active="true" class="rounded-lg border border-[#118dff]/50 bg-[#118dff]/10 px-4 py-2 text-xs font-semibold text-[#66c0f4] transition hover:border-[#66c0f4]">Semua</button>
                                <button type="button" data-review-filter="positive" data-active="false" class="rounded-lg border border-white/10 bg-white/[0.02] px-4 py-2 text-xs font-semibold text-[#94a3b8] transition hover:border-emerald-400/30 hover:text-emerald-300">Positif</button>
                                <button type="button" data-review-filter="negative" data-active="false" class="rounded-lg border border-white/10 bg-white/[0.02] px-4 py-2 text-xs font-semibold text-[#94a3b8] transition hover:border-red-400/30 hover:text-red-300">Negatif</button>
                            </div>

                            <label class="flex items-center gap-3 text-xs font-semibold text-[#94a3b8]">
                                Urutkan:
                                <select data-review-sort class="rounded-lg border border-white/10 bg-[#0f1923] px-3 py-2 text-xs text-[#d8e3ec] outline-none transition focus:border-[#118dff]">
                                    <option value="latest">Terbaru</option>
                                    <option value="oldest">Terlama</option>
                                </select>
                            </label>
                        </div>

                        <div id="review-list-container" class="space-y-4">
                            <div class="rounded-xl border border-dashed border-white/10 p-8 text-center text-sm text-slate-500">Memuat review...</div>
                        </div>
                    </div>
                </div>
            </section>

            @if($relatedGames->isNotEmpty())
                <section class="game-panel mt-10 rounded-xl p-5 sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <h2 class="text-lg font-semibold text-[#f8fafc] sm:text-xl">Related Games</h2>
                        <a href="{{ route('games.search', $primaryGenre ? ['genre' => $primaryGenre->genre_id] : []) }}" class="text-xs font-bold text-[#66c0f4] hover:text-white">Browse all</a>
                    </div>
                    <div class="mt-5 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($relatedGames as $relatedGame)
                            @php
                                $relatedLikePercentage = ($relatedGame->reviews_count ?? 0) > 0
                                    ? (int) round(($relatedGame->recommendation_ratio ?? 0) * 100)
                                    : 0;
                                $relatedBadge = $relatedGame->discount_percent > 0
                                    ? 'Sale -' . $relatedGame->discount_percent . '%'
                                    : ($relatedGame->release_date?->gte(now()->subDays(90)) ? 'New' : null);
                            @endphp
                            <a href="{{ url('/game/' . $relatedGame->game_id) }}" class="group min-w-0 rounded-xl border border-white/[0.08] bg-[#0f1923] p-3 transition duration-200 hover:-translate-y-1 hover:border-[#118dff]/25 hover:shadow-[0_14px_30px_rgba(0,0,0,0.3)]">
                                <div class="relative aspect-video overflow-hidden rounded-lg border border-white/[0.08] bg-black">
                                    <img src="{{ $relatedGame->thumbnail_url }}" alt="{{ $relatedGame->title }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                                    @if($relatedBadge)
                                        <span class="absolute left-2 top-2 rounded-md bg-[#118dff] px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-white shadow-lg">{{ $relatedBadge }}</span>
                                    @elseif(($relatedGame->paid_purchases_count ?? 0) >= 10)
                                        <span class="absolute left-2 top-2 rounded-md bg-[#f59e0b] px-2 py-1 text-[9px] font-bold uppercase tracking-wide text-black shadow-lg">Best Seller</span>
                                    @endif
                                </div>
                                <h3 class="mt-3 truncate text-sm font-bold text-[#f8fafc]">{{ $relatedGame->title }}</h3>
                                <div class="mt-2 flex items-center justify-between gap-2">
                                    <p class="text-xs font-bold text-[#66c0f4]">{{ (float) $relatedGame->price <= 0 ? 'FREE' : 'Rp' . number_format($relatedGame->final_price, 0, ',', '.') }}</p>
                                    <span class="flex items-center gap-1 text-[11px] font-semibold text-emerald-300">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                        {{ $relatedLikePercentage }}%
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

    </div>

    <div class="fixed inset-x-0 bottom-16 z-[9998] border-y border-white/[0.08] bg-[#0b1420]/95 px-4 py-3 shadow-[0_-12px_35px_rgba(0,0,0,0.45)] backdrop-blur-xl sm:inset-x-3 sm:bottom-24 sm:rounded-xl sm:border lg:hidden">
        <div class="mx-auto flex max-w-xl items-center gap-4">
            <div class="min-w-0 flex-1">
                <p class="truncate text-[11px] font-medium text-[#94a3b8]">{{ $game->title }}</p>
                <p class="mt-0.5 text-lg font-black tracking-tight text-[#f8fafc]">{{ $displayPrice }}</p>
            </div>

            @if($isPurchased)
                <span class="flex min-h-12 shrink-0 items-center rounded-lg border border-emerald-400/25 bg-emerald-400/10 px-5 text-xs font-bold text-emerald-300">Sudah dimiliki</span>
            @elseif($isInCart)
                <a href="{{ route('cart.index') }}" class="flex min-h-12 shrink-0 items-center justify-center rounded-lg bg-gradient-to-r from-[#118dff] to-[#66c0f4] px-6 text-sm font-bold text-white shadow-lg shadow-[#118dff]/20 transition active:scale-[0.97]">Lihat Cart</a>
            @else
                <form action="{{ route('cart.add', $game->game_id) }}" method="POST" class="shrink-0">
                    @csrf
                    <button class="flex min-h-12 items-center justify-center rounded-lg bg-gradient-to-r from-[#118dff] to-[#66c0f4] px-6 text-sm font-bold text-white shadow-lg shadow-[#118dff]/20 transition active:scale-[0.97]">Add to Cart</button>
                </form>
            @endif
        </div>
    </div>

    <div id="image-zoom-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/95 p-4 sm:p-8" data-image-modal>
        <button type="button" data-close-image class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white" aria-label="Tutup gambar">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
        <img id="image-zoom-target" src="" alt="Game preview" class="max-h-[90vh] max-w-full rounded-xl object-contain shadow-2xl">
    </div>

    @if($primaryTrailer)
        <div id="trailer-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center bg-black/95 p-4 sm:p-8" data-trailer-modal>
            <button type="button" data-close-trailer class="absolute right-4 top-4 flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/10 text-white" aria-label="Tutup trailer">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
            @if($primaryTrailer->embed_url)
                <iframe data-trailer-frame data-src="{{ $primaryTrailer->embed_url }}" title="{{ $game->title }} trailer" class="aspect-video w-full max-w-5xl rounded-xl border border-white/10 bg-black" allow="autoplay; fullscreen" allowfullscreen></iframe>
            @else
                <a href="{{ $primaryTrailer->url }}" target="_blank" rel="noopener" class="btn-primary">Buka trailer</a>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainImage = document.getElementById('game-gallery-main');
            const thumbs = Array.from(document.querySelectorAll('[data-gallery-thumb]'));
            const dots = Array.from(document.querySelectorAll('[data-gallery-dot]'));
            const counter = document.getElementById('gallery-counter');
            let activeIndex = 0;
            let touchStartX = 0;

            const selectImage = (index) => {
                if (!thumbs.length) return;
                activeIndex = (index + thumbs.length) % thumbs.length;
                const activeThumb = thumbs[activeIndex];
                mainImage.src = activeThumb.dataset.galleryImage;
                thumbs.forEach((thumb, thumbIndex) => {
                    thumb.dataset.active = thumbIndex === activeIndex ? 'true' : 'false';
                });
                dots.forEach((dot, dotIndex) => {
                    const isActive = dotIndex === activeIndex;
                    dot.classList.toggle('w-5', isActive);
                    dot.classList.toggle('bg-[#118dff]', isActive);
                    dot.classList.toggle('w-1.5', !isActive);
                    dot.classList.toggle('bg-white/20', !isActive);
                });
                if (counter) counter.textContent = `${activeIndex + 1}/${thumbs.length}`;
                activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            };

            thumbs.forEach((thumb, index) => thumb.addEventListener('click', () => selectImage(index)));
            dots.forEach((dot, index) => dot.addEventListener('click', () => selectImage(index)));
            document.querySelector('[data-gallery-prev]')?.addEventListener('click', () => selectImage(activeIndex - 1));
            document.querySelector('[data-gallery-next]')?.addEventListener('click', () => selectImage(activeIndex + 1));
            mainImage?.addEventListener('touchstart', (event) => {
                touchStartX = event.changedTouches[0].clientX;
            }, { passive: true });
            mainImage?.addEventListener('touchend', (event) => {
                const distance = event.changedTouches[0].clientX - touchStartX;
                if (Math.abs(distance) < 45) return;
                selectImage(distance < 0 ? activeIndex + 1 : activeIndex - 1);
            }, { passive: true });

            const imageModal = document.querySelector('[data-image-modal]');
            const imageTarget = document.getElementById('image-zoom-target');
            const closeImage = () => {
                imageModal?.classList.add('hidden');
                imageModal?.classList.remove('flex');
                document.body.style.overflow = '';
            };

            window.openImageZoom = (url) => {
                imageTarget.src = url;
                imageModal?.classList.remove('hidden');
                imageModal?.classList.add('flex');
                document.body.style.overflow = 'hidden';
            };

            document.querySelector('[data-zoom-current]')?.addEventListener('click', () => window.openImageZoom(mainImage.src));
            document.querySelector('[data-close-image]')?.addEventListener('click', closeImage);
            imageModal?.addEventListener('click', (event) => {
                if (event.target === imageModal) closeImage();
            });

            const trailerModal = document.querySelector('[data-trailer-modal]');
            const trailerFrame = document.querySelector('[data-trailer-frame]');
            const closeTrailer = () => {
                trailerModal?.classList.add('hidden');
                trailerModal?.classList.remove('flex');
                if (trailerFrame) trailerFrame.src = '';
                document.body.style.overflow = '';
            };

            document.querySelector('[data-open-trailer]')?.addEventListener('click', () => {
                if (!trailerModal) return;
                trailerModal.classList.remove('hidden');
                trailerModal.classList.add('flex');
                if (trailerFrame) trailerFrame.src = trailerFrame.dataset.src;
                document.body.style.overflow = 'hidden';
            });
            document.querySelector('[data-close-trailer]')?.addEventListener('click', closeTrailer);
            trailerModal?.addEventListener('click', (event) => {
                if (event.target === trailerModal) closeTrailer();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    closeImage();
                    closeTrailer();
                }
            });

            const reviewRoot = document.querySelector('[data-review-root]');
            if (!reviewRoot) return;

            const reviewList = document.getElementById('review-list-container');
            const canReview = @js($canReview);
            const escapeHtml = (value) => String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');

            const filterButtons = Array.from(document.querySelectorAll('[data-review-filter]'));
            const sortSelect = document.querySelector('[data-review-sort]');
            let loadedReviews = [];
            let activeReviewFilter = 'all';
            let activeReviewSort = 'latest';

            const emptyReviewsMarkup = (isFiltered = false) => {
                const writeReview = canReview && !isFiltered
                    ? '<a href="#simple-review-form" class="mt-6 inline-flex rounded-lg border border-[#118dff]/50 bg-[#118dff]/10 px-5 py-3 text-xs font-bold text-[#66c0f4] transition hover:bg-[#118dff]/20 hover:text-white">Tulis Ulasan Pertama</a>'
                    : '';
                const title = isFiltered ? 'Tidak ada ulasan pada filter ini' : 'Belum ada ulasan untuk game ini';
                const description = isFiltered
                    ? 'Coba pilih filter lain untuk melihat ulasan pemain.'
                    : 'Beli game ini dan jadilah yang pertama membagikan pengalamanmu!';

                return `
                    <div class="rounded-xl border border-white/10 bg-[#07111d]/55 px-5 py-16 text-center sm:py-20">
                        <div class="relative mx-auto h-24 w-28 text-[#71879a]">
                            <svg class="absolute right-1 top-0 h-16 w-16 text-[#55718d]" fill="currentColor" viewBox="0 0 24 24"><path d="M4 3h14a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-2l-4 4v-4H8a4 4 0 0 1-4-4V3Zm4 5h9V6H8v2Zm0 4h6v-2H8v2Z" /></svg>
                            <svg class="absolute bottom-0 left-1 h-14 w-14 text-[#8ca0b5]" fill="currentColor" viewBox="0 0 24 24"><path d="M3 5h14a3 3 0 0 1 3 3v7a3 3 0 0 1-3 3h-6l-4 4v-4H6a3 3 0 0 1-3-3V5Zm4 5h2V8H7v2Zm4 0h6V8h-6v2Zm-4 4h7v-2H7v2Z" /></svg>
                            <svg class="absolute right-0 top-8 h-6 w-6 text-[#66c0f4]" fill="currentColor" viewBox="0 0 24 24"><path d="m12 2 1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8L12 2Z" /></svg>
                        </div>
                        <h3 class="mt-5 text-lg font-bold text-[#f8fafc]">${title}</h3>
                        <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-[#94a3b8]">${description}</p>
                        ${writeReview}
                    </div>`;
            };

            const renderReviews = () => {
                let reviews = loadedReviews.filter((review) => {
                    if (activeReviewFilter === 'positive') return review.is_recommended;
                    if (activeReviewFilter === 'negative') return !review.is_recommended;
                    return true;
                });

                reviews = [...reviews].sort((first, second) => activeReviewSort === 'oldest'
                    ? Number(first.id) - Number(second.id)
                    : Number(second.id) - Number(first.id));

                if (!reviews.length) {
                    reviewList.innerHTML = emptyReviewsMarkup(loadedReviews.length > 0);
                    return;
                }

                reviewList.innerHTML = reviews.map((review) => {
                        const tone = review.is_recommended
                            ? 'bg-[#1f6b50] text-emerald-100'
                            : 'bg-[#71353d] text-red-200';
                        const label = review.is_recommended ? 'Direkomendasikan' : 'Tidak Direkomendasikan';
                        const reviewIcon = review.is_recommended
                            ? '<svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10v10H4V10h3Zm2 10V9.45L12.64 3a1 1 0 0 1 1.86.5V8H19a2 2 0 0 1 1.96 2.4l-1.4 7A2 2 0 0 1 17.6 19H9v1Z" /></svg>'
                            : '<svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24"><path d="M17 14V4h3v10h-3Zm-2-10v10.55L11.36 21a1 1 0 0 1-1.86-.5V16H5a2 2 0 0 1-1.96-2.4l1.4-7A2 2 0 0 1 6.4 5H15V4Z" /></svg>';
                        const removeButton = review.is_owner
                            ? `<button type="button" onclick="deleteReview(${Number(review.id)})" class="rounded-md border border-red-400/20 px-2.5 py-1.5 text-[10px] font-bold text-red-300 transition hover:bg-red-400/10 hover:text-red-200">Hapus</button>`
                            : '';

                        return `
                            <article class="overflow-hidden rounded-lg border border-white/10 bg-[#111c2b] shadow-md shadow-black/10 sm:grid sm:grid-cols-[150px_minmax(0,1fr)]">
                                <aside class="border-b border-white/[0.06] bg-[#152334] p-3 sm:border-b-0 sm:border-r">
                                    <div class="flex items-center gap-2.5 sm:items-start">
                                        <img src="${escapeHtml(review.avatar_url)}" alt="Avatar ${escapeHtml(review.user_name)}" class="h-11 w-11 shrink-0 rounded-md border border-white/10 object-cover shadow-sm">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-bold text-[#8fb3cf]">${escapeHtml(review.user_name)}</p>
                                            <p class="mt-0.5 text-[11px] font-medium text-[#7890a6]">${Number(review.user_reviews_count)} ulasan</p>
                                        </div>
                                    </div>
                                </aside>

                                <div class="min-w-0">
                                    <header class="flex min-h-14 items-stretch bg-[#0c1724]">
                                        <div class="flex w-14 shrink-0 items-center justify-center ${tone}">${reviewIcon}</div>
                                        <div class="flex min-w-0 flex-1 items-center justify-between gap-3 px-4 py-2.5">
                                            <div class="min-w-0">
                                                <h3 class="truncate text-base font-semibold text-[#d8e3ec] sm:text-lg">${label}</h3>
                                                <p class="text-[11px] text-[#7890a6]">Ulasan komunitas PlayMart</p>
                                            </div>
                                            ${removeButton}
                                        </div>
                                    </header>

                                    <div class="p-4">
                                        <p class="text-[10px] font-bold uppercase tracking-wide text-[#657c91]">Diposting: ${escapeHtml(review.posted_at)}</p>
                                        <p class="mt-2.5 whitespace-pre-line break-words text-sm leading-6 text-[#b7c3cf]">${escapeHtml(review.body)}</p>

                                        <div class="mt-3 flex flex-wrap items-center gap-x-3 gap-y-2 border-t border-white/15 pt-3" data-helpful-panel>
                                            <p class="mr-1 text-xs font-medium text-[#7890a6]">Membantu?</p>
                                            <div class="flex flex-wrap items-center gap-2">
                                                <button type="button" data-helpful-choice="yes" class="inline-flex items-center gap-1.5 rounded-md bg-[#1c2b3c] px-2.5 py-1.5 text-[11px] font-semibold text-[#7890a6] transition hover:bg-[#263a50] hover:text-[#66c0f4] active:scale-95">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 10v10H4V10h3Zm0 0 4-7a2 2 0 0 1 2 2v4h5a2 2 0 0 1 2 2l-1 7a2 2 0 0 1-2 2H7" /></svg>
                                                    Ya
                                                </button>
                                                <button type="button" data-helpful-choice="no" class="inline-flex items-center gap-1.5 rounded-md bg-[#1c2b3c] px-2.5 py-1.5 text-[11px] font-semibold text-[#7890a6] transition hover:bg-[#263a50] hover:text-red-300 active:scale-95">
                                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14V4h3v10h-3Zm0 0-4 7a2 2 0 0 1-2-2v-4H6a2 2 0 0 1-2-2l1-7a2 2 0 0 1 2-2h10" /></svg>
                                                    Tidak
                                                </button>
                                                <span data-helpful-feedback class="hidden text-[11px] font-medium text-emerald-300">Terima kasih.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>`;
                }).join('');
            };

            const updateFilterButtons = () => {
                filterButtons.forEach((button) => {
                    const isActive = button.dataset.reviewFilter === activeReviewFilter;
                    button.dataset.active = isActive ? 'true' : 'false';
                    button.classList.toggle('border-[#118dff]/50', isActive);
                    button.classList.toggle('bg-[#118dff]/10', isActive);
                    button.classList.toggle('text-[#66c0f4]', isActive);
                    button.classList.toggle('border-white/10', !isActive);
                    button.classList.toggle('bg-white/[0.02]', !isActive);
                    button.classList.toggle('text-[#94a3b8]', !isActive);
                });
            };

            filterButtons.forEach((button) => button.addEventListener('click', () => {
                activeReviewFilter = button.dataset.reviewFilter;
                updateFilterButtons();
                renderReviews();
            }));

            sortSelect?.addEventListener('change', () => {
                activeReviewSort = sortSelect.value;
                renderReviews();
            });

            const loadReviews = async () => {
                try {
                    const response = await fetch(reviewRoot.dataset.reviewsUrl, { headers: { Accept: 'application/json' } });
                    if (!response.ok) throw new Error(`HTTP ${response.status}`);
                    const data = await response.json();
                    loadedReviews = data.reviews ?? [];
                    renderReviews();
                } catch (error) {
                    reviewList.innerHTML = '<div class="rounded-xl border border-red-400/20 bg-red-400/10 p-5 text-center text-sm text-red-300">Gagal memuat review.</div>';
                }
            };

            loadReviews();

            reviewList.addEventListener('click', (event) => {
                const button = event.target.closest('[data-helpful-choice]');
                if (!button) return;

                const panel = button.closest('[data-helpful-panel]');
                const buttons = panel?.querySelectorAll('[data-helpful-choice]') ?? [];
                const feedback = panel?.querySelector('[data-helpful-feedback]');

                buttons.forEach((item) => {
                    const isSelected = item === button;
                    item.disabled = true;
                    item.classList.toggle('bg-[#263a50]', isSelected);
                    item.classList.toggle('text-[#f8fafc]', isSelected);
                    item.classList.toggle('opacity-45', !isSelected);
                });
                feedback?.classList.remove('hidden');
            });

            const reviewForm = document.getElementById('simple-review-form');
            const reviewBody = reviewForm?.querySelector('[data-review-body]');
            const reviewCounter = reviewForm?.querySelector('[data-review-counter]');
            const updateReviewCounter = () => {
                if (reviewCounter && reviewBody) reviewCounter.textContent = `${reviewBody.value.length}/500`;
            };
            reviewBody?.addEventListener('input', updateReviewCounter);
            updateReviewCounter();

            reviewForm?.addEventListener('submit', async (event) => {
                event.preventDefault();
                const button = reviewForm.querySelector('button[type="submit"]');
                const originalLabel = button.textContent;
                button.disabled = true;
                button.textContent = 'Mengirim...';

                try {
                    const response = await fetch(@js(route('games.reviews.store', $game)), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': @js(csrf_token()),
                            Accept: 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(Object.fromEntries(new FormData(reviewForm))),
                    });

                    if (!response.ok) {
                        const error = await response.json();
                        throw new Error(error.message || 'Gagal mengirim review.');
                    }

                    window.location.reload();
                } catch (error) {
                    alert(error.message || 'Terjadi kesalahan saat mengirim review.');
                    button.disabled = false;
                    button.textContent = originalLabel;
                }
            });

            window.deleteReview = async (reviewId) => {
                window.adminConfirm({
                    title: 'Hapus Review',
                    message: 'Apakah Anda yakin ingin menghapus review Anda untuk game ini?',
                    type: 'danger',
                    confirmLabel: 'Ya, Hapus',
                    onConfirm: async () => {
                        try {
                            const response = await fetch(`/game/{{ $game->game_id }}/reviews/${reviewId}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': @js(csrf_token()),
                                    Accept: 'application/json',
                                },
                            });

                            if (!response.ok) throw new Error();
                            window.location.reload();
                        } catch (error) {
                            alert('Gagal menghapus review.');
                        }
                    }
                });
            };
        });
    </script>
@endpush
