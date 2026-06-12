@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.store')

@section('title', 'Game Dibeli - PlayMart')

@section('content')
    <main class="page-shell">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
            <div>
                <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-3">
                    Library
                </p>
                <h1 class="text-4xl font-bold mb-2">
                    Game Dibeli
                </h1>
                <p class="text-gray-400">
                    Semua game yang sudah berhasil kamu checkout.
                </p>
            </div>

            <a href="{{ route('profile.show') }}" class="bg-[#2a475e] hover:bg-[#35617d] px-6 py-3 rounded-xl font-bold transition">
                Detail Profile
            </a>
        </div>

        @if($payments->count() > 0)
            <div class="space-y-8">
                @foreach($payments as $payment)
                    <section class="glass border border-[#2a475e] rounded-3xl overflow-hidden">
                        <div class="p-6 border-b border-[#2a475e] flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-2xl font-bold">{{ $payment->payment_code }}</h2>
                                <p class="text-gray-400 text-sm">
                                    Dibeli pada {{ $payment->paid_at?->format('d M Y H:i') }} - {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                                </p>
                            </div>
                            <a href="{{ route('payments.show', $payment) }}" class="text-[#66c0f4] font-semibold hover:text-white">
                                Lihat Receipt
                            </a>
                        </div>

                        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5 p-6">
                            @foreach($payment->items as $item)
                                <article class="bg-[#0f1923] border border-[#2a475e] rounded-2xl overflow-hidden">
                                    <img
                                        src="{{ $item->game->thumbnail_url ?? 'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=80&w=1200&auto=format&fit=crop' }}"
                                        class="w-full h-44 object-cover"
                                        alt="{{ $item->title }}"
                                    >
                                    <div class="p-5">
                                        <h3 class="text-xl font-bold mb-2">
                                            {{ $item->title }}
                                        </h3>
                                        <p class="text-gray-400 text-sm mb-4">
                                            Publisher: {{ $item->game->publisher->name ?? 'Unknown' }}
                                        </p>
                                        <div class="flex items-center justify-between gap-4">
                                            <span class="text-sm text-gray-400">
                                                Qty {{ $item->quantity }}
                                            </span>
                                            <span class="text-xl font-bold text-[#66c0f4]">
                                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
                                            </span>
                                        </div>

                                        @if($item->game)
                                            <a href="{{ url('/game/' . $item->game->game_id) }}" class="mt-5 block text-center steam-blue py-3 rounded-xl font-bold hover:opacity-90 transition">
                                                View Game
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @else
            <div class="glass border border-[#2a475e] rounded-3xl p-8 text-center sm:p-16">
                <h2 class="mb-4 text-3xl font-bold sm:text-4xl">
                    Belum ada game dibeli
                </h2>
                <p class="text-gray-400 mb-8">
                    Setelah checkout berhasil, game akan muncul di library ini.
                </p>
                <a href="{{ route('home') }}" class="steam-blue px-8 py-4 rounded-xl font-bold inline-block hover:opacity-90 transition">
                    Browse Games
                </a>
            </div>
        @endif
    </main>
@endsection
