@extends('layouts.store')

@section('title', 'Payment ' . $payment->payment_code . ' - PlayMart')

@section('content')
    <main class="page-shell max-w-5xl">
        @if(session('success'))
            <div class="mb-6 glass border border-green-500/50 rounded-2xl p-5 text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="glass border border-[#2a475e] rounded-3xl overflow-hidden">
            <div class="flex flex-col gap-6 border-b border-[#2a475e] p-5 sm:p-8 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-3">
                        Receipt
                    </p>
                    <h1 class="text-4xl font-bold mb-2">
                        Payment Berhasil
                    </h1>
                    <p class="text-gray-400">
                        Kode transaksi: {{ $payment->payment_code }}
                    </p>
                </div>

                <div class="text-left md:text-right">
                    <span class="inline-block bg-green-600/20 border border-green-500/50 text-green-200 px-4 py-2 rounded-xl font-bold uppercase text-sm">
                        {{ $payment->status }}
                    </span>
                    <p class="text-gray-400 mt-3">
                        {{ $payment->paid_at?->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="p-5 sm:p-8">
                <div class="grid md:grid-cols-3 gap-5 mb-8">
                    <div class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-5">
                        <p class="text-gray-400 text-sm mb-2">Metode</p>
                        <p class="font-bold">{{ ucwords(str_replace('_', ' ', $payment->method)) }}</p>
                    </div>
                    <div class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-5">
                        <p class="text-gray-400 text-sm mb-2">Total Item</p>
                        <p class="font-bold">{{ $payment->items->sum('quantity') }}</p>
                    </div>
                    <div class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-5">
                        <p class="text-gray-400 text-sm mb-2">Total Bayar</p>
                        <p class="font-bold text-[#66c0f4]">Rp {{ number_format($payment->display_total, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="space-y-2">
                    @foreach($payment->items as $item)
                        @php
                            $game      = $item->game;
                            $thumb     = $game?->thumbnail_url
                                         ?? 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?q=50&w=400&auto=format&fit=crop';
                            $publisher = $game?->publisher?->name ?? 'Indie';
                            $disc      = (int) ($item->discount_percent ?? 0);
                            $unitPrice = (float) ($item->unit_price ?? $item->price ?? 0);
                            $lineTotal = (float) $item->line_total;
                            $gameUrl   = $game ? url('/game/' . $game->game_id) : '#';
                        @endphp

                        <div class="flex items-center gap-4 bg-[#0f1923] border border-[#2a475e] rounded-xl px-4 py-3
                                    hover:border-[#66c0f4]/40 transition-colors group">

                            {{-- Thumbnail kecil --}}
                            <a href="{{ $gameUrl }}" class="flex-shrink-0 w-20 h-12 rounded-lg overflow-hidden bg-[#07111d]">
                                <img src="{{ $thumb }}"
                                     alt="{{ $item->title }}"
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                     loading="lazy" decoding="async">
                            </a>

                            {{-- Nama + Publisher --}}
                            <div class="flex-1 min-w-0">
                                <a href="{{ $gameUrl }}" class="block hover:text-[#66c0f4] transition-colors">
                                    <p class="text-sm font-black text-white truncate leading-tight">{{ $item->title }}</p>
                                </a>
                                <p class="text-[11px] text-gray-500 font-semibold uppercase tracking-wider mt-0.5 truncate">{{ $publisher }}</p>
                                @if($disc > 0)
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-black bg-[#4c6b22] text-[#beee11]">-{{ $disc }}%</span>
                                        <span class="text-[11px] text-gray-500 line-through">Rp {{ number_format($unitPrice, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Harga --}}
                            <div class="flex-shrink-0 text-right">
                                <p class="text-base font-black text-[#66c0f4] whitespace-nowrap">
                                    Rp {{ number_format($lineTotal, 0, ',', '.') }}
                                </p>
                                <p class="text-[11px] text-gray-500 mt-0.5">Qty {{ $item->quantity }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>



                <div class="border-t border-[#2a475e] mt-8 pt-6 space-y-3">
                    <div class="flex justify-between text-gray-300">
                        <span>Subtotal</span>
                        <span>Rp {{ number_format($payment->display_subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-300">
                        <span>Diskon</span>
                        <span>Rp {{ number_format($payment->display_discount_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold">
                        <span>Total</span>
                        <span class="text-[#66c0f4]">Rp {{ number_format($payment->display_total, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="mt-8 grid gap-3 sm:flex sm:flex-wrap">
                    <a href="{{ route('home') }}" class="steam-blue px-6 py-3 rounded-xl font-bold hover:opacity-90 transition">
                        Beli Game Lagi
                    </a>
                    <a href="{{ route('payments.history') }}" class="bg-[#2a475e] hover:bg-[#35617d] px-6 py-3 rounded-xl font-bold transition">
                        Lihat Riwayat
                    </a>
                </div>
            </div>
        </section>
    </main>
@endsection
