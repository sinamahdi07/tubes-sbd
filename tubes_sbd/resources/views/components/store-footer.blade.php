<footer class="relative mt-20 bg-[#050a12]">
    {{-- Glowing Top Border --}}
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#118dff]/50 to-transparent"></div>
    
    <div class="mx-auto grid w-full max-w-[1700px] gap-10 px-4 py-16 sm:px-6 md:grid-cols-[1.5fr_1fr_1fr_1fr] lg:px-8">
        {{-- Brand Section --}}
        <div>
            <a href="{{ route('home') }}" class="group flex items-center gap-3">
                <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-12 w-12 object-contain rounded-lg shadow-lg shadow-blue-950/20 transition-transform group-hover:scale-110">
                <span class="text-2xl font-black tracking-tighter text-white">Play<span class="text-[#118dff]">Mart</span></span>
            </a>
            <p class="mt-6 max-w-xs text-sm leading-relaxed text-gray-400">
                Platform distribusi digital terpercaya untuk mengeksplorasi, membeli, dan mengoleksi ribuan judul game terbaik di seluruh dunia.
            </p>
        </div>

        {{-- Navigation Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Discover</h3>
            <div class="mt-4 space-y-3 text-sm font-semibold text-gray-400">
                <a href="{{ route('home') }}" class="block transition-colors hover:text-[#118dff]">Store Front</a>
                <a href="{{ route('games.search') }}" class="block transition-colors hover:text-[#118dff]">Explore Catalogue</a>
                <a href="{{ route('games.search', ['sort' => 'popular']) }}" class="block transition-colors hover:text-[#118dff]">Top Sellers</a>
                <a href="{{ route('about') }}" class="block transition-colors hover:text-[#118dff]">Company About</a>
            </div>
        </div>

        {{-- Support Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Support</h3>
            <div class="mt-4 space-y-3 text-sm font-semibold text-gray-400">
                <a href="{{ route('support') }}" class="block transition-colors hover:text-[#118dff]">Help Center</a>
                <a href="{{ route('support') }}#faq" class="block transition-colors hover:text-[#118dff]">F.A.Q</a>
                <a href="mailto:support@playmart.test" class="block transition-colors hover:text-[#118dff]">Contact Email</a>
            </div>
        </div>

        {{-- User Section --}}
        <div>
            <h3 class="text-xs font-black uppercase tracking-[0.25em] text-[#66c0f4]">Account</h3>
            <div class="mt-4 space-y-3 text-sm font-semibold text-gray-400">
                @auth
                    <a href="{{ route('profile.show') }}" class="block transition-colors hover:text-[#118dff]">My Profile</a>
                    <a href="{{ route('profile.games') }}" class="block transition-colors hover:text-[#118dff]">Game Library</a>
                    <a href="{{ route('cart.index') }}" class="block transition-colors hover:text-[#118dff]">Shopping Cart</a>
                    <a href="{{ route('payments.history') }}" class="block transition-colors hover:text-[#118dff]">Transaction History</a>
                @else
                    <a href="{{ route('login') }}" class="block transition-colors hover:text-[#118dff]">Sign In</a>
                    <a href="{{ route('register') }}" class="block transition-colors hover:text-[#118dff]">Create Account</a>
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
