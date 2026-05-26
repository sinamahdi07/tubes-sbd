@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - PlayMart</title>
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
    <x-store-nav active="cart" />

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="mb-10">
            <p class="text-[#66c0f4] uppercase tracking-[0.25em] text-xs font-bold mb-3">
                Payment
            </p>
            <h1 class="text-4xl font-bold mb-2">
                Checkout Game
            </h1>
            <p class="text-gray-400">
                Pilih metode pembayaran untuk menyelesaikan pembelian game.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 glass border border-red-500/50 rounded-2xl p-5 text-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('payments.store') }}" method="POST" class="grid lg:grid-cols-3 gap-8">
            @csrf
            @foreach($summary['items'] as $item)
                <input type="hidden" name="cart_ids[]" value="{{ $item['cart']->id }}">
            @endforeach

            <section class="lg:col-span-2 space-y-5">
                @foreach($summary['items'] as $item)
                    <div class="glass border border-[#2a475e] rounded-2xl overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            <img
                                src="{{ $item['game']->thumbnail_url }}"
                                class="w-full md:w-64 h-48 object-cover"
                                alt="{{ $item['game']->title }}"
                            >

                            <div class="flex-1 p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <div>
                                        <h2 class="text-2xl font-bold mb-2">
                                            {{ $item['game']->title }}
                                        </h2>
                                        <p class="text-gray-400 leading-relaxed">
                                            {{ Str::limit($item['game']->description, 140) }}
                                        </p>
                                    </div>

                                    <div class="text-right shrink-0">
                                        <div class="text-sm text-gray-400">
                                            Qty: {{ $item['quantity'] }}
                                        </div>
                                        @if($item['discount_percent'] > 0)
                                            <div class="mt-1 flex justify-end gap-2 text-sm">
                                                <span class="rounded bg-[#4c6b22] px-2 py-0.5 font-black text-[#beee11]">-{{ $item['discount_percent'] }}%</span>
                                                <span class="text-gray-500 line-through">Rp {{ number_format($item['line_subtotal'], 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        <div class="text-2xl font-bold text-[#66c0f4]">
                                            Rp {{ number_format($item['line_total'], 0, ',', '.') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>

            <aside>
                <div class="glass border border-[#2a475e] rounded-2xl p-6 sticky top-24">
                    <h2 class="text-2xl font-bold mb-6">
                        Ringkasan
                    </h2>

                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-gray-300">
                            <span>Total Item</span>
                            <span>{{ $summary['quantity'] }}</span>
                        </div>
                        <div class="flex justify-between text-gray-300">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($summary['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-300">
                            <span>Diskon</span>
                            <span>Rp {{ number_format($summary['discount_total'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="border-t border-[#2a475e] pt-5 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-semibold">
                                Total Bayar
                            </span>
                            <span class="text-3xl font-bold text-[#66c0f4]">
                                Rp {{ number_format($summary['total'], 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <label for="method" class="block text-sm font-semibold text-gray-300 mb-2">
                        Metode Pembayaran
                    </label>
                    <select
                        id="method"
                        name="method"
                        class="w-full bg-[#0f1923] border border-[#316282] focus:border-[#66c0f4] outline-none rounded-xl px-4 py-3 mb-6 text-white"
                        required
                    >
                        @foreach($paymentMethods as $value => $label)
                            <option value="{{ $value }}" @selected(old('method') === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <button
                        type="submit"
                        class="w-full steam-blue py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
                        Bayar Sekarang
                    </button>

                    <a
                        href="{{ route('cart.index') }}"
                        class="block text-center mt-4 text-gray-300 hover:text-white">
                        Kembali ke cart
                    </a>
                </div>
            </aside>
        </form>
    </main>
    <x-store-footer />
</body>
</html>
