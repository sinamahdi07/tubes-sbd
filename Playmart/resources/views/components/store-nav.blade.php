@props([
    'active' => null,
])

@php
    $cartCount = auth()->check() ? auth()->user()->carts()->count() : 0;
    $wishlistCount = auth()->check() ? auth()->user()->wishlists()->count() : 0;
    $chatUnreadCount = auth()->check() ? auth()->user()->receivedChatMessages()->whereNull('read_at')->count() : 0;
    $chatUnreadLabel = $chatUnreadCount > 99 ? '99+' : $chatUnreadCount;
    $isStoreActive = $active === 'store' || request()->routeIs('home');
    $isAboutActive = $active === 'about' || request()->routeIs('about');
    $isSupportActive = $active === 'support' || request()->routeIs('support');
    $isCartActive = $active === 'cart' || request()->routeIs('cart.*');
    $isChatActive = $active === 'chat' || request()->routeIs('chat.*');
    $isWishlistActive = $active === 'wishlist' || request()->routeIs('wishlist.*');
    $linkBase = 'relative flex h-[72px] items-center text-[15px] font-bold text-gray-300 transition hover:text-white';
    $mobileNavBase = 'flex min-w-0 flex-col items-center justify-center gap-1 rounded-xl px-1 py-2.5 text-[12px] font-black transition';
@endphp

<nav class="sticky top-0 z-50 border-b border-white/10 bg-[#050a12]/90 px-4 py-3 shadow-xl backdrop-blur-xl lg:hidden">
    <div class="mx-auto flex max-w-2xl items-center justify-between gap-3">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-3">
            <img src="{{ asset('GAMESTORE.png') }}" alt="PlayMart" class="h-11 w-11 shrink-0 rounded-xl object-contain">
            <div class="min-w-0">
                <span class="block truncate text-lg font-black tracking-tight text-white">PLAY<span class="text-[#118dff]">MART</span></span>
                <span class="block truncate text-[9px] font-bold uppercase tracking-[0.22em] text-slate-500">Game marketplace</span>
            </div>
        </a>

        <div class="flex shrink-0 items-center gap-2">
            <a href="{{ route('wishlist.index') }}" class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-slate-300" aria-label="Wishlist">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z" /></svg>
                @if($wishlistCount > 0)
                    <span class="absolute -right-1.5 -top-1.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-[#118dff] px-1 text-[9px] font-black text-white ring-2 ring-[#050a12]">{{ $wishlistCount > 99 ? '99+' : $wishlistCount }}</span>
                @endif
            </a>
            @auth
                <x-store-user-menu compact />
            @else
                <a href="{{ route('login') }}" class="rounded-xl bg-[#118dff] px-4 py-3 text-xs font-black uppercase tracking-wider text-white">Login</a>
            @endauth
        </div>
    </div>
</nav>

