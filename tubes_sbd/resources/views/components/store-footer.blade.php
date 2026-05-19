<footer class="mt-16 border-t border-[#2a475e]/70 bg-[#050a12]">
    <div class="mx-auto grid w-full max-w-[1700px] gap-8 px-4 py-10 sm:px-6 md:grid-cols-[1.2fr_1fr_1fr] lg:px-8">
        <div>
            <div class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] text-xl font-black text-white">
                    P
                </span>
                <span class="text-xl font-black tracking-wide text-white">Play<span class="text-[#118dff]">Mart</span></span>
            </div>
            <p class="mt-4 max-w-md text-sm leading-relaxed text-gray-400">
                Marketplace game digital untuk menemukan, membeli, dan mengelola library game favoritmu.
            </p>
        </div>

        <div>
            <h3 class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Store</h3>
            <div class="mt-4 space-y-3 text-sm font-semibold text-gray-400">
                <a href="{{ route('home') }}" class="block transition hover:text-white">Home</a>
                <a href="{{ route('games.search') }}" class="block transition hover:text-white">Browse Games</a>
                <a href="{{ route('about') }}" class="block transition hover:text-white">About</a>
                <a href="{{ route('support') }}" class="block transition hover:text-white">Support</a>
                <a href="{{ route('cart.index') }}" class="block transition hover:text-white">Cart</a>
            </div>
        </div>

        <div>
            <h3 class="text-sm font-black uppercase tracking-[0.22em] text-[#66c0f4]">Account</h3>
            <div class="mt-4 space-y-3 text-sm font-semibold text-gray-400">
                @auth
                    <a href="{{ route('profile.show') }}" class="block transition hover:text-white">Profile</a>
                    <a href="{{ route('profile.games') }}" class="block transition hover:text-white">Game Dibeli</a>
                    <a href="{{ route('payments.history') }}" class="block transition hover:text-white">Riwayat Payment</a>
                @else
                    <a href="{{ route('login') }}" class="block transition hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="block transition hover:text-white">Register</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="border-t border-[#2a475e]/60">
        <div class="mx-auto flex w-full max-w-[1700px] flex-col items-center justify-between gap-3 px-4 py-5 text-xs font-semibold text-gray-500 sm:px-6 md:flex-row lg:px-8">
            <span>© 2026 PlayMart. All rights reserved.</span>
            <span class="text-gray-600">Built for digital game distribution.</span>
        </div>
    </div>
</footer>
