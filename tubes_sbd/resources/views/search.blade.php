@extends('layouts.store')

@push('styles')
    <style>
        .search-result-row {
            position: relative;
            grid-template-columns: 260px minmax(0, 1fr) auto;
            min-height: 118px;
            background: linear-gradient(90deg, rgba(22, 32, 45, .86), rgba(15, 25, 35, .78));
            border: 1px solid rgba(42, 71, 94, .62);
            transition: background .18s ease, border-color .18s ease, box-shadow .18s ease, transform .18s ease;
        }
        .search-result-row::before {
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
        .search-result-row:hover,
        .search-result-row:focus-visible {
            background: linear-gradient(90deg, rgba(42, 71, 94, .98), rgba(22, 32, 45, .96));
            border-color: rgba(102, 192, 244, .76);
            box-shadow: 0 18px 42px rgba(17, 141, 255, .14);
            transform: translateX(4px);
        }
        .search-result-row:hover::before,
        .search-result-row:focus-visible::before {
            opacity: 1;
        }
        .search-result-row:hover .search-result-thumb {
            transform: scale(1.045);
            filter: saturate(1.1);
        }
        .search-result-thumb {
            transition: transform .28s ease, filter .28s ease;
        }
        .search-hover-popover {
            position: fixed;
            top: 0;
            left: 0;
            z-index: 90;
            display: none;
            width: min(300px, calc(100vw - 32px));
            pointer-events: none;
            background:
                linear-gradient(180deg, rgba(226, 236, 246, .96), rgba(202, 215, 226, .98));
            color: #1b2838;
            border: 1px solid rgba(102, 192, 244, .35);
            box-shadow: 0 24px 58px rgba(0, 0, 0, .38);
        }
        .search-hover-popover.is-visible {
            display: block;
            animation: search-popover-in .12s ease both;
        }
        .search-preview-image {
            height: 145px;
            width: 100%;
            object-fit: cover;
        }
        .search-preview-title {
            display: -webkit-box;
            overflow: hidden;
            text-overflow: ellipsis;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
        }
        .search-preview-chip {
            background: rgba(87, 103, 118, .74);
            color: #f1f5f9;
        }
        .search-pagination-panel {
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .search-pagination-controls {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .85rem;
        }
        .search-page-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            min-width: 48px;
            border: 1px solid rgba(42, 71, 94, .95);
            border-radius: 10px;
            background: rgba(7, 17, 29, .7);
            color: rgba(229, 236, 245, .72);
            font-size: 1rem;
            font-weight: 900;
            transition: transform .18s ease, border-color .18s ease, color .18s ease, background .18s ease, box-shadow .18s ease;
        }
        .search-page-button:hover {
            transform: translateY(-2px);
            border-color: rgba(102, 192, 244, .72);
            color: #fff;
            background: rgba(15, 25, 35, .96);
            box-shadow: 0 14px 32px rgba(17, 141, 255, .12);
        }
        .search-page-button.is-active {
            border-color: rgba(102, 192, 244, .98);
            background: linear-gradient(135deg, #118dff, #2d73ff);
            color: #fff;
            box-shadow:
                0 0 0 1px rgba(102, 192, 244, .45),
                0 0 20px rgba(45, 115, 255, .76),
                inset 0 1px 0 rgba(255, 255, 255, .18);
        }
        .search-page-button.is-disabled {
            opacity: .38;
            pointer-events: none;
        }
        .search-page-ellipsis {
            display: inline-flex;
            height: 48px;
            min-width: 24px;
            align-items: center;
            justify-content: center;
            color: rgba(229, 236, 245, .42);
            font-size: 1rem;
            font-weight: 900;
        }
        @keyframes search-popover-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
        @media (max-width: 1024px) {
            .search-result-row {
                grid-template-columns: 180px minmax(0, 1fr);
            }
            .search-result-price {
                grid-column: 2;
                justify-self: start;
            }
        }
        @media (max-width: 640px) {
            .search-result-row {
                grid-template-columns: 112px minmax(0, 1fr);
                min-height: 104px;
                transform: none !important;
            }
            .search-hover-popover {
                display: none !important;
            }
            .search-pagination-panel {
                margin-top: 1.75rem !important;
            }
            .search-pagination-controls {
                gap: .55rem;
            }
            .search-page-button {
                height: 42px;
                min-width: 42px;
                border-radius: 10px;
                font-size: .92rem;
            }
            .search-page-ellipsis {
                height: 42px;
            }
        }
    </style>
@endpush

@section('content')
    @php
        $fallbackImage = 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=70&w=800&auto=format&fit=crop';
        $formatPrice = fn ($price) => (float) $price <= 0 ? 'Gratis' : 'Rp ' . number_format($price, 0, ',', '.');
    @endphp

    <x-game-search
        :value="$search"
        :categories="$categories"
        :genres="$genres"
        :selected-category="$selectedCategory"
        :selected-genre="$selectedGenre"
    />

    <main class="mx-auto max-w-[1700px] px-4 py-10 sm:px-6 lg:px-8">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">
                    {{ $sort === 'popular' ? 'Top Sellers' : 'Search Results' }}
                </p>
                <h1 class="mt-2 text-4xl font-black text-white">
                    {{ $sort === 'popular' ? 'Game paling banyak dibeli' : $games->total() . ' game ditemukan' }}
                </h1>
                @if($sort === 'popular')
                    <p class="mt-2 text-gray-400">
                        Urutan ini dihitung dari jumlah checkout yang statusnya sudah paid.
                    </p>
                @endif
                @if($search !== '')
                    <p class="mt-2 text-gray-400">
                        Untuk awalan huruf/judul:
                        <span class="font-semibold text-[#66c0f4]">"{{ $search }}"</span>
                    </p>
                @endif
                @if(!empty($maxPrice))
                    <p class="mt-2 text-gray-400">
                        Filter harga:
                        <span class="font-semibold text-[#66c0f4]">di bawah {{ $formatPrice($maxPrice) }}</span>
                    </p>
                @endif
                @if(!empty($discountedOnly))
                    <p class="mt-2 text-gray-400">
                        Filter promo:
                        <span class="font-semibold text-[#66c0f4]">game yang sedang diskon</span>
                    </p>
                @endif
            </div>
        </div>

        <div class="relative">
            <div class="min-w-0 space-y-2" data-search-preview-list>
                @forelse($games as $game)
                    @php
                        $discount = $game->discount_percent;
                        $price = (float) $game->price;
                        $finalPrice = $game->final_price;
                        $releaseDate = $game->release_date ? $game->release_date->translatedFormat('j M Y') : 'TBA';
                        $tagList = $game->genres
                            ->take(3)
                            ->pluck('name')
                            ->merge($game->categories->take(2)->pluck('name'))
                            ->filter()
                            ->values();
                        $previewImages = $game->screenshots
                            ->pluck('url')
                            ->prepend($game->thumbnail_url ?: $fallbackImage)
                            ->filter()
                            ->unique()
                            ->take(1)
                            ->values();
                        $previewTags = $game->genres
                            ->take(3)
                            ->pluck('name')
                            ->merge($game->categories->take(3)->pluck('name'))
                            ->filter()
                            ->values();
                    @endphp

                    <a
                        href="{{ url('/game/' . $game->game_id) }}"
                        class="search-result-row group grid items-center gap-4 overflow-hidden rounded-lg p-2"
                        data-search-preview-row
                    >
                        <span class="block overflow-hidden rounded-md">
                            <img
                                src="{{ $game->thumbnail_url ?: $fallbackImage }}"
                                alt="{{ $game->title }}"
                                class="search-result-thumb h-24 w-full object-cover sm:h-[108px]"
                                loading="lazy"
                                decoding="async"
                            >
                        </span>

                        <span class="min-w-0">
                            <span class="block truncate text-xl font-black text-white group-hover:text-[#66c0f4]">
                                {{ $game->title }}
                            </span>
                            <span class="mt-2 block truncate text-base font-bold text-gray-300">
                                {{ $tagList->isNotEmpty() ? $tagList->join(', ') : 'PlayMart' }}
                            </span>
                            <span class="mt-3 flex flex-wrap items-center gap-3 text-sm font-bold text-gray-500">
                                @if($game->platforms->isNotEmpty())
                                    <span class="flex items-center gap-1.5">
                                        @foreach($game->platforms->take(4) as $platform)
                                            <span title="{{ $platform->name }}" class="h-4 w-4 opacity-70">
                                                {!! $platform->icon !!}
                                            </span>
                                        @endforeach
                                    </span>
                                @endif
                                <span>Dirilis: {{ $releaseDate }}</span>
                                @if(($game->paid_purchases_count ?? 0) > 0)
                                    <span class="rounded bg-[#0b2a44]/90 px-2 py-0.5 text-xs font-black uppercase text-[#66c0f4]">
                                        {{ number_format($game->paid_purchases_count, 0, ',', '.') }} pembelian
                                    </span>
                                @endif
                            </span>
                        </span>

                        <span class="search-result-price flex min-w-[132px] flex-col items-end gap-1 pr-2">
                            @if($discount > 0)
                                <span class="flex items-center gap-2">
                                    <span class="rounded bg-[#8bc53f] px-2 py-1 text-sm font-black text-[#102008]">-{{ $discount }}%</span>
                                    <span class="text-xs font-bold text-gray-500 line-through">{{ $formatPrice($price) }}</span>
                                </span>
                            @endif
                            <span class="rounded-md border border-[#2a475e]/70 bg-[#07111d] px-4 py-2 text-base font-black text-white">
                                {{ $formatPrice($finalPrice) }}
                            </span>
                        </span>

                        <template data-search-preview-template>
                            <div class="p-3">
                                <h2 class="search-preview-title text-lg font-black leading-tight">{{ $game->title }}</h2>
                                <p class="mt-1 text-xs font-bold text-slate-700">Dirilis: {{ $releaseDate }}</p>

                                <div class="mt-2.5">
                                    @foreach($previewImages as $image)
                                        <img
                                            src="{{ $image }}"
                                            alt="{{ $game->title }} preview"
                                            class="search-preview-image rounded-sm shadow"
                                            loading="lazy"
                                            decoding="async"
                                        >
                                    @endforeach
                                </div>

                                <div class="mt-2.5 rounded bg-slate-600/78 p-2 text-xs font-bold text-slate-100">
                                    <span class="block">Ulasan Umum Pengguna</span>
                                    <span class="text-[#d8b35a]">
                                        Bercampur ({{ number_format($game->reviews_count ?? 0, 0, ',', '.') }} ulasan)
                                    </span>
                                </div>

                                <div class="mt-2.5">
                                    <p class="text-xs font-bold text-slate-700">Tag pengguna:</p>
                                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                                        @forelse($previewTags as $tag)
                                            <span class="search-preview-chip rounded px-2 py-1 text-[11px] font-black">{{ $tag }}</span>
                                        @empty
                                            <span class="search-preview-chip rounded px-2 py-1 text-[11px] font-black">Game</span>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </template>
                    </a>
                @empty
                    <div class="rounded-2xl border border-[#2a475e] bg-[#16202d] py-20 text-center">
                        <h2 class="text-4xl font-black text-gray-300">Game not found</h2>
                    </div>
                @endforelse
            </div>

            @if($games->count() > 0)
                <div class="search-hover-popover rounded-sm" data-search-hover-popover aria-hidden="true"></div>
            @endif
        </div>

        @if($games->total() > 0)
            @php
                $currentPage = $games->currentPage();
                $lastPage = $games->lastPage();
                $startPage = max(1, $currentPage - 1);
                $endPage = min($lastPage, $currentPage + 1);

                if ($currentPage === 1) {
                    $endPage = min($lastPage, 3);
                }

                if ($currentPage === $lastPage) {
                    $startPage = max(1, $lastPage - 2);
                }

                $pageNumbers = range($startPage, $endPage);
            @endphp

            @if($lastPage > 1)
                <nav class="search-pagination-panel mt-8" aria-label="Navigasi halaman hasil search">
                    <div class="search-pagination-controls">
                        <a
                            href="{{ $games->previousPageUrl() ?: '#' }}"
                            class="search-page-button {{ $games->onFirstPage() ? 'is-disabled' : '' }}"
                            aria-label="Halaman sebelumnya"
                            @if($games->onFirstPage()) aria-disabled="true" @endif
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" d="M15 19 8 12l7-7"/>
                            </svg>
                        </a>

                        @foreach($pageNumbers as $page)
                            @if($page === $currentPage)
                                <span class="search-page-button is-active" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $games->url($page) }}" class="search-page-button" aria-label="Halaman {{ $page }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        <a
                            href="{{ $games->nextPageUrl() ?: '#' }}"
                            class="search-page-button {{ $games->hasMorePages() ? '' : 'is-disabled' }}"
                            aria-label="Halaman berikutnya"
                            @if(!$games->hasMorePages()) aria-disabled="true" @endif
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.6" d="m9 5 7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </nav>
            @endif
        @endif
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-search-preview-list]').forEach((list) => {
                const rows = Array.from(list.querySelectorAll('[data-search-preview-row]'));
                const popover = document.querySelector('[data-search-hover-popover]');
                let activeRow = null;
                let hoverTimer = null;
                const hoverDelay = 1000;

                if (rows.length === 0 || !popover) {
                    return;
                }

                const clearHoverTimer = () => {
                    if (hoverTimer) {
                        clearTimeout(hoverTimer);
                        hoverTimer = null;
                    }
                };

                const positionPopover = (row = activeRow) => {
                    const gap = 18;
                    const viewportPadding = 14;
                    const width = popover.offsetWidth || 300;
                    const height = popover.offsetHeight || 300;
                    const rowRect = row?.getBoundingClientRect();

                    if (!rowRect) {
                        return;
                    }

                    let left = rowRect.right + gap;
                    let top = rowRect.top + (rowRect.height / 2) - (height / 2);

                    if (left + width > window.innerWidth - viewportPadding) {
                        left = rowRect.left - width - gap;
                        popover.classList.add('is-left');
                    } else {
                        popover.classList.remove('is-left');
                    }

                    left = Math.max(viewportPadding, Math.min(left, window.innerWidth - width - viewportPadding));
                    top = Math.max(viewportPadding, Math.min(top, window.innerHeight - height - viewportPadding));

                    popover.style.left = `${Math.round(left)}px`;
                    popover.style.top = `${Math.round(top)}px`;
                };

                const showPopover = (row) => {
                    const template = row.querySelector('[data-search-preview-template]');

                    if (!template) {
                        return;
                    }

                    activeRow = row;
                    popover.innerHTML = template.innerHTML;
                    popover.classList.add('is-visible');
                    popover.setAttribute('aria-hidden', 'false');
                    requestAnimationFrame(() => positionPopover(row));
                };

                const schedulePopover = (row) => {
                    clearHoverTimer();
                    hoverTimer = setTimeout(() => {
                        hoverTimer = null;
                        showPopover(row);
                    }, hoverDelay);
                };

                const hidePopover = () => {
                    clearHoverTimer();
                    activeRow = null;
                    popover.classList.remove('is-visible');
                    popover.setAttribute('aria-hidden', 'true');
                    popover.innerHTML = '';
                };

                rows.forEach((row) => {
                    row.addEventListener('mouseenter', () => schedulePopover(row));
                    row.addEventListener('mouseleave', hidePopover);
                    row.addEventListener('focus', () => schedulePopover(row));
                    row.addEventListener('blur', hidePopover);
                });

                window.addEventListener('resize', () => {
                    if (activeRow) {
                        positionPopover(activeRow);
                    }
                });

                window.addEventListener('scroll', () => {
                    if (activeRow) {
                        positionPopover(activeRow);
                    }
                }, true);
            });
        });
    </script>
@endpush
