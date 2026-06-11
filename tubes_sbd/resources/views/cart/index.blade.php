@php
use Illuminate\Support\Str;
@endphp

@extends('layouts.store')

@section('title', 'My Cart - PlayMart')

@push('styles')
    <style>
        .cart-container-main {
            background: radial-gradient(circle at top right, rgba(17, 141, 255, 0.05), transparent 40%),
                        radial-gradient(circle at bottom left, rgba(29, 185, 84, 0.05), transparent 40%);
        }
        .cart-glass-panel {
            background: rgba(255, 255, 255, 0.02);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
        }
        .item-card {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }
        .item-card:hover {
            border-color: rgba(102, 192, 244, 0.2);
            background: rgba(255, 255, 255, 0.04);
        }
        .checkout-label {
            background: rgba(7, 17, 29, 0.8);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(102, 192, 244, 0.3);
            box-shadow: 0 8px 20px rgba(0,0,0,0.4);
        }
        .summary-card {
            background: linear-gradient(145deg, rgba(15, 25, 35, 0.8), rgba(5, 10, 20, 0.9));
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 2rem;
        }
    </style>
@endpush

@section('content')
    <div class="cart-container-main min-h-screen py-10 lg:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 flex items-center gap-3 text-emerald-400 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 animate-in fade-in slide-in-from-top-4 duration-500 rounded-2xl bg-red-500/10 border border-red-500/20 p-4 flex items-center gap-3 text-red-400 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-black tracking-tighter text-white">Your <span class="text-[#1DB954]">Cart</span></h1>
                    <p class="text-gray-400 mt-2 font-medium">Selesaikan pembelian game impianmu dan mulai petualangan baru.</p>
                </div>
                
                <div class="flex items-center gap-3">
                    <a href="{{ route('payments.history') }}" class="px-6 py-3 rounded-xl bg-white/5 border border-white/5 text-xs font-black text-white/60 uppercase tracking-widest hover:bg-white/10 transition-all">
                        History
                    </a>
                    <a href="/" class="px-6 py-3 rounded-xl bg-white text-black font-black text-xs uppercase tracking-widest shadow-xl transition-all hover:scale-105 active:scale-95">
                        Continue Shopping
                    </a>
                </div>
            </div>

            @if($carts->count() > 0)
                <form id="checkout-selection-form" action="{{ route('payments.checkout') }}" method="GET">
                    <input type="hidden" name="selection" value="1">
                </form>

                <div class="grid lg:grid-cols-3 gap-10">
                    <!-- Items List -->
                    <div class="lg:col-span-2 space-y-6">
                        @foreach($carts as $cart)
                            @php
                                $discount = $cart->game->discount_percent;
                                $finalPrice = $cart->game->final_price;
                            @endphp
                            <div class="item-card overflow-hidden relative group">
                                <!-- Checkout Toggle -->
                                <label class="checkout-label absolute left-6 top-6 z-10 flex items-center gap-3 rounded-2xl px-4 py-2.5 text-xs font-black text-white cursor-pointer transition-transform group-hover:scale-105">
                                    <input
                                        type="checkbox"
                                        name="cart_ids[]"
                                        value="{{ $cart->id }}"
                                        form="checkout-selection-form"
                                        checked
                                        data-cart-select
                                        data-cart-price="{{ $finalPrice }}"
                                        class="h-4 w-4 rounded-lg border-white/20 bg-black/40 text-[#1DB954] focus:ring-[#1DB954]"
                                    >
                                    CHECKOUT
                                </label>

                                <div class="flex flex-col sm:flex-row">
                                    <!-- Image Section -->
                                    <div class="sm:w-64 h-48 sm:h-auto shrink-0 relative overflow-hidden">
                                        <img src="{{ $cart->game->thumbnail_url }}" alt="{{ $cart->game->title }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                    </div>

                                    <!-- Details Section -->
                                    <div class="flex-1 p-6 flex flex-col justify-between">
                                        <div>
                                            <div class="flex justify-between items-start gap-4 mb-2">
                                                <h2 class="text-2xl font-black text-white tracking-tight leading-tight group-hover:text-[#66c0f4] transition-colors line-clamp-1">{{ $cart->game->title }}</h2>
                                                <form action="{{ route('cart.remove', $cart->id) }}" method="POST" onsubmit="return confirm('Hapus dari keranjang?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="h-9 w-9 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center border border-red-500/20 hover:bg-red-500 hover:text-white transition-all active:scale-90">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            <p class="text-sm text-gray-500 font-medium line-clamp-2 mb-4">{{ Str::limit($cart->game->description, 120) }}</p>
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-white/5 px-2 py-1 rounded">Publisher</span>
                                                <span class="text-xs font-bold text-[#66c0f4]">{{ $cart->game->publisher->name ?? 'Unknown' }}</span>
                                            </div>
                                        </div>

                                        <div class="flex items-end justify-between mt-6">
                                            <div class="flex items-center gap-4">
                                                @if($discount > 0)
                                                    <div class="flex flex-col">
                                                        <span class="text-xs text-gray-500 line-through font-bold">Rp {{ number_format($cart->game->price, 0, ',', '.') }}</span>
                                                        <span class="text-xs font-black text-[#beee11] uppercase tracking-tighter bg-[#4c6b22]/30 px-1.5 py-0.5 rounded">-{{ $discount }}% OFF</span>
                                                    </div>
                                                @endif
                                                <div class="text-2xl font-black text-white">Rp {{ number_format($finalPrice, 0, ',', '.') }}</div>
                                            </div>
                                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-[0.2em]">Qty: 1</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Summary Sidebar -->
                    <div class="lg:sticky lg:top-24 h-fit">
                        <div class="summary-card p-8 shadow-2xl relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mt-8 -mr-8 h-32 w-32 rounded-full bg-[#1DB954] opacity-5 blur-3xl"></div>
                            
                            <h2 class="text-2xl font-black text-white tracking-tighter mb-8 flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#1DB954]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                Order Summary
                            </h2>

                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center text-sm font-bold text-gray-400">
                                    <span class="uppercase tracking-widest">Items Selected</span>
                                    <span class="text-white" data-selected-count>{{ $totalItems }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm font-bold text-gray-400">
                                    <span class="uppercase tracking-widest">Digital Tax</span>
                                    <span class="text-emerald-500">FREE</span>
                                </div>
                            </div>

                            <div class="border-t border-white/5 pt-6 mb-10">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-1">Total Payable</span>
                                    <div class="flex items-baseline gap-1">
                                        <span class="text-sm font-black text-[#1DB954]">Rp</span>
                                        <span class="text-4xl font-black text-white tracking-tighter" data-selected-total>{{ number_format($totalPrice, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <button
                                type="submit"
                                form="checkout-selection-form"
                                data-checkout-button
                                class="w-full py-4 rounded-2xl bg-[#1DB954] text-black font-black text-lg uppercase tracking-widest shadow-xl shadow-green-500/20 transition-all hover:scale-102 hover:brightness-110 active:scale-95 disabled:opacity-50 disabled:grayscale disabled:scale-100 disabled:cursor-not-allowed"
                            >
                                Secure Checkout
                            </button>
                            
                            <p class="mt-6 text-center text-[10px] font-bold text-gray-500 uppercase tracking-widest flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                SSL Encrypted Payment
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="cart-glass-panel p-20 text-center animate-in fade-in zoom-in duration-700">
                    <div class="h-32 w-32 bg-white/5 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 text-white/10">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <h2 class="text-4xl font-black text-white tracking-tighter mb-4">Your cart is feeling lonely</h2>
                    <p class="text-gray-400 max-w-sm mx-auto mb-10 text-lg font-medium leading-relaxed">Sepertinya kamu belum menambahkan game apapun. Jelajahi koleksi kami dan temukan game favoritmu!</p>
                    <a href="/" class="inline-flex items-center justify-center px-12 py-5 rounded-2xl bg-[#118dff] text-white font-black shadow-2xl shadow-blue-500/30 hover:scale-105 active:scale-95 transition-all uppercase tracking-widest">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
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

                if (countTarget) countTarget.textContent = selected.length;
                if (totalTarget) totalTarget.textContent = formatRupiah(total);
                if (checkoutButton) checkoutButton.disabled = selected.length === 0;
                
                // Visual feedback for item cards
                checkboxes.forEach(cb => {
                    const card = cb.closest('.item-card');
                    if (cb.checked) {
                        card.style.opacity = '1';
                        card.style.transform = 'scale(1)';
                    } else {
                        card.style.opacity = '0.5';
                        card.style.transform = 'scale(0.98)';
                    }
                });
            };

            checkboxes.forEach((checkbox) => checkbox.addEventListener('change', syncSelection));
            syncSelection();
        });
    </script>
@endpush
