<footer class="relative mt-28 bg-[#050a12]">
    {{-- Glowing Top Border --}}
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#118dff]/50 to-transparent"></div>
    
    <div class="mx-auto grid w-full max-w-[1700px] gap-12 px-4 py-16 sm:px-6 md:grid-cols-2 lg:grid-cols-[1.6fr_1fr_1fr_1fr] lg:px-8 lg:py-20">
        {{-- Brand Section --}}
        <div>
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-12 w-12 rounded-lg object-contain shadow-lg shadow-blue-950/20 transition-transform duration-200 group-hover:scale-110">
                <span class="text-2xl font-black tracking-tighter text-white">Play<span class="text-[#118dff]">Mart</span></span>
            </a>
            <p class="mt-6 max-w-md text-sm leading-7 text-gray-400">
                Platform distribusi digital terpercaya untuk mengeksplorasi, membeli, dan mengoleksi ribuan judul game terbaik di seluruh dunia.
            </p>
            <div class="mt-7 flex flex-wrap gap-3 text-xs font-black uppercase tracking-[0.16em] text-[#66c0f4]">
                <span class="rounded border border-[#2a475e] bg-[#07111d]/80 px-3 py-2">Secure Checkout</span>
                <span class="rounded border border-[#2a475e] bg-[#07111d]/80 px-3 py-2">Digital Library</span>
            </div>
        </div>

        {{-- Navigation Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Discover</h3>
            <div class="mt-5 space-y-3 text-sm font-semibold text-gray-400">
                <a href="{{ route('home') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Store Front</a>
                <a href="{{ route('games.search') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Explore Catalogue</a>
                <a href="{{ route('games.search', ['sort' => 'popular']) }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Top Sellers</a>
                <a href="{{ route('about') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Company About</a>
            </div>
        </div>

        {{-- Support Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Support</h3>
            <div class="mt-5 space-y-3 text-sm font-semibold text-gray-400">
                <a href="{{ route('support') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Help Center</a>
                <a href="{{ route('about') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">F.A.Q</a>
                <a href="mailto:support@playmart.test" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Contact Email</a>
            </div>
        </div>

        {{-- User Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Account</h3>
            <div class="mt-5 space-y-3 text-sm font-semibold text-gray-400">
                @auth
                    <a href="{{ route('profile.show') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">My Profile</a>
                    <a href="{{ route('profile.games') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Game Library</a>
                    <a href="{{ route('cart.index') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Shopping Cart</a>
                    <a href="{{ route('payments.history') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Transaction History</a>
                @else
                    <a href="{{ route('login') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Sign In</a>
                    <a href="{{ route('register') }}" class="block transition hover:translate-x-1 hover:text-[#66c0f4]">Create Account</a>
                @endauth
            </div>
        </div>
    </div>

    <div class="border-t border-[#2a475e]/60">
        <div class="mx-auto flex w-full max-w-[1700px] flex-col items-center justify-between gap-3 px-4 py-5 text-xs font-semibold text-gray-500 sm:px-6 md:flex-row lg:px-8">
            <span class="tracking-wide">© 2026 PLAYMART. ALL RIGHTS RESERVED.</span>
            <span class="text-gray-600 uppercase tracking-widest">Built for digital game distribution</span>
        </div>
    </div>
</footer>
