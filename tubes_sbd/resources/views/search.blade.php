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

        <div class="space-y-2">
            @forelse($games as $game)
                <a href="{{ url('/game/' . $game->game_id) }}" class="block">
                    <article class="group overflow-hidden rounded-lg border border-transparent bg-[#16202d]/60 p-2 transition hover:border-[#66c0f4] hover:bg-[#1f2f42]">
                        <div class="flex items-center gap-4">
                            {{-- Thumbnail --}}
                            <img
                                src="{{ $game->thumbnail_url ?: $fallbackImage }}"
                                alt="{{ $game->title }}"
                                class="h-16 w-40 rounded object-cover shadow-lg"
                                loading="lazy"
                            >

                            {{-- Game Info --}}
                            <div class="flex flex-1 flex-col justify-center">
                                <h2 class="text-lg font-bold text-white group-hover:text-[#66c0f4]">{{ $game->title }}</h2>
                                
                                <div class="flex items-center gap-3 mt-1">
                                    {{-- Platforms --}}
                                    <div class="flex items-center gap-1.5 text-gray-500">
                                        @foreach($game->platforms as $platform)
                                            <div title="{{ $platform->name }}" class="w-4 h-4 opacity-60">
                                                {!! $platform->icon !!}
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <span class="text-xs text-gray-500">•</span>
                                    
                                    {{-- Release Date --}}
                                    <span class="text-xs text-gray-500">
                                        {{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('d M Y') : 'TBA' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Pricing & Review --}}
                            <div class="flex items-center gap-6 pr-4">
                                {{-- Review Summary (Simple) --}}
                                <div class="hidden text-right md:block">
                                    @if($game->paid_purchases_count > 0)
                                        <div class="text-xs font-bold text-[#66c0f4] uppercase tracking-tighter">
                                            Popular
                                        </div>
                                    @endif
                                </div>

                                {{-- Price --}}
                                <div class="flex flex-col items-end min-w-[100px]">
                                    @php
                                        $discount = $game->detail->discount ?? 0;
                                        $price = (float) $game->price;
                                    @endphp
                                    
                                    @if($discount > 0)
                                        <div class="flex items-center gap-2">
                                            <span class="bg-[#4c6b22] text-[#beee11] text-xs font-bold px-1.5 py-0.5 rounded">-{{ $discount }}%</span>
                                            <span class="text-xs text-gray-500 line-through">Rp {{ number_format($price, 0, ',', '.') }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="text-sm font-medium text-white">
                                        {{ $discount > 0 ? 'Rp ' . number_format($price * (1 - $discount/100), 0, ',', '.') : $formatPrice($price) }}
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
