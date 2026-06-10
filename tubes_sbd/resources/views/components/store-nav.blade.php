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
    $mobileNavBase = 'flex min-w-0 flex-col items-center justify-center gap-1 rounded-lg px-1 py-2 text-[11px] font-black transition';
@endphp

<nav class="sticky left-0 right-0 top-0 z-50 border-b border-[#2a475e]/80 bg-[#050a12]/90 shadow-xl shadow-black/25 backdrop-blur">
    <div class="mx-auto flex h-[72px] w-full max-w-[1700px] items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <div class="flex min-w-0 items-center gap-8">
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-3">
                <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-11 w-11 object-contain rounded-lg shadow-lg shadow-blue-950/20">
                <span class="hidden text-2xl font-black tracking-wide text-white sm:inline">
                    Play<span class="text-[#118dff]">Mart</span>
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

    <div class="border-t border-[#2a475e]/70 bg-[#07111d]/95 px-2 py-1 md:hidden">
        <div class="mx-auto grid h-14 max-w-md grid-cols-5 gap-1">
            <a href="{{ route('home') }}" class="{{ $mobileNavBase }} {{ $isStoreActive ? 'bg-[#0f2638] text-white' : 'text-gray-400 hover:bg-[#0f1923] hover:text-white' }}" aria-label="Store">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5 12 4l9 6.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1v-9.5Z"/>
                </svg>
                <span class="truncate">Store</span>
            </a>

            <a href="{{ route('games.search') }}" class="{{ $mobileNavBase }} {{ request()->routeIs('games.search') ? 'bg-[#0f2638] text-white' : 'text-gray-400 hover:bg-[#0f1923] hover:text-white' }}" aria-label="Search games">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                </svg>
                <span class="truncate">Search</span>
            </a>

            <a
                href="{{ auth()->check() ? route('chat.index') : route('login') }}"
                class="{{ $mobileNavBase }} {{ $isChatActive ? 'bg-[#0f2638] text-white' : 'text-gray-400 hover:bg-[#0f1923] hover:text-white' }}"
                aria-label="Chat"
                @auth data-chat-notification-url="{{ route('chat.unread-count') }}" @endauth
            >
                <span class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.86 9.86 0 0 1-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8Z"/>
                    </svg>
                    @auth
                        <span
                            class="absolute -right-3 -top-3 {{ $chatUnreadCount > 0 ? 'flex' : 'hidden' }} h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#ff4d4d] to-[#f97316] px-1.5 text-[10px] font-black leading-none text-white shadow-lg shadow-red-950/40 ring-2 ring-[#07111d]"
                            data-chat-notification-badge
                            aria-label="{{ $chatUnreadCount }} pesan belum dibaca"
                        >
                            {{ $chatUnreadLabel }}
                        </span>
                    @endauth
                </span>
                <span class="truncate">Chat</span>
            </a>

            <a href="{{ route('wishlist.index') }}" class="{{ $mobileNavBase }} {{ $isWishlistActive ? 'bg-[#0f2638] text-white' : 'text-gray-400 hover:bg-[#0f1923] hover:text-white' }}" aria-label="Wishlist">
                <span class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 8.25c0 6.75-9 11.25-9 11.25S3 15 3 8.25A4.75 4.75 0 0 1 11.1 5L12 6l.9-1A4.75 4.75 0 0 1 21 8.25Z"/>
                    </svg>
                    @if($wishlistCount > 0)
                        <span class="absolute -right-3 -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-1.5 text-[10px] font-black leading-none text-white shadow-lg shadow-blue-950/40 ring-2 ring-[#07111d]">
                            {{ $wishlistCount > 99 ? '99+' : $wishlistCount }}
                        </span>
                    @endif
                </span>
                <span class="truncate">Wishlist</span>
            </a>

            <a href="{{ route('cart.index') }}" class="{{ $mobileNavBase }} {{ $isCartActive ? 'bg-[#0f2638] text-white' : 'text-gray-400 hover:bg-[#0f1923] hover:text-white' }}" aria-label="Cart">
                <span class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l2.1 11.1a2 2 0 0 0 2 1.65h7.9a2 2 0 0 0 1.96-1.6L20 8H6M9 20.25h.01M17 20.25h.01"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -right-3 -top-3 flex h-5 min-w-5 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-1.5 text-[10px] font-black leading-none text-white shadow-lg shadow-blue-950/40 ring-2 ring-[#07111d]">
                            {{ $cartCount > 99 ? '99+' : $cartCount }}
                        </span>
                    @endif
                </span>
                <span class="truncate">Cart</span>
            </a>
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
