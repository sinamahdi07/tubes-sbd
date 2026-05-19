@extends('layouts.store')

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

    <main class="mx-auto max-w-7xl px-6 py-10">
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
            </div>
        </div>

        <div class="space-y-5">
            @forelse($games as $game)
                <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                    <article class="overflow-hidden rounded-2xl border border-[#2a475e] bg-[#16202d] transition hover:border-[#66c0f4] hover:bg-[#1f2f42]">
                        <div class="grid gap-0 md:grid-cols-[320px_1fr]">
                            <img
                                src="{{ $game->thumbnail_url ?: $fallbackImage }}"
                                alt="{{ $game->title }}"
                                class="h-52 w-full object-cover md:h-full"
                                loading="lazy"
                                decoding="async"
                            >

                            <div class="flex flex-col justify-between p-6">
                                <div>
                                    <h2 class="text-3xl font-black text-white">{{ $game->title }}</h2>
                                    <p class="mt-3 line-clamp-3 text-gray-400">
                                        {{ $game->detail->short_description ?? $game->description }}
                                    </p>

                                    @if($game->categories->isNotEmpty())
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($game->categories->take(4) as $category)
                                                <span class="rounded bg-[#2a475e] px-3 py-1 text-xs font-bold text-gray-200">
                                                    {{ $category->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                <div class="mt-6 flex flex-wrap items-center justify-between gap-4">
                                    <div>
                                        <div class="text-[#66c0f4]">
                                            {{ $game->publisher->name ?? 'Unknown Publisher' }}
                                        </div>
                                        @if($sort === 'popular')
                                            <div class="mt-2 inline-flex items-center gap-2 rounded bg-[#0b2a44]/90 px-3 py-1 text-xs font-black uppercase text-[#66c0f4]">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19V5m0 14h16M8 17v-5m4 5V8m4 9v-8"/>
                                                </svg>
                                                {{ number_format((int) ($game->paid_purchases_count ?? 0), 0, ',', '.') }} dibeli
                                            </div>
                                        @endif
                                    </div>

                                    <div class="text-2xl font-black text-white">
                                        {{ $formatPrice($game->price) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                </a>
            @empty
                <div class="rounded-2xl border border-[#2a475e] bg-[#16202d] py-20 text-center">
                    <h2 class="text-4xl font-black text-gray-300">Game not found</h2>
                </div>
            @endforelse
        </div>

        <div class="mt-12 flex justify-center">
            {{ $games->links() }}
        </div>
    </main>
@endsection