<nav class="hidden lg:sticky left-0 right-0 top-0 z-50 border-b border-white/10 bg-[#050a12]/80 shadow-2xl backdrop-blur-xl lg:flex">
    <div class="mx-auto flex h-24 w-full max-w-[1700px] items-center justify-between gap-4 px-5 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-8">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3 group">
                <div class="relative">
                    <div class="absolute -inset-1 rounded-2xl bg-gradient-to-tr from-[#118dff] to-[#66c0f4] opacity-30 blur group-hover:opacity-50 transition"></div>
                    <img 
                        src="{{ asset('GAMESTORE.png') }}" 
                        alt="Logo" 
                        class="relative h-14 w-14 object-contain rounded-2xl shadow-2xl"
                        decoding="async"
                    >
                </div>
                <span class="text-3xl font-black tracking-tighter text-white sm:inline uppercase">
                    PLAY<span class="text-[#118dff]">MART</span>
                </span>
            </a>

            <div class="hidden items-center gap-10 md:flex">
                <a href="{{ route('home') }}" class="{{ $linkBase }}">
                    Store
                    @if($isStoreActive)
                        <span class="absolute inset-x-0 bottom-0 h-[3px] rounded-full bg-[#118dff] shadow-[0_0_16px_rgba(17,141,255,0.8)]"></span>
                    @endif
                </a>
                <a href="{{ route('about') }}" class="{{ $linkBase }}">
                    About
                    @if($isAboutActive)
                        <span class="absolute inset-x-0 bottom-0 h-[3px] rounded-full bg-[#118dff] shadow-[0_0_16px_rgba(17,141,255,0.8)]"></span>
                    @endif
                </a>
                <a href="{{ route('support') }}" class="{{ $linkBase }}">
                    Support
                    @if($isSupportActive)
                        <span class="absolute inset-x-0 bottom-0 h-[3px] rounded-full bg-[#118dff] shadow-[0_0_16px_rgba(17,141,255,0.8)]"></span>
                    @endif
                </a>
            </div>
        </div>

        <div class="flex shrink-0 items-center gap-3">
            <div class="hidden items-center gap-3 md:flex">
                <a href="{{ route('games.search') }}" class="h-12 w-12 items-center justify-center rounded-full border border-[#2a475e] bg-[#0f1923]/86 text-gray-200 shadow-lg shadow-black/20 transition hover:border-[#66c0f4] hover:bg-[#16202d] hover:text-white md:inline-flex" aria-label="Search games">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                </a>
                @auth
                    <a
                        href="{{ route('chat.index') }}"
                        class="relative flex h-12 w-12 items-center justify-center rounded-full border {{ $isChatActive ? 'border-[#66c0f4] text-white' : 'border-[#2a475e] text-gray-200' }} bg-[#0f1923]/86 shadow-lg shadow-black/20 transition hover:border-[#66c0f4] hover:bg-[#16202d] hover:text-white"
                        aria-label="Chat"
                        data-chat-notification-url="{{ route('chat.unread-count') }}"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                        </svg>
                        <span
                            class="absolute -right-1 -top-1 {{ $chatUnreadCount > 0 ? 'flex' : 'hidden' }} h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#ff4d4d] to-[#f97316] px-1.5 text-xs font-black leading-none text-white shadow-lg shadow-red-950/40 ring-2 ring-[#050a12]"
                            data-chat-notification-badge
                            aria-label="{{ $chatUnreadCount }} pesan belum dibaca"
                        >
                            {{ $chatUnreadLabel }}
                        </span>
                    </a>
                @endauth
                <a href="{{ route('wishlist.index') }}" class="relative flex h-12 w-12 items-center justify-center rounded-full border {{ $isWishlistActive ? 'border-[#66c0f4] text-white' : 'border-[#2a475e] text-gray-200' }} bg-[#0f1923]/86 shadow-lg shadow-black/20 transition hover:border-[#66c0f4] hover:bg-[#16202d] hover:text-white" aria-label="Wishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z"/>
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-1.5 text-xs font-black leading-none text-white shadow-lg shadow-blue-950/40">
                            {{ $wishlistCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('cart.index') }}" class="relative flex h-12 w-12 items-center justify-center rounded-full border {{ $isCartActive ? 'border-[#66c0f4] text-white' : 'border-[#2a475e] text-gray-200' }} bg-[#0f1923]/86 shadow-lg shadow-black/20 transition hover:border-[#66c0f4] hover:bg-[#16202d] hover:text-white" aria-label="Cart">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l2.1 11.1a2 2 0 0 0 2 1.65h7.9a2 2 0 0 0 1.96-1.6L20 8H6M9 20.25h.01M17 20.25h.01"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-1.5 text-xs font-black leading-none text-white shadow-lg shadow-blue-950/40">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>
            <x-store-user-menu />
        </div>
    </div>

</nav>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const chatLink = document.querySelector('[data-chat-notification-url]');
                const badges = document.querySelectorAll('[data-chat-notification-badge]');

                if (!chatLink || badges.length === 0) {
                    return;
                }

                const renderBadge = (count) => {
                    const unreadCount = Math.max(0, Number(count) || 0);

                    badges.forEach((badge) => {
                        badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        badge.setAttribute('aria-label', `${unreadCount} pesan belum dibaca`);
                        badge.classList.toggle('hidden', unreadCount === 0);
                        badge.classList.toggle('flex', unreadCount > 0);
                    });
                };

                const refreshChatBadge = async () => {
                    if (document.hidden) {
                        return;
                    }

                    try {
                        const response = await fetch(chatLink.dataset.chatNotificationUrl, {
                            headers: {
                                Accept: 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });

                        if (!response.ok) {
                            return;
                        }

                        const data = await response.json();
                        renderBadge(data.unread_count);
                    } catch (error) {
                    }
                };

                setInterval(refreshChatBadge, 5000);
                window.addEventListener('focus', refreshChatBadge);
            });
        </script>
    @endpush
@endonce
