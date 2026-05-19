@php
use Illuminate\Support\Str;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Cart - PlayMart</title>

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


<!-- PAGE -->
<section class="max-w-7xl mx-auto px-6 py-12">

    @if(session('success'))
        <div class="mb-6 glass border border-green-500/50 rounded-2xl p-5 text-green-200">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 glass border border-red-500/50 rounded-2xl p-5 text-red-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-10">

        <div>
            <h1 class="text-4xl font-bold mb-2">
                Your Cart
            </h1>

            <p class="text-gray-400">
                {{ $totalItems }} game in your cart
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('payments.history') }}"
               class="bg-[#2a475e] px-6 py-3 rounded-xl font-semibold hover:bg-[#35617d] transition">
                Riwayat Payment
            </a>

            <a href="/"
               class="steam-blue px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
                Continue Shopping
            </a>
        </div>

    </div>


    @if($carts->count() > 0)

        <form id="checkout-selection-form" action="{{ route('payments.checkout') }}" method="GET">
            <input type="hidden" name="selection" value="1">
        </form>

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- LEFT -->
            <div class="lg:col-span-2 space-y-5">

                @foreach($carts as $cart)
                    @php
                        $discount = $cart->game->discount_percent;
                        $finalPrice = $cart->game->final_price;
                    @endphp

                    <div class="glass border border-[#2a475e] rounded-2xl overflow-hidden relative">
                        <label class="absolute left-4 top-4 z-10 flex items-center gap-2 rounded-xl border border-[#66c0f4]/50 bg-[#07111d]/90 px-3 py-2 text-sm font-bold text-white shadow-lg shadow-black/30">
                            <input
                                type="checkbox"
                                name="cart_ids[]"
                                value="{{ $cart->id }}"
                                form="checkout-selection-form"
                                checked
                                data-cart-select
                                data-cart-price="{{ $finalPrice }}"
                                class="rounded border-[#316282] bg-[#0f1923] text-[#06bfff] focus:ring-[#66c0f4]"
                            >
                            Checkout
                        </label>

                        <div class="flex flex-col md:flex-row">

                            <!-- IMAGE -->
                            <img
                                src="{{ $cart->game->thumbnail_url }}"
                                class="w-full md:w-72 h-52 object-cover"
                            >

                            <!-- CONTENT -->
                            <div class="flex-1 p-6 flex flex-col justify-between">

                                <div>

                                    <h2 class="text-3xl font-bold mb-3">
                                        {{ $cart->game->title }}
                                    </h2>

                                    <p class="text-gray-400 leading-relaxed mb-5">
                                        {{ Str::limit($cart->game->description, 150) }}
                                    </p>

                                    <div class="text-[#66c0f4] text-sm font-semibold">
                                        Publisher:
                                        {{ $cart->game->publisher->name ?? 'Unknown' }}
                                    </div>

                                </div>

                                <div class="flex items-center justify-between mt-8">

                                    <div>
                                        @if($discount > 0)
                                            <div class="mb-1 flex items-center gap-2">
                                                <span class="rounded bg-[#4c6b22] px-2 py-1 text-xs font-black text-[#beee11]">-{{ $discount }}%</span>
                                                <span class="text-sm text-gray-500 line-through">Rp {{ number_format($cart->game->price, 0, ',', '.') }}</span>
                                            </div>
                                        @endif
                                        <div class="text-3xl font-bold text-[#66c0f4]">
                                            Rp {{ number_format($finalPrice, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Qty: 1
                                    </div>

                                    <!-- REMOVE -->
                                    <form action="{{ route('cart.remove', $cart->id) }}"
                                          method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="bg-red-600 hover:bg-red-700 px-5 py-3 rounded-xl font-semibold transition">
                                            Remove
                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>


            <!-- RIGHT -->
            <div>

                <div class="glass border border-[#2a475e] rounded-2xl p-6 sticky top-24">

                    <h2 class="text-2xl font-bold mb-6">
                        Cart Summary
                    </h2>

                    <div class="space-y-4 mb-6">

                        <div class="flex justify-between text-gray-300">
                            <span>Item Dipilih</span>
                            <span data-selected-count>{{ $totalItems }}</span>
                        </div>

                        <div class="flex justify-between text-gray-300">
                            <span>Tax</span>
                            <span>Rp 0</span>
                        </div>

                    </div>

                    <div class="border-t border-[#2a475e] pt-5 mb-6">

                        <div class="flex justify-between items-center">

                            <span class="text-xl font-semibold">
                                Total
                            </span>

                            <span class="text-3xl font-bold text-[#66c0f4]">
                                Rp <span data-selected-total>{{ number_format($totalPrice, 0, ',', '.') }}</span>
                            </span>

                        </div>

                    </div>

                    <button
                        type="submit"
                        form="checkout-selection-form"
                        data-checkout-button
                        class="block text-center w-full steam-blue py-4 rounded-xl font-bold text-lg hover:opacity-90 transition disabled:cursor-not-allowed disabled:opacity-50">
                        Checkout
                    </button>

                </div>

            </div>

        </div>

    @else

        <div class="glass border border-[#2a475e] rounded-3xl p-16 text-center">

            <h2 class="text-4xl font-bold mb-4">
                Your cart is empty
            </h2>

            <p class="text-gray-400 mb-8">
                Looks like you haven't added any games yet.
            </p>

            <a href="/"
               class="steam-blue px-8 py-4 rounded-xl font-bold inline-block hover:opacity-90 transition">
                Browse Games
            </a>

        </div>

    @endif

</section>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkboxes = Array.from(document.querySelectorAll('[data-cart-select]'));
        const countTarget = document.querySelector('[data-selected-count]');
        const totalTarget = document.querySelector('[data-selected-total]');
        const checkoutButton = document.querySelector('[data-checkout-button]');

        const formatRupiah = (value) => Number(value || 0).toLocaleString('id-ID');

        const syncSelection = () => {
            const selected = checkboxes.filter((checkbox) => checkbox.checked);
            const total = selected.reduce((sum, checkbox) => sum + Number(checkbox.dataset.cartPrice || 0), 0);

            if (countTarget) {
                countTarget.textContent = selected.length;
            }

            if (totalTarget) {
                totalTarget.textContent = formatRupiah(total);
            }

            if (checkoutButton) {
                checkoutButton.disabled = selected.length === 0;
            }
        };

        checkboxes.forEach((checkbox) => checkbox.addEventListener('change', syncSelection));
        syncSelection();
    });
</script>

<x-store-footer />

</body>
</html>
