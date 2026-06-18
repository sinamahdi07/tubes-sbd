@extends('layouts.app') {{-- Asumsi kamu punya layout utama bernama 'app.blade.php' --}}

@section('content')
    <div class="container-fluid px-0">
        {{-- Main Carousel Section --}}
        <section class="mb-5">
            <div id="mainCarousel" class="carousel slide carousel-fade shadow-lg" data-bs-ride="carousel">
                <div class="carousel-inner rounded-4">
                    @forelse($featuredGames as $index => $game)
                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                            <div class="carousel-image-wrapper">
                                <img src="{{ $game->header_image_url }}" class="d-block w-100 object-fit-cover" alt="{{ $game->title }}">
                                <div class="carousel-vignette"></div>
                            </div>
                            <div class="carousel-caption text-start pb-5 px-4">
                                <span class="badge bg-primary mb-2">Featured Game</span>
                                <h2 class="display-6 fw-bold text-white">{{ $game->title }}</h2>
                                <p class="d-none d-md-block text-white-50">{{ Str::limit($game->short_description, 150) }}</p>
                                <div class="d-flex align-items-center gap-3 mt-3">
                                    <a href="{{ route('games.show', $game->game_id) }}" class="btn btn-light btn-lg px-4 fw-bold">Lihat Detail</a>
                                    @if ($game->final_price > 0)
                                        <span class="fs-4 fw-bold text-white">{{ format_price($game->final_price) }}</span>
                                    @else
                                        <span class="fs-4 fw-bold text-white">Gratis</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="carousel-item active">
                            <div class="carousel-image-wrapper">
                                <img src="https://via.placeholder.com/1920x600/343a40/ffffff?text=No+Featured+Games" class="d-block w-100 object-fit-cover" alt="No Featured Games">
                                <div class="carousel-vignette"></div>
                            </div>
                            <div class="carousel-caption text-start pb-5 px-4">
                                <h2 class="display-6 fw-bold text-white">Tidak Ada Game Unggulan</h2>
                                <p class="d-none d-md-block text-white-50">Coba cek nanti atau cari game lain.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon p-3 bg-dark bg-opacity-25 rounded-circle"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon p-3 bg-dark bg-opacity-25 rounded-circle"></span>
                </button>
            </div>
        </section>

        <div class="container py-4 py-md-5">
            {{-- Showcase Tabs Section --}}
            @if($showcaseTabs->isNotEmpty())
                <section class="mb-5">
                    <h3 class="mb-4 text-white">Jelajahi Berdasarkan Kategori</h3>
                    <ul class="nav nav-pills mb-3 justify-content-center justify-content-md-start" id="showcaseTabs" role="tablist">
                        @foreach($showcaseTabs as $index => $tab)
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $index === 0 ? 'active' : '' }}" id="{{ $tab['key'] }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $tab['key'] }}" type="button" role="tab" aria-controls="{{ $tab['key'] }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                    {{ $tab['label'] }}
                                </button>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="showcaseTabsContent">
                        @foreach($showcaseTabs as $index => $tab)
                            <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="{{ $tab['key'] }}" role="tabpanel" aria-labelledby="{{ $tab['key'] }}-tab">
                                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                                    @forelse($tab['games'] as $game)
                                        <div class="col">
                                            @include('components.game_card', ['game' => $game]) {{-- Asumsi ada component game_card --}}
                                        </div>
                                    @empty
                                        <div class="col-12 text-center text-white-50 py-4">Tidak ada game di kategori ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Discounted Games Section --}}
            @if($discountedGames->isNotEmpty())
                <section class="mb-5">
                    <h3 class="mb-4 text-white">Penawaran Spesial</h3>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                        @foreach($discountedGames as $game)
                            <div class="col">
                                @include('components.game_card', ['game' => $game])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Browse Categories Section --}}
            @if($browseCategoryCards->isNotEmpty())
                <section class="mb-5">
                    <h3 class="mb-4 text-white">Jelajahi Berdasarkan Genre</h3>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                        @foreach($browseCategoryCards as $card)
                            <div class="col">
                                <a href="{{ $card['url'] }}" class="card bg-dark text-white border-0 shadow-sm h-100 category-card" style="background-image: url('{{ $card['image'] }}');">
                                    <div class="card-img-overlay d-flex flex-column justify-content-end p-3" style="background: {{ $card['overlay'] }};">
                                        <h5 class="card-title fw-bold mb-0">{{ $card['label'] }}</h5>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Budget Deals Section --}}
            @if($budgetDeals->isNotEmpty())
                <section class="mb-5">
                    <h3 class="mb-4 text-white">Game Murah Meriah</h3>
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3">
                        @foreach($budgetDeals as $game)
                            <div class="col">
                                @include('components.game_card', ['game' => $game])
                            </div>
                        @endforeach
                    </div>
                    @if($budgetQuickLinks->isNotEmpty())
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            @foreach($budgetQuickLinks as $link)
                                <a href="{{ $link['url'] }}" class="btn btn-outline-light btn-sm">{{ $link['label'] }}</a>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
        </div>
    </div>
@endsection

{{-- Asumsi kamu punya helper function untuk format harga, contoh: --}}
{{-- function format_price($price) {
    return 'Rp ' . number_format($price, 0, ',', '.');
} --}}

{{-- Contoh game_card component (buat file resources/views/components/game_card.blade.php) --}}
{{--
<div class="card bg-dark text-white border-0 shadow-sm game-card-hover h-100">
    <img src="{{ $game->thumbnail_url }}" class="card-img-top object-fit-cover" alt="{{ $game->title }}" style="height: 150px;">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title mb-1 fs-6">{{ $game->title }}</h5>
        <p class="card-text text-white-50 small mb-2">{{ $game->publisher->name ?? 'Unknown' }}</p>
        <div class="mt-auto">
            @if ($game->discount_percent > 0)
                <span class="badge bg-success me-2">-{{ $game->discount_percent }}%</span>
                <s class="text-white-50 small">{{ format_price($game->price) }}</s>
                <span class="fw-bold d-block">{{ format_price($game->final_price) }}</span>
            @else
                <span class="fw-bold">{{ format_price($game->price) }}</span>
            @endif
        </div>
        <a href="{{ route('games.show', $game->game_id) }}" class="stretched-link"></a>
    </div>
</div>
--}}