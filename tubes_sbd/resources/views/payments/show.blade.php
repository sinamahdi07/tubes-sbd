<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment {{ $payment->payment_code }} - PlayMart</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            background: #1b2838;
            color: white;
            font-family: Arial, Helvetica, sans-serif;
        }

        .steam-blue {
            background: linear-gradient(90deg,#06bfff,#2d73ff);
        }

        .glass {
            background: rgba(255,255,255,0.04);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    <x-store-nav />

    <main class="max-w-5xl mx-auto px-6 py-12">
        @if(session('success'))
            <div class="mb-6 glass border border-green-500/50 rounded-2xl p-5 text-green-200">
                {{ session('success') }}
            </div>
        @endif

        <section class="glass border border-[#2a475e] rounded-3xl overflow-hidden">
            <div class="p-8 border-b border-[#2a475e] flex flex-col md:flex-row md:items-center md:justify-between gap-6">
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

            <div class="p-8">
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

                <div class="space-y-4">
                    @foreach($payment->items as $item)
                        <div class="bg-[#0f1923] border border-[#2a475e] rounded-2xl p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h2 class="text-xl font-bold">
                                    {{ $item->title }}
                                </h2>
                                <p class="text-gray-400 text-sm">
                                    Qty {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="text-2xl font-bold text-[#66c0f4]">
                                Rp {{ number_format($item->line_total, 0, ',', '.') }}
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

                <div class="mt-8 flex flex-wrap gap-3">
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
    <x-store-footer />
</body>
</html>
