@extends('layouts.store')

@section('title', 'Riwayat Payment - PlayMart')

@section('content')

    <main class="page-shell max-w-6xl">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-10">
            <div>
                <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-3">
                    Payment
                </p>
                <h1 class="text-4xl font-bold mb-2">
                    Riwayat Payment
                </h1>
                <p class="text-gray-400">
                    Semua transaksi game yang pernah kamu checkout.
                </p>
            </div>

            <a href="{{ route('cart.index') }}" class="steam-blue px-6 py-3 rounded-xl font-bold hover:opacity-90 transition">
                Buka Cart
            </a>
        </div>

        @if($payments->count() > 0)
            <div class="space-y-4">
                @foreach($payments as $payment)
                    <a
                        href="{{ route('payments.show', $payment) }}"
                        class="glass border border-[#2a475e] rounded-2xl p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-5 hover:border-[#66c0f4] transition"
                    >
                        <div>
                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                <h2 class="text-2xl font-bold">
                                    {{ $payment->payment_code }}
                                </h2>
                                <span class="bg-green-600/20 border border-green-500/50 text-green-200 px-3 py-1 rounded-lg text-xs font-bold uppercase">
                                    {{ $payment->status }}
                                </span>
                            </div>
                            <p class="text-gray-400">
                                {{ $payment->items_count }} item - {{ $payment->paid_at?->format('d M Y H:i') ?? $payment->created_at->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div class="text-left md:text-right">
                            <p class="text-gray-400 text-sm">
                                {{ ucwords(str_replace('_', ' ', $payment->method)) }}
                            </p>
                            <p class="text-3xl font-bold text-[#66c0f4]">
                                Rp {{ number_format($payment->display_total, 0, ',', '.') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $payments->links() }}
            </div>
        @else
            <div class="glass border border-[#2a475e] rounded-3xl p-8 text-center sm:p-16">
                <h2 class="mb-4 text-3xl font-bold sm:text-4xl">
                    Belum ada payment
                </h2>
                <p class="text-gray-400 mb-8">
                    Checkout game dari cart untuk membuat transaksi pertama.
                </p>
                <a href="{{ route('home') }}" class="steam-blue px-8 py-4 rounded-xl font-bold inline-block hover:opacity-90 transition">
                    Browse Games
                </a>
            </div>
        @endif
    </main>
@endsection
