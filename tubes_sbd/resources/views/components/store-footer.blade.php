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
                    <span class="text-[9px] font-black uppercase tracking-[0.4em] text-[#66c0f4]">Level Up Your Library</span>
                </div>
            </a>
            <p class="mt-8 max-w-sm text-base font-medium leading-relaxed text-gray-500">
                The ultimate digital destination for gamers. Explore, discover, and own the greatest titles in the universe. Join the revolution of digital gaming distribution.
            </p>
            
            {{-- Social Icons --}}
            <div class="mt-8 flex gap-4">
                @php
                    $socials = [
                        ['icon' => 'M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-1.002-2.178-1.627-3.598-1.627-2.722 0-4.929 2.207-4.929 4.929 0 .386.043.762.127 1.122-4.097-.205-7.73-2.168-10.163-5.152-.424.656-.667 1.44-.667 2.276 0 1.71.87 3.218 2.193 4.103-.807-.026-1.566-.247-2.229-.616v.062c0 2.387 1.699 4.38 3.952 4.832-.413.113-.849.173-1.299.173-.317 0-.625-.03-.925-.088.627 1.956 2.444 3.379 4.599 3.42-1.685 1.32-3.809 2.106-6.115 2.106-.397 0-.79-.023-1.175-.068 2.179 1.396 4.743 2.212 7.49 2.212 8.987 0 13.896-7.445 13.896-13.896 0-.211-.005-.422-.014-.631.954-.688 1.782-1.546 2.435-2.528z', 'name' => 'Twitter'],
                        ['icon' => 'M12 2.163c3.204 0 3.584.012 4.85.07 1.17.054 1.805.249 2.227.493.559.217.96.477 1.382.896.419.42.679.819.896 1.381.244.424.44.103.493 2.227.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.054 1.17-.249 1.805-.493 2.227-.217.558-.477.96-.896 1.382-.42.419-.819.679-1.381.896-.424.244-1.058.44-2.227.493-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.17-.054-1.805-.249-2.227-.493-.558-.217-.96-.477-1.382-.896-.419-.42-.679-.819-.896-1.381-.244-.424-.44-1.058-.493-2.227-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.054-1.17.249-1.805.493-2.227.217-.558.477-.96.896-1.382.42-.419.819-.679 1.381-.896.424-.244 1.058-.44 2.227-.493 1.266-.058 1.646-.07 4.85-.07zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948s.014 3.667.072 4.947c.2 4.337 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072s3.667-.014 4.947-.072c4.337-.2 6.78-2.618 6.98-6.98.058-1.281.072-1.689.072-4.948s-.014-3.667-.072-4.947c-.2-4.358-2.618-6.78-6.98-6.98-1.281-.058-1.689-.072-4.948-.072zM12 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.162 6.162 6.162 6.162-2.759 6.162-6.162-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z', 'name' => 'Instagram'],
                        ['icon' => 'M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z', 'name' => 'LinkedIn'],
                    ];
                @endphp
                @foreach($socials as $social)
                    <a href="#" class="h-10 w-10 rounded-xl bg-white/5 flex items-center justify-center text-white/40 transition-all hover:bg-[#118dff] hover:text-white group">
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $social['icon'] }}" /></svg>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Navigation Sections --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 md:col-span-1 lg:col-span-3 gap-12">
            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Catalogue</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    <a href="{{ route('home') }}" class="block transition-all hover:text-white hover:translate-x-1">Store Front</a>
                    <a href="{{ route('games.search') }}" class="block transition-all hover:text-white hover:translate-x-1">Browse Games</a>
                    <a href="{{ route('games.search', ['sort' => 'popular']) }}" class="block transition-all hover:text-white hover:translate-x-1">Global Top Sellers</a>
                    <a href="#" class="block transition-all hover:text-white hover:translate-x-1 text-[#8bc53f]">Special Offers</a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Community</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    <a href="{{ route('friends.index') }}" class="block transition-all hover:text-white hover:translate-x-1">Social Hub</a>
                    <a href="{{ url('/chat') }}" class="block transition-all hover:text-white hover:translate-x-1">Global Chat</a>
                    <a href="{{ route('support') }}" class="block transition-all hover:text-white hover:translate-x-1">Help Center</a>
                    <a href="{{ route('about') }}" class="block transition-all hover:text-white hover:translate-x-1">About Us</a>
                </div>
            </div>

            <div>
                <h3 class="text-xs font-black uppercase tracking-[0.3em] text-[#66c0f4]">Authorized</h3>
                <div class="mt-8 space-y-4 text-sm font-bold text-gray-500">
                    @auth
                        @php
                            $cartCount = auth()->user()->carts()->count();
                        @endphp
                        <a href="{{ route('profile.show') }}" class="block transition-all hover:text-white hover:translate-x-1">My Dashboard</a>
                        <a href="{{ route('profile.games') }}" class="block transition-all hover:text-white hover:translate-x-1">My Library</a>
                        <a href="{{ route('cart.index') }}" class="block transition-all hover:text-white hover:translate-x-1 flex items-center gap-2">
                            Cart
                            @if($cartCount > 0)
                                <span class="bg-[#1DB954] text-black px-1.5 py-0.5 rounded text-[10px] font-black">{{ $cartCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('payments.history') }}" class="block transition-all hover:text-white hover:translate-x-1">Transactions</a>
                    @else
                        <a href="{{ route('login') }}" class="block transition-all hover:text-white hover:translate-x-1">Member Login</a>
                        <a href="{{ route('register') }}" class="block transition-all hover:text-white hover:translate-x-1 text-[#118dff]">Join PlayMart</a>
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
                <a href="#" class="hover:text-white transition-colors">Privacy Policy</a>
                <div class="h-1 w-1 rounded-full bg-white/10"></div>
                <a href="#" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-gray-700">POWERED BY</span>
                <span class="text-[#118dff] shadow-sm">NEO-ENGINE 4.0</span>
            </div>
        </div>
    </div>
</footer>
