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

<!-- NAVBAR -->
<nav class="bg-[#171a21] border-b border-[#2a475e] sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        <a href="/" class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full steam-blue flex items-center justify-center font-bold text-xl">
                G
            </div>

            <h1 class="text-2xl font-bold tracking-wide text-[#66c0f4]">
                PlayMart
            </h1>
        </a>

        <div class="flex items-center gap-4">
            <x-store-user-menu />
        </div>
    </div>
</nav>


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

        <div class="grid lg:grid-cols-3 gap-8">

            <!-- LEFT -->
            <div class="lg:col-span-2 space-y-5">

                @foreach($carts as $cart)

                    <div class="glass border border-[#2a475e] rounded-2xl overflow-hidden">

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

                                    <div class="text-3xl font-bold text-[#66c0f4]">
                                        Rp {{ number_format($cart->game->price, 0, ',', '.') }}
                                    </div>

                                    <div class="text-sm text-gray-400">
                                        Qty: {{ $cart->quantity }}
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
                            <span>Total Item</span>
                            <span>{{ $totalItems }}</span>
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
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>

                        </div>

                    </div>

                    <a
                        href="{{ route('payments.checkout') }}"
                        class="block text-center w-full steam-blue py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
                        Checkout
                    </a>

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

</body>
</html>
