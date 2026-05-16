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

            <span class="text-sm text-gray-300">
                Halo,
                <span class="text-[#66c0f4] font-semibold">
                    {{ auth()->user()->name }}
                </span>
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    Logout
                </button>
            </form>

        </div>
    </div>
</nav>


<!-- PAGE -->
<section class="max-w-7xl mx-auto px-6 py-12">

    <div class="flex items-center justify-between mb-10">

        <div>
            <h1 class="text-4xl font-bold mb-2">
                Your Cart
            </h1>

            <p class="text-gray-400">
                {{ $carts->count() }} game in your cart
            </p>
        </div>

        <a href="/"
           class="steam-blue px-6 py-3 rounded-xl font-semibold hover:opacity-90 transition">
            Continue Shopping
        </a>

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
                            <span>{{ $carts->count() }}</span>
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
                                Rp {{ number_format($carts->sum(fn($cart) => $cart->game->price), 0, ',', '.') }}
                            </span>

                        </div>

                    </div>

                    <button
                        class="w-full steam-blue py-4 rounded-xl font-bold text-lg hover:opacity-90 transition">
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

</body>
</html>