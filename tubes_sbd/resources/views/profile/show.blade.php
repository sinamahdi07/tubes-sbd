@extends('layouts.store')

@section('title', 'Detail Profile - PlayMart')

@push('styles')
    <style>
        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
        }
    </style>
@endpush

@section('content')
    <main class="max-w-7xl mx-auto px-6 py-12">
        <section class="glass border border-[#2a475e] rounded-3xl overflow-hidden mb-8">
            <div class="p-8 bg-gradient-to-r from-[#0f1923] to-[#16202d]">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-center gap-5">
                        <div class="w-20 h-20 rounded-full steam-blue flex items-center justify-center text-4xl font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-2">
                                Detail Profile
                            </p>
                            <h1 class="text-4xl font-bold">
                                {{ $user->name }}
                            </h1>
                            <p class="text-gray-400 mt-2">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="steam-blue px-6 py-3 rounded-xl font-bold hover:opacity-90 transition text-center">
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-5 p-8">
                <a href="{{ route('profile.games') }}" class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-6 hover:border-[#66c0f4] transition">
                    <p class="text-gray-400 text-sm mb-2">Game Dibeli</p>
                    <p class="text-4xl font-bold text-[#66c0f4]">{{ $purchasedGamesCount }}</p>
                </a>

                <a href="{{ route('friends.index') }}" class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-6 hover:border-[#66c0f4] transition">
                    <p class="text-gray-400 text-sm mb-2">Teman</p>
                    <p class="text-4xl font-bold text-[#66c0f4]">{{ $friendCount }}</p>
                </a>

                <a href="{{ route('payments.history') }}" class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-6 hover:border-[#66c0f4] transition">
                    <p class="text-gray-400 text-sm mb-2">Transaksi Paid</p>
                    <p class="text-4xl font-bold text-[#66c0f4]">{{ $paidPayments->count() }}</p>
                </a>
            </div>
        </section>

        <section class="glass border border-[#2a475e] rounded-3xl p-8">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-2xl font-bold">
                        Payment Terbaru
                    </h2>
                    <p class="text-gray-400 mt-1">
                        Ringkasan pembelian terakhir dari akun ini.
                    </p>
                </div>
                <a href="{{ route('profile.games') }}" class="text-[#66c0f4] font-semibold hover:text-white">
                    Lihat Game Dibeli
                </a>
            </div>

            @if($latestPayments->count() > 0)
                <div class="space-y-4">
                    @foreach($latestPayments as $payment)
                        <a href="{{ route('payments.show', $payment) }}" class="block bg-[#0f1923] border border-[#2a475e] rounded-2xl p-5 hover:border-[#66c0f4] transition">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div>
                                    <h3 class="font-bold text-xl">{{ $payment->payment_code }}</h3>
                                    <p class="text-gray-400 text-sm">
                                        {{ $payment->items->sum('quantity') }} item - {{ $payment->paid_at?->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <p class="text-2xl font-bold text-[#66c0f4]">
                                    Rp {{ number_format($payment->display_total, 0, ',', '.') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-8 text-center">
                    <h3 class="text-2xl font-bold mb-2">Belum ada pembelian</h3>
                    <p class="text-gray-400 mb-6">Game yang sudah dibeli akan muncul di sini.</p>
                    <a href="{{ route('home') }}" class="steam-blue px-6 py-3 rounded-xl font-bold inline-block hover:opacity-90 transition">
                        Browse Games
                    </a>
                </div>
            @endif
        </section>
    </main>
    <x-store-footer />
</body>
</html>
