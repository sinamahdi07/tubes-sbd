<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <x-store-nav />

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
                            <span data-review-label>No Reviews</span>
                        </div>

                        <div class="mt-1 text-xs text-gray-500" data-review-percent>
                            0% dari 0 review menyukai game ini
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

                    <!-- CATEGORIES -->
                    @if($game->categories->isNotEmpty())
                    <div class="mb-6">

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

                    <!-- BUTTON -->
                    @php
                        $isPurchased = false;
                        $isInCart = false;
                        if(auth()->check()) {
                            $isPurchased = \App\Models\Payment::join('payment_items', 'payments.id', '=', 'payment_items.payment_id')
                                ->where('payments.user_id', auth()->id())
                                ->where('payment_items.game_id', $game->game_id)
                                ->where('payments.status', \App\Models\Payment::STATUS_PAID)
                                ->exists();

                            $isInCart = \App\Models\Cart::where('user_id', auth()->id())
                                ->where('game_id', $game->game_id)
                                ->exists();
                        }
                    @endphp

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

                </div>

            </div>

        </div>

        <section
            class="mt-10 bg-[#16202d] p-8 rounded-2xl border border-[#2a475e]"
            data-review-root
            data-reviews-url="{{ route('games.reviews.index', $game) }}"
            data-is-authenticated="{{ auth()->check() ? '1' : '0' }}"
            @auth
                data-login-url=""
            @else
                data-login-url="{{ route('login') }}"
            @endauth
        >
            <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                <div>
                    <h2 class="text-3xl font-bold">Reviews</h2>
                    <p class="mt-2 text-gray-400">
                        Bagikan pendapat kamu setelah membeli game ini.
                    </p>
                </div>

                <div class="rounded-xl border border-[#2a475e] bg-[#0f1923] px-5 py-4 text-right">
                    <p class="text-2xl font-black text-[#66c0f4]" data-review-label>No Reviews</p>
                    <p class="mt-1 text-sm text-gray-400" data-review-percent>0% dari 0 review menyukai game ini</p>
                </div>
            </div>

            <div class="mt-6 rounded-2xl border border-[#2a475e] bg-[#0f1923] p-5" data-review-form-shell>
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

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-gray-400" data-review-form-message>
                                Kamu bisa edit review dengan submit ulang.
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
                <div class="rounded-xl border border-[#2a475e] bg-[#0f1923] p-5 text-gray-400">
                    Loading reviews...
                </div>
            </div>
        </section>

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
            let formHydrated = false;
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

                const userReview = payload.user_review;

                if (userReview && reviewBody && !formHydrated) {
                    reviewBody.value = userReview.body || '';
                    const selector = `input[name="is_recommended"][value="${userReview.is_recommended ? 1 : 0}"]`;
                    reviewForm?.querySelector(selector)?.click();
                    if (formMessage) {
                        formMessage.textContent = 'Review kamu sudah tersimpan. Submit ulang untuk edit.';
                    }
                    formHydrated = true;
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
                if (formMessage) {
                    formMessage.textContent = 'Review tersimpan dan feed sudah diperbarui.';
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
