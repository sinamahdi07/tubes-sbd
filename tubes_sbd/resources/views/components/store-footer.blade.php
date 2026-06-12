<footer class="relative mt-28 bg-[#050a12] overflow-hidden">
    {{-- Decorative Background Glow --}}
    <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-[#118dff] opacity-5 blur-[120px]"></div>
    
    {{-- Glowing Top Border with Animation --}}
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-[#118dff]/40 to-transparent shadow-[0_0_15px_rgba(17,141,255,0.3)]"></div>
    
    <div class="mx-auto grid w-full max-w-[1700px] gap-12 px-6 py-16 sm:px-8 md:grid-cols-2 lg:grid-cols-[2fr_1fr_1fr_1fr] lg:px-12 lg:py-24">
        {{-- Brand Section --}}
        <div class="relative z-10">
            <a href="{{ route('home') }}" class="group flex items-center gap-4">
                <div class="h-14 w-14 rounded-2xl bg-white/5 p-2 shadow-2xl transition-all duration-300 group-hover:scale-110 group-hover:bg-[#118dff]/10">
                    <img src="{{ asset('GAMESTORE.png') }}" alt="Logo" class="h-full w-full object-contain">
                </div>
                <div class="flex flex-col">
                    <span class="text-3xl font-black tracking-tighter text-white">Play<span class="text-[#118dff]">Mart</span></span>
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-[#66c0f4]">Tingkatkan Koleksi Gamemu</span>
                </div>
            </a>
            <p class="mt-8 max-w-sm text-base font-medium leading-relaxed text-gray-500">
                Destinasi digital terbaik untuk para gamer. Jelajahi, temukan, dan miliki berbagai game terbaik dalam satu tempat bersama PlayMart.
            </p>
        </div>

        {{-- Navigation Sections --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 md:col-span-1 lg:col-span-3 gap-12">
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Katalog</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    <a href="{{ route('home') }}" class="block transition-all hover:text-white hover:translate-x-1">Beranda Toko</a>
                    <a href="{{ route('games.search') }}" class="block transition-all hover:text-white hover:translate-x-1">Jelajahi Game</a>
                    <a href="{{ route('games.search', ['sort' => 'popular']) }}" class="block transition-all hover:text-white hover:translate-x-1">Game Terlaris</a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Komunitas</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    <a href="{{ route('friends.index') }}" class="block transition-all hover:text-white hover:translate-x-1">Teman</a>
                    <a href="{{ url('/chat') }}" class="block transition-all hover:text-white hover:translate-x-1">Chat</a>
                    <a href="{{ route('support') }}" class="block transition-all hover:text-white hover:translate-x-1">Pusat Bantuan</a>
                    <a href="{{ route('about') }}" class="block transition-all hover:text-white hover:translate-x-1">Tentang Kami</a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Akun</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    @auth
                        @php
                            $cartCount = auth()->user()->carts()->count();
                        @endphp
                        <a href="{{ route('profile.show') }}" class="block transition-all hover:text-white hover:translate-x-1">Dasbor Saya</a>
                        <a href="{{ route('profile.games') }}" class="block transition-all hover:text-white hover:translate-x-1">Koleksi Game</a>
                        <a href="{{ route('cart.index') }}" class="block transition-all hover:text-white hover:translate-x-1 flex items-center gap-2">
                            Keranjang
                            @if($cartCount > 0)
                                <span class="bg-[#1DB954] text-black px-1.5 py-0.5 rounded text-[10px] font-black">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('payments.history') }}" class="block transition-all hover:text-white hover:translate-x-1">Riwayat Transaksi</a>
                    @else
                        <a href="{{ route('login') }}" class="block transition-all hover:text-white hover:translate-x-1">Masuk</a>
                        <a href="{{ route('register') }}" class="block transition-all hover:text-white hover:translate-x-1 text-[#118dff]">Daftar PlayMart</a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Bar --}}
    <div class="border-t border-white/5 bg-black/20">
        <div class="mx-auto flex w-full max-w-[1700px] flex-col items-center justify-between gap-6 px-6 py-10 text-[11px] font-black uppercase tracking-[0.2em] text-gray-600 sm:px-8 md:flex-row lg:px-12">
            <div class="flex items-center gap-4">
                <span class="text-white/20">© 2026 PLAYMART CORP.</span>
                <div class="h-1 w-1 rounded-full bg-white/10"></div>
                <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                <div class="h-1 w-1 rounded-full bg-white/10"></div>
                <a href="#" class="hover:text-white transition-colors">Ketentuan Layanan</a>
            </div>
            
        </div>
    </div>
</footer>
