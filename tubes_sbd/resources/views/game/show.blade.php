@php
    // Logic Harga & Status
    $discount = $game->detail->discount ?? 0;
    $originalPrice = $game->price;
    $finalPrice = $discount > 0 ? $originalPrice * (1 - $discount / 100) : $originalPrice;
    
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
        $isInCart = \App\Models\Cart::where('user_id', $userId)->where('game_id', $game->game_id)->exists();
        $isWishlisted = \App\Models\Wishlist::where('user_id', $userId)->where('game_id', $game->game_id)->exists();
    }
@endphp

@extends('layouts.store')

@section('title', $game->title . ' - PlayMart')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,700;0,800;1,800&display=swap');
        
        body { 
            background-color: #000000 !important; 
            font-family: 'Plus Jakarta Sans', sans-serif !important; 
            color: #ffffff !important; 
            overflow-x: hidden !important;
            width: 100% !important;
        }

        .spotify-hero {
            background: linear-gradient(to bottom, rgba(102, 192, 244, 0.12) 0%, #000000 100%);
            min-height: 400px;
            padding-top: 120px;
            padding-bottom: 60px;
        }

        .glass-box {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(30px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1.5rem;
        }

        /* PC-Friendly Gallery with Scrollbar */
        .gallery-container {
            display: flex;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            gap: 1.25rem;
            padding-bottom: 1.5rem;
            cursor: grab;
        }
        .gallery-container::-webkit-scrollbar { height: 8px; display: block; }
        .gallery-container::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        .gallery-container::-webkit-scrollbar-thumb { background: #1DB954; border-radius: 10px; }
        
        .gallery-slide {
            flex: 0 0 85%;
            scroll-snap-align: center;
            border-radius: 1.5rem;
            overflow: hidden;
            aspect-ratio: 16/9;
            border: 1px solid rgba(255,255,255,0.1);
            background: #0a1018;
        }
        @media (min-width: 1024px) { .gallery-slide { flex: 0 0 60%; } }

        .btn-spotify-green {
            background-color: #1DB954 !important;
            color: #000000 !important;
            font-weight: 800;
            border-radius: 50px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-spotify-green:hover { transform: scale(1.05); filter: brightness(1.1); }

        .action-circle-btn {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .action-circle-btn:hover { background: rgba(255,255,255,0.15); border-color: white; }

        @media (max-width: 640px) {
            .buy-bar-mobile { bottom: 125px !important; }
            .gallery-slide { flex: 0 0 92%; }
            .gallery-container::-webkit-scrollbar { display: none; }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid p-0 overflow-hidden">
    <!-- HERO SECTION -->
    <header class="spotify-hero d-flex align-items-end relative overflow-hidden">
        <div class="absolute inset-0 z-0">
            <img src="{{ $game->thumbnail_url }}" class="w-100 h-100 object-fit-cover opacity-25 blur-3xl scale-125">
        </div>
        <div class="container relative z-1 px-4">
            <div class="mb-4"><span class="badge rounded-pill bg-success text-black px-3 py-2 fw-black small uppercase">Verified Release</span></div>
            <h1 class="display-1 fw-black italic text-uppercase text-white mb-4 tracking-tighter drop-shadow-lg">{{ $game->title }}</h1>
            <div class="d-flex align-items-center gap-4 text-white-50 fw-bold">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle border overflow-hidden" style="width: 32px; height: 32px;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($game->developer->name ?? 'D') }}&background=1DB954&color=000" class="w-100 h-100">
                    </div>
                    <span>{{ $game->developer->name ?? 'Unknown' }}</span>
                </div>
                <span class="vr bg-white-50" style="height: 12px;"></span>
                <span>{{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('Y') : 'TBA' }}</span>
            </div>
        </div>
    </header>

    <!-- CONTENT GRID -->
    <main class="container py-5 px-4">
        <div class="row g-5">
            <div class="col-lg-8">
                
                <!-- ACTION BAR DESKTOP -->
                <div class="d-none d-lg-flex align-items-center gap-4 mb-5">
                    @if(!$isPurchased)
                        <form action="{{ route('cart.add', $game->game_id) }}" method="POST" class="m-0">
                            @csrf
                            <button class="btn btn-lg btn-spotify-green px-5 py-3 h5 m-0">ADD TO CART</button>
                        </form>
                    @else
                        <div class="btn btn-lg btn-dark rounded-pill px-5 py-3 h5 m-0 border border-success border-opacity-25 text-success italic">IN LIBRARY</div>
                    @endif

                    @auth
                        <form action="{{ route('wishlist.toggle', $game) }}" method="POST" class="m-0">
                            @csrf
                            <button class="action-circle-btn {{ $isWishlisted ? 'text-success border-success' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </form>
                    @endauth

                    <button class="action-circle-btn text-white-50"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" /></svg></button>
                </div>

                <!-- GALLERY CAROUSEL -->
                <section class="mb-5">
                    <h5 class="text-white-50 fw-black text-uppercase mb-4 tracking-widest small">Gallery</h5>
                    <div class="gallery-container no-scrollbar-mobile">
                        @foreach($game->trailers as $trailer)
                            <div class="gallery-slide">
                                @if($trailer->embed_url) <iframe src="{{ $trailer->embed_url }}" class="w-100 h-100" allowfullscreen></iframe>
                                @else <div class="h-100 d-flex align-items-center justify-content-center"><a href="{{ $trailer->url }}" target="_blank" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-black">PLAY TRAILER</a></div> @endif
                            </div>
                        @endforeach
                        @foreach($game->screenshots as $shot)
                            <div class="gallery-slide cursor-pointer transition-transform hover:scale-102" onclick="zoomIn('{{ $shot->url }}')">
                                <img src="{{ $shot->url }}" class="w-100 h-100 object-fit-cover">
                            </div>
                        @endforeach
                    </div>
                </section>

                <!-- ABOUT -->
                <section class="mb-5">
                    <h5 class="text-white-50 fw-black text-uppercase mb-4 tracking-widest small">About</h5>
                    <div class="glass-box p-4 p-md-5">
                        @if($game->detail && $game->detail->short_description)<p class="h4 fw-bold mb-4 italic text-white">{{ $game->detail->short_description }}</p>@endif
                        <div class="text-white-50 lead fs-6 text-break">{{ $game->description }}</div>
                        <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top border-white border-opacity-10">
                            @foreach($game->genres as $g)<span class="badge rounded-pill bg-white bg-opacity-10 text-white-50 px-3 py-2 fw-bold">{{ $g->name }}</span>@endforeach
                        </div>
                    </div>
                </section>

                <!-- REQUIREMENTS -->
                @if($game->detail && ($game->detail->minimum_requirements || $game->detail->recommended_requirements))
                <section class="mb-5">
                    <h5 class="text-white-50 fw-black text-uppercase mb-4 tracking-widest small">Specs</h5>
                    <div class="row g-4">
                        @if($game->detail->minimum_requirements)<div class="col-md-6"><div class="glass-box p-4 h-100"><p class="text-success fw-black small uppercase mb-3">Minimum</p><div class="text-white-50 small text-break prose-invert">{!! $game->detail->minimum_requirements !!}</div></div></div>@endif
                        @if($game->detail->recommended_requirements)<div class="col-md-6"><div class="glass-box p-4 h-100"><p class="text-info fw-black small uppercase mb-3">Recommended</p><div class="text-white-50 small text-break prose-invert">{!! $game->detail->recommended_requirements !!}</div></div></div>@endif
                    </div>
                </section>
                @endif

                <!-- REVIEWS -->
                <section class="mb-5" data-review-root data-reviews-url="{{ route('games.reviews.index', $game) }}">
                    <h5 class="text-white-50 fw-black text-uppercase mb-4 tracking-widest small">Reviews</h5>
                    <div class="glass-box p-4">
                        <div id="review-list-container" class="d-flex flex-column gap-3">
                            <div class="text-center py-4 text-white-50 italic">Listening to community...</div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- DESKTOP SIDEBAR -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sticky-top" style="top: 100px;">
                    <div class="glass-box p-4 shadow-2xl">
                        <img src="{{ $game->thumbnail_url }}" class="w-100 rounded-4 shadow-lg mb-4">
                        <div class="d-flex flex-column mb-4">
                            @if($discount > 0)<small class="text-white-50 text-decoration-line-through">Rp{{ number_format($originalPrice, 0, ',', '.') }}</small>@endif
                            <span class="h2 fw-black m-0 text-success">{{ $originalPrice == 0 ? 'FREE' : 'Rp' . number_format($finalPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="space-y-4 pt-4 border-top border-white border-opacity-10 text-white-50 small">
                            <div><label class="d-block text-uppercase fw-black mb-1" style="font-size: 10px;">Publisher</label><span class="text-white fw-bold h6">{{ $game->publisher->name ?? '-' }}</span></div>
                            <div class="mt-4"><label class="d-block text-uppercase fw-black mb-2" style="font-size: 10px;">Available On</label><div class="d-flex gap-4 text-white">@foreach($game->platforms as $p)<div style="width: 24px; height: 24px;" title="{{ $p->name }}">{!! $p->icon !!}</div>@endforeach</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MOBILE STICKY BOTTOM BAR (ACTION GROUP) -->
    <div class="fixed-bottom d-lg-none buy-bar-mobile px-3 pb-3">
        <div class="glass-box p-4 shadow-2xl border-success border-opacity-20" style="background: rgba(13, 28, 46, 0.98);">
            <div class="d-flex align-items-center justify-content-between gap-3">
                <div class="flex-shrink-0">
                    <span class="h3 fw-black m-0 text-success">{{ $originalPrice == 0 ? 'FREE' : 'Rp' . number_format($finalPrice, 0, ',', '.') }}</span>
                </div>
                
                <div class="d-flex align-items-center gap-2 flex-grow-1 justify-content-end">
                    @auth
                        <form action="{{ route('wishlist.toggle', $game) }}" method="POST" class="m-0">
                            @csrf
                            <button class="action-circle-btn {{ $isWishlisted ? 'text-success border-success' : '' }}" style="width: 48px; height: 48px;">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                            </button>
                        </form>
                    @endauth

                    @if($isPurchased)
                        <span class="badge bg-white bg-opacity-10 text-white-50 rounded-pill px-4 py-3 small fw-bold">OWNED</span>
                    @else
                        <form action="{{ route('cart.add', $game->game_id) }}" method="POST" class="m-0 flex-grow-1">
                            @csrf
                            <button class="btn btn-success btn-spotify-green w-100 py-3 small fw-black tracking-widest shadow-lg">BUY NOW</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FULL SCREEN IMAGE ZOOM -->
<div id="master-zoom" class="fixed-top w-100 h-100 bg-black bg-opacity-98 d-none align-items-center justify-content-center p-4" style="z-index: 9999;" onclick="this.classList.add('d-none'); document.body.style.overflow=''">
    <img id="zoom-img-target" src="" class="img-fluid rounded-5 shadow-2xl transition-all" style="max-height: 92vh;">
</div>
@endsection

@push('scripts')
<script>
    function zoomIn(url) {
        const o = document.getElementById('master-zoom');
        const i = document.getElementById('zoom-img-target');
        i.src = url;
        o.classList.remove('d-none');
        o.classList.add('d-flex');
        document.body.style.overflow = 'hidden';
    }

    document.addEventListener('DOMContentLoaded', () => {
        const root = document.querySelector('[data-review-root]'); if (!root) return;
        const target = document.getElementById('review-list-container');
        const url = root.dataset.reviewsUrl;

        const loadR = async () => {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (res.ok) {
                const data = await res.json();
                if (data.reviews.length === 0) {
                    target.innerHTML = '<div class="py-4 text-center text-white-50 italic fw-bold uppercase small tracking-widest">No reviews yet.</div>';
                } else {
                    target.innerHTML = data.reviews.map(r => `
                        <div class="glass-box p-4 border-0 bg-white bg-opacity-5">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center fw-black text-black" style="width: 36px; height: 36px; font-size: 13px;">${r.initial}</div>
                                    <div>
                                        <div class="fw-bold text-white small">${r.user_name}</div>
                                        <small class="text-white-50" style="font-size: 9px;">${r.created_at}</small>
                                    </div>
                                </div>
                                <span class="badge ${r.is_recommended ? 'bg-success' : 'bg-danger'} bg-opacity-10 text-${r.is_recommended ? 'success' : 'danger'} small fw-black uppercase" style="font-size: 8px; letter-spacing: 1px;">${r.is_recommended ? 'Like' : 'Dislike'}</span>
                            </div>
                            <p class="small text-white-50 m-0 text-break leading-relaxed">${r.body}</p>
                        </div>
                    `).join('');
                }
            }
        };
        loadR();
    });
</script>
@endpush
