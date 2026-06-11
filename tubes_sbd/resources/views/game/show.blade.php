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

    // Ambil data game lain dari developer yang sama agar sidebar tidak kosong
    $moreGames = collect();
    if($game->developer_id) {
        $moreGames = \App\Models\Game::with('detail')
            ->where('developer_id', $game->developer_id)
            ->where('game_id', '!=', $game->game_id)
            ->limit(4)
            ->get();
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
            background: rgba(10, 20, 35, 0.7);
            backdrop-filter: blur(40px);
            border: 1px solid rgba(255, 255, 255, 0.05);
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

        /* CLICKABLE BADGES & LINKS */
        .clickable-badge {
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }
        .clickable-badge:hover {
            background: rgba(29, 185, 84, 0.3) !important;
            border-color: #1DB954 !important;
            color: #1DB954 !important;
            transform: translateY(-2px);
        }

        .clickable-link {
            color: #ffffff;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: all 0.2s ease;
        }
        .clickable-link:hover {
            color: #1DB954 !important;
            border-bottom-color: #1DB954;
        }

        /* REVIEW STYLING - FIX WHITE/BLANK ISSUE */
        .review-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.2s ease;
        }
        .review-card:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: translateY(-2px);
        }
        .review-text {
            color: rgba(255, 255, 255, 0.8) !important;
            line-height: 1.6;
        }
        .review-input-box {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
        .review-input-box:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: #1DB954;
            box-shadow: none;
            color: #fff;
        }
        .review-user {
            color: #ffffff !important;
        }
        .review-date {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* MOOD CARDS */
        .mood-card {
            background: rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.05);
            color: rgba(255, 255, 255, 0.4);
        }
        .mood-positive:hover { background: rgba(29, 185, 84, 0.05); border-color: rgba(29, 185, 84, 0.2); color: rgba(29, 185, 84, 0.5); }
        .mood-negative:hover { background: rgba(220, 53, 69, 0.05); border-color: rgba(220, 53, 69, 0.2); color: rgba(220, 53, 69, 0.5); }

        .btn-check:checked + .mood-positive {
            background: rgba(29, 185, 84, 0.15) !important;
            border-color: #1DB954 !important;
            color: #1DB954 !important;
            box-shadow: 0 0 20px rgba(29, 185, 84, 0.2);
        }
        .btn-check:checked + .mood-negative {
            background: rgba(220, 53, 69, 0.15) !important;
            border-color: #dc3545 !important;
            color: #dc3545 !important;
            box-shadow: 0 0 20px rgba(220, 53, 69, 0.2);
        }
        .mood-icon { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        .btn-check:checked + .mood-card .mood-icon { transform: scale(1.3) rotate(-10deg); }

        /* SPECS DASHBOARD */
        .specs-card {
            background: linear-gradient(145deg, rgba(10, 20, 35, 0.8), rgba(5, 10, 20, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
        }
        .specs-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(29, 185, 84, 0.3), transparent);
        }
        .spec-line {
            padding: 12px 0;
            border-bottom: 1px dashed rgba(255, 255, 255, 0.05);
        }
        .spec-line:last-child { border-bottom: none; }
        
        .tech-font { font-family: 'JetBrains Mono', 'Fira Code', monospace; }
        .glow-text-success { text-shadow: 0 0 10px rgba(29, 185, 84, 0.3); }
        .glow-text-info { text-shadow: 0 0 10px rgba(17, 141, 255, 0.3); }

        .spec-content strong { color: #ffffff; font-weight: 800; display: block; margin-top: 10px; margin-bottom: 2px; text-transform: uppercase; font-size: 10px; letter-spacing: 1px; }
        .spec-content br { display: block; content: ""; margin-top: 4px; }
        .spec-content ul { list-style: none; padding-left: 0; }
        .spec-content li { position: relative; padding-left: 15px; margin-bottom: 4px; }
        .spec-content li::before { content: '>'; position: absolute; left: 0; color: #1DB954; font-weight: bold; }

        .more-game-item:hover {
            transform: translateX(8px);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1.5rem;
            background: rgba(255,255,255,0.02);
        }

        .sidebar-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

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
            <div class="d-flex align-items-center gap-4 text-white-50 fw-bold flex-wrap">
                <!-- CLICKABLE DEVELOPER -->
                @if($game->developer)
                <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="clickable-link d-flex align-items-center gap-2">
                    <div class="rounded-circle border overflow-hidden" style="width: 32px; height: 32px;">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($game->developer->name) }}&background=1DB954&color=000" class="w-100 h-100">
                    </div>
                    <span>{{ $game->developer->name }}</span>
                </a>
                @else
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle border overflow-hidden" style="width: 32px; height: 32px;">
                        <img src="https://ui-avatars.com/api/?name=D&background=1DB954&color=000" class="w-100 h-100">
                    </div>
                    <span>Unknown Developer</span>
                </div>
                @endif
                
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
                        <div class="row g-4 mb-5">
                            <div class="col-md-12">
                                @if($game->detail && $game->detail->short_description)
                                    <p class="h4 fw-bold mb-4 italic text-white border-start border-success border-4 ps-3">{{ $game->detail->short_description }}</p>
                                @endif
                                <div class="text-white-50 lead fs-6 text-break">{{ $game->description }}</div>
                            </div>
                        </div>
                        
                        <div class="info-grid rounded-4 mb-4">
                            <div>
                                <label class="d-block text-uppercase fw-black text-white-50 mb-1" style="font-size: 10px;">Developer</label>
                                <span class="text-white fw-bold">{{ $game->developer->name ?? 'Unknown' }}</span>
                            </div>
                            <div>
                                <label class="d-block text-uppercase fw-black text-white-50 mb-1" style="font-size: 10px;">Release Date</label>
                                <span class="text-white fw-bold">{{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('d M, Y') : 'TBA' }}</span>
                            </div>
                        </div>

                        <!-- CLICKABLE GENRES/CATEGORIES -->
                        <div class="d-flex flex-wrap gap-2 mt-4 pt-4 border-top border-white border-opacity-10">
                            @foreach($game->genres as $g)
                                <a href="{{ route('games.search', ['genre' => $g->genre_id]) }}" class="badge rounded-pill bg-white bg-opacity-10 text-white-50 px-3 py-2 fw-bold clickable-badge text-decoration-none">
                                    {{ $g->name }}
                                </a>
                            @endforeach
                            @foreach($game->categories as $c)
                                <a href="{{ route('games.search', ['category' => $c->category_id]) }}" class="badge rounded-pill bg-white bg-opacity-10 text-white-50 px-3 py-2 fw-bold clickable-badge text-decoration-none">
                                    {{ $c->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- REQUIREMENTS - REDESIGNED -->
                @if($game->detail && ($game->detail->minimum_requirements || $game->detail->recommended_requirements))
                <section class="mb-5 overflow-hidden">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <h5 class="text-white-50 fw-black text-uppercase m-0 tracking-widest small">Hardware Dashboard</h5>
                        <div class="d-flex gap-2">
                            <span class="bg-success rounded-circle opacity-50" style="width: 6px; height: 6px;"></span>
                            <span class="bg-success rounded-circle animate-pulse" style="width: 6px; height: 6px;"></span>
                        </div>
                    </div>
                    
                    <div class="row g-4">
                        @if($game->detail->minimum_requirements)
                        <div class="col-md-6">
                            <div class="specs-card rounded-4 p-4 h-100">
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="bg-success bg-opacity-10 text-success p-2 rounded-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                    </div>
                                    <h6 class="fw-black text-success text-uppercase small m-0 tracking-tighter glow-text-success">Minimum Diagnostics</h6>
                                </div>
                                <div class="tech-font text-white-50 small leading-loose spec-content">
                                    {!! $game->detail->minimum_requirements !!}
                                </div>
                                <div class="mt-4 pt-3 border-top border-white border-opacity-5 d-flex justify-content-between align-items-center">
                                    <span class="text-white-10 tech-font" style="font-size: 8px;">STATUS: VERIFIED</span>
                                    <div class="bg-success bg-opacity-20 rounded-pill px-2 py-1" style="font-size: 7px; color: #1DB954;">720p @ 30FPS</div>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($game->detail->recommended_requirements)
                        <div class="col-md-6">
                            <div class="specs-card rounded-4 p-4 h-100" style="border-top: 1px solid rgba(17, 141, 255, 0.2);">
                                <div class="d-flex align-items-center gap-2 mb-4">
                                    <div class="bg-info bg-opacity-10 text-info p-2 rounded-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 20px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                    </div>
                                    <h6 class="fw-black text-info text-uppercase small m-0 tracking-tighter glow-text-info">Recommended Specs</h6>
                                </div>
                                <div class="tech-font text-white-50 small leading-loose spec-content">
                                    {!! $game->detail->recommended_requirements !!}
                                </div>
                                <div class="mt-4 pt-3 border-top border-white border-opacity-5 d-flex justify-content-between align-items-center">
                                    <span class="text-white-10 tech-font" style="font-size: 8px;">STATUS: OPTIMAL</span>
                                    <div class="bg-info bg-opacity-20 rounded-pill px-2 py-1" style="font-size: 7px; color: #0dcaf0;">1080p @ 60FPS+</div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </section>
                @endif

                <!-- REVIEWS - DYNAMICALLY LOADED -->
                <section class="mb-5" data-review-root data-reviews-url="{{ route('games.reviews.index', $game) }}">
                    <h5 class="text-white-50 fw-black text-uppercase mb-4 tracking-widest small">Reviews</h5>
                    
                    <div class="glass-box p-4 p-md-5">
                        <!-- Review Stats -->
                        <div id="review-stats-container" class="mb-5">
                            @if($totalReviews > 0)
                                <div class="d-flex align-items-center gap-4">
                                    <div class="text-center px-4 border-end border-white border-opacity-10">
                                        <div class="display-5 fw-black text-success">{{ $reviewPercentage }}%</div>
                                        <div class="small text-white-50 text-uppercase tracking-tighter">Recommended</div>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-1">{{ $reviewLabel }}</h4>
                                        <p class="text-white-50 small mb-0">Berdasarkan {{ $totalReviews }} ulasan dari para pemain.</p>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Review Form - REDESIGNED -->
                        <div id="review-form-container" class="mb-5">
                            @auth
                                @if($canReview)
                                    <div class="glass-box p-4 p-md-5 border-success border-opacity-10">
                                        <div class="d-flex align-items-center gap-3 mb-4">
                                            <div class="bg-success rounded-circle" style="width: 8px; height: 32px;"></div>
                                            <h4 class="fw-black text-uppercase m-0 tracking-tighter">Bagikan Pengalamanmu</h4>
                                        </div>

                                        <form id="simple-review-form">
                                            @csrf
                                            <p class="text-white-50 small fw-bold mb-3 text-uppercase tracking-widest">Apa kamu merekomendasikan game ini?</p>
                                            
                                            <div class="row g-3 mb-4">
                                                <div class="col-6">
                                                    <input type="radio" class="btn-check" name="is_recommended" id="rec-yes" value="1" checked>
                                                    <label class="btn w-100 py-4 rounded-4 border-2 transition-all d-flex flex-column align-items-center gap-2 mood-card mood-positive" for="rec-yes">
                                                        <div class="mood-icon display-6">👍</div>
                                                        <span class="fw-black small text-uppercase tracking-widest">Recommended</span>
                                                    </label>
                                                </div>
                                                <div class="col-6">
                                                    <input type="radio" class="btn-check" name="is_recommended" id="rec-no" value="0">
                                                    <label class="btn w-100 py-4 rounded-4 border-2 transition-all d-flex flex-column align-items-center gap-2 mood-card mood-negative" for="rec-no">
                                                        <div class="mood-icon display-6">👎</div>
                                                        <span class="fw-black small text-uppercase tracking-widest">Not Recommended</span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="position-relative mb-4">
                                                <textarea name="body" class="form-control review-input-box rounded-4 p-4 border-0 shadow-inner" rows="4" placeholder="Tuliskan alasanmu di sini... (Minimal 5 karakter)" required minlength="5" style="background: rgba(255,255,255,0.03);"></textarea>
                                                <div class="position-absolute bottom-0 end-0 p-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px; opacity: 0.1;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                </div>
                                            </div>

                                            <button type="submit" class="btn btn-spotify-green w-100 py-3 rounded-pill shadow-lg hover:scale-102 transition-transform">
                                                <span class="h6 fw-black m-0 text-uppercase tracking-widest">Posting Review</span>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="glass-box p-5 text-center border-dashed border-white border-opacity-10">
                                        <div class="mb-3 opacity-20"><svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 48px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg></div>
                                        <h6 class="fw-bold text-white-50 mb-0 italic">Kamu harus membeli game ini untuk memberikan ulasan.</h6>
                                    </div>
                                @endif
                            @else
                                <div class="glass-box p-5 text-center border-dashed border-white border-opacity-10">
                                    <p class="text-white-50 mb-4">Silahkan Login untuk memberikan review.</p>
                                    <a href="{{ route('login') }}" class="btn btn-outline-success rounded-pill px-5 fw-black text-uppercase small tracking-widest">Login Sekarang</a>
                                </div>
                            @endauth
                        </div>

                        <!-- Review List Container -->
                        <div id="review-list-container" class="d-flex flex-column gap-3">
                            <div class="text-center py-4 text-white-50 italic">Listening to community...</div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- DESKTOP SIDEBAR - FIXED Z-INDEX -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="sticky-top" style="top: 20px; z-index: 1;">
                    <!-- Purchase Sidebar -->
                    <div class="sidebar-card shadow-lg">
                        <img src="{{ $game->thumbnail_url }}" class="w-100 rounded-4 shadow-lg mb-4">
                        <div class="d-flex flex-column mb-4">
                            @if($discount > 0)<div class="badge bg-danger align-self-start mb-1">-{{ $discount }}%</div>@endif
                            @if($discount > 0)<small class="text-white-50 text-decoration-line-through">Rp{{ number_format($originalPrice, 0, ',', '.') }}</small>@endif
                            <span class="h2 fw-black m-0 text-success">{{ $originalPrice == 0 ? 'FREE' : 'Rp' . number_format($finalPrice, 0, ',', '.') }}</span>
                        </div>

                        <div class="space-y-4 pt-4 border-top border-white border-opacity-10 text-white-50 small">
                            <div class="mb-3">
                                <label class="d-block text-uppercase fw-black mb-1 text-success" style="font-size: 10px;">Publisher</label>
                                @if($game->publisher)
                                    <a href="{{ route('games.search', ['publisher' => $game->publisher->publisher_id]) }}" class="clickable-link">
                                        <span class="text-white fw-bold h6">{{ $game->publisher->name }}</span>
                                    </a>
                                @else
                                    <span class="text-white fw-bold h6">-</span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label class="d-block text-uppercase fw-black mb-1 text-success" style="font-size: 10px;">Developer</label>
                                @if($game->developer)
                                    <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="clickable-link">
                                        <span class="text-white fw-bold h6">{{ $game->developer->name }}</span>
                                    </a>
                                @else
                                    <span class="text-white fw-bold h6">-</span>
                                @endif
                            </div>
                            
                            <div class="mt-4">
                                <label class="d-block text-uppercase fw-black mb-2" style="font-size: 10px;">Available On</label>
                                <div class="d-flex gap-4 text-white">
                                    @foreach($game->platforms as $p)
                                        <div style="width: 24px; height: 24px;" title="{{ $p->name }}">{!! $p->icon !!}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- More Games by Developer/Publisher -->
                    @if($moreGames->isNotEmpty())
                    <div class="sidebar-card">
                        <h6 class="text-white-50 fw-black text-uppercase mb-3 small tracking-widest">More Games by {{ $game->developer->name ?? 'This Developer' }}</h6>
                        <div class="d-flex flex-column gap-3">
                            @foreach($moreGames as $mg)
                                @php $mgDiscount = $mg->detail->discount ?? 0; @endphp
                                <a href="{{ url('/game/' . $mg->game_id) }}" class="more-game-item d-flex align-items-center gap-3 text-decoration-none transition-all">
                                    <div class="rounded-3 overflow-hidden bg-white bg-opacity-10 position-relative" style="width: 80px; height: 45px; flex-shrink: 0;">
                                        <img src="{{ $mg->thumbnail_url }}" class="w-100 h-100 object-fit-cover" onerror="this.src='https://ui-avatars.com/api/?name=G&background=1DB954&color=000'">
                                        @if($mgDiscount > 0)
                                            <div class="position-absolute top-0 start-0 bg-danger text-white fw-black px-1" style="font-size: 8px; border-bottom-right-radius: 4px;">
                                                -{{ $mgDiscount }}%
                                            </div>
                                        @endif
                                    </div>
                                    <div class="text-white small fw-bold text-truncate flex-grow-1">{{ $mg->title }}</div>
                                </a>
                            @endforeach
                            
                            @if($game->developer)
                            <a href="{{ route('games.search', ['developer' => $game->developer->developer_id]) }}" class="btn btn-sm btn-outline-success rounded-pill mt-3">View All</a>
                            @endif
                        </div>
                    </div>
                    @endif
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
        const root = document.querySelector('[data-review-root]'); 
        if (!root) return;
        
        const target = document.getElementById('review-list-container');
        const url = root.dataset.reviewsUrl;

        console.log('Review system initialized. Fetching from:', url);

        const loadR = async () => {
            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (res.ok) {
                    const data = await res.json();
                    console.log('Reviews loaded:', data);
                    
                    if (!data.reviews || data.reviews.length === 0) {
                        target.innerHTML = '<div class="py-5 text-center text-white-50 italic fw-bold uppercase small tracking-widest">No reviews yet.</div>';
                    } else {
                        target.innerHTML = data.reviews.map(r => `
                            <div class="glass-box p-4 border-0 mb-3" style="background: rgba(0, 0, 0, 0.2);">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center fw-black text-black" style="width: 36px; height: 36px; font-size: 13px; flex-shrink: 0;">${r.initial}</div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-white small text-truncate">${r.user_name}</div>
                                            <small class="text-white-50 d-block" style="font-size: 9px;">${r.created_at}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                        ${r.is_owner ? `<button onclick="deleteReview(${r.id})" class="btn btn-link text-danger p-0 me-1 small text-decoration-none" style="font-size:10px;">Hapus</button>` : ''}
                                        <span class="badge ${r.is_recommended ? 'bg-success' : 'bg-danger'} bg-opacity-10 text-${r.is_recommended ? 'success' : 'danger'} small fw-black uppercase border border-${r.is_recommended ? 'success' : 'danger'} border-opacity-20" style="font-size: 8px; letter-spacing: 1px; padding: 4px 8px;">${r.is_recommended ? 'Like' : 'Dislike'}</span>
                                    </div>
                                </div>
                                <p class="small text-white-50 m-0 text-break leading-relaxed" style="white-space: pre-line;">${r.body}</p>
                            </div>
                        `).join('');
                    }
                } else {
                    console.error('Server error when loading reviews:', res.status);
                    target.innerHTML = `<div class="py-4 text-center text-danger small">Gagal memuat review (Server Error: ${res.status}).</div>`;
                }
            } catch (err) {
                console.error('Network error when loading reviews:', err);
                target.innerHTML = '<div class="py-4 text-center text-danger small">Kesalahan koneksi saat memuat review.</div>';
            }
        };

        loadR();

        const form = document.getElementById('simple-review-form');
        if (form) {
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = 'Sending...';

                const fd = new FormData(form);
                try {
                    const res = await fetch("{{ route('games.reviews.store', $game) }}", {
                        method: 'POST',
                        headers: { 
                            'X-CSRF-TOKEN': "{{ csrf_token() }}", 
                            'Accept': 'application/json', 
                            'Content-Type': 'application/json' 
                        },
                        body: JSON.stringify(Object.fromEntries(fd))
                    });
                    
                    if (res.ok) {
                        form.reset();
                        // Refresh reviews and potentially stats
                        // loadR(); 
                        window.location.reload(); // Reload to update stats and review status
                    } else {
                        const errorData = await res.json();
                        alert(errorData.message || 'Gagal mengirim review. Pastikan ulasan minimal 5 karakter.');
                    }
                } catch (err) {
                    console.error('Submission error:', err);
                    alert('Terjadi kesalahan saat mengirim review.');
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = 'Posting Review';
                }
            });
        }

        window.deleteReview = async (reviewId) => {
            if (!confirm('Hapus review ini?')) return;
            try {
                const res = await fetch(`/game/{{ $game->game_id }}/reviews/${reviewId}`, {
                    method: 'DELETE',
                    headers: { 
                        'X-CSRF-TOKEN': "{{ csrf_token() }}", 
                        'Accept': 'application/json' 
                    }
                });
                if (res.ok) {
                    window.location.reload(); // Reload to update stats
                } else {
                    alert('Gagal menghapus review.');
                }
            } catch (err) {
                console.error('Delete error:', err);
                alert('Terjadi kesalahan saat menghapus review.');
            }
        };
    });
</script>
@endpush