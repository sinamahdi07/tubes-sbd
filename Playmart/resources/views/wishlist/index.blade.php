@extends('layouts.store')

@section('title', 'Wishlist - PlayMart')

@section('content')
    @php
        use Illuminate\Support\Str;

        $formatPrice = fn ($value) => (float) $value <= 0
            ? 'Gratis'
            : 'Rp ' . number_format((float) $value, 0, ',', '.');
    @endphp

    <main class="mx-auto max-w-7xl px-6 py-12">
        @if(session('success'))
            <div class="mb-6 rounded-2xl border border-green-500/50 bg-green-950/30 p-5 text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 rounded-2xl border border-red-500/50 bg-red-950/30 p-5 text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-10 flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Saved Games</p>
                <h1 class="mt-3 text-4xl font-black text-white md:text-5xl">Wishlist</h1>
                <p class="mt-3 text-gray-400">
                    {{ $totalItems }} game tersimpan di wishlist kamu.
                </p>
            </div>

            <a href="{{ route('home') }}" class="steam-blue inline-flex items-center justify-center rounded-xl px-6 py-3 font-black text-white transition hover:brightness-110">
                Continue Shopping
            </a>
        </div>

        @if($wishlists->count() > 0)
            <div class="grid gap-5">
                @foreach($wishlists as $wishlist)
                    @php
                        $game = $wishlist->game;
                        $discount = $game->discount_percent;
                        $finalPrice = $game->final_price;
                    @endphp

                    <article class="overflow-hidden rounded-2xl border border-[#2a475e] bg-[#16202d]/80 shadow-xl shadow-black/20 transition hover:border-[#66c0f4]/70 hover:bg-[#1b2b3d]">
                        <div class="grid gap-0 md:grid-cols-[280px_minmax(0,1fr)_220px]">
                            <a href="{{ url('/game/' . $game->game_id) }}" class="block overflow-hidden bg-black">
                                <img
                                    src="{{ $game->thumbnail_url ?: 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=70&w=900&auto=format&fit=crop' }}"
                                    alt="{{ $game->title }}"
                                    class="h-56 w-full object-cover transition duration-300 hover:scale-105 md:h-full"
                                    loading="lazy"
                                >
                            </a>

                            <div class="p-6">
                                <a href="{{ url('/game/' . $game->game_id) }}" class="text-2xl font-black text-white transition hover:text-[#66c0f4]">
                                    {{ $game->title }}
                                </a>

                                <p class="mt-3 max-w-3xl text-sm leading-relaxed text-gray-400">
                                    {{ Str::limit($game->description ?: $game->detail?->short_description ?: 'Game pilihan kamu di PlayMart.', 180) }}
                                </p>

                                <div class="mt-5 flex flex-wrap gap-2">
                                    @foreach($game->genres->take(4) as $genre)
                                        <span class="rounded-lg bg-[#2a475e] px-3 py-1 text-xs font-black text-gray-200">
                                            {{ $genre->name }}
                                        </span>
                                    @endforeach
                                </div>

                                <p class="mt-5 text-sm font-semibold text-[#66c0f4]">
                                    Publisher: {{ $game->publisher->name ?? 'Unknown' }}
                                </p>
                            </div>

                            <div class="flex flex-col justify-between border-t border-[#2a475e] p-6 md:border-l md:border-t-0">
                                <div>
                                    @if($discount > 0)
                                        <div class="mb-2 flex items-center gap-2">
                                            <span class="rounded bg-[#4c6b22] px-2 py-1 text-xs font-black text-[#beee11]">-{{ $discount }}%</span>
                                            <span class="text-sm text-gray-500 line-through">{{ $formatPrice($game->price) }}</span>
                                        </div>
                                    @endif
                                    <div class="text-2xl font-black text-[#66c0f4]">
                                        {{ $formatPrice($finalPrice) }}
                                    </div>
                                </div>

                                <div class="mt-6 space-y-3">
                                    <form action="{{ route('cart.add', $game) }}" method="POST">
                                        @csrf
                                        <button class="steam-blue w-full rounded-xl px-5 py-3 font-black text-white transition hover:brightness-110">
                                            Add to Cart
                                        </button>
                                    </form>

                                    <form action="{{ route('wishlist.toggle', $game) }}" method="POST">
                                        @csrf
                                        <button class="w-full rounded-xl border border-[#2a475e] bg-[#0f1923] px-5 py-3 font-black text-gray-200 transition hover:border-red-400 hover:text-red-200">
                                            Remove
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-3xl border border-[#2a475e] bg-[#16202d]/80 p-16 text-center">
                <h2 class="text-4xl font-black text-white">Wishlist masih kosong</h2>
                <p class="mt-4 text-gray-400">Simpan game dari halaman detail supaya muncul di sini.</p>
                <a href="{{ route('home') }}" class="steam-blue mt-8 inline-flex rounded-xl px-8 py-4 font-black text-white transition hover:brightness-110">
                    Browse Games
                </a>
            </div>
        @endif
    </main>
@endsection
