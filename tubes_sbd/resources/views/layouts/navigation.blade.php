<nav class="sticky top-0 z-50 border-b border-white/10 bg-[#07111d]/95 backdrop-blur-xl" x-data="{ open: false }">
    <div class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a class="flex items-center gap-3 font-black tracking-tight text-white" href="{{ route('home') }}">
            <img src="{{ asset('GAMESTORE.png') }}" alt="PlayMart" class="h-10 w-10 rounded-xl object-contain">
            <span>PLAY<span class="text-[#118dff]">MART</span></span>
        </a>

        <div class="hidden items-center gap-2 md:flex">
            <a class="rounded-xl px-4 py-2 text-sm font-bold {{ request()->routeIs('home') ? 'bg-[#118dff]/15 text-[#66c0f4]' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}" href="{{ route('home') }}">Store</a>
            <a class="rounded-xl px-4 py-2 text-sm font-bold {{ request()->routeIs('friends.*') ? 'bg-[#118dff]/15 text-[#66c0f4]' : 'text-slate-300 hover:bg-white/5 hover:text-white' }}" href="{{ route('friends.index') }}">Teman</a>
            @if(Auth::user()->is_admin)
                <a class="rounded-xl px-4 py-2 text-sm font-bold text-[#66c0f4] hover:bg-[#118dff]/15" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            @endif
            <a class="rounded-xl px-4 py-2 text-sm font-bold text-slate-300 hover:bg-white/5 hover:text-white" href="{{ route('profile.edit') }}">{{ Auth::user()->name }}</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-xl border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-bold text-red-300 hover:bg-red-500 hover:text-white">Keluar</button>
            </form>
        </div>

        <button type="button" class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/10 bg-white/5 text-white md:hidden" @click="open = !open" :aria-expanded="open.toString()" aria-label="Buka navigasi">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
        </button>
    </div>

    <div class="border-t border-white/10 px-4 py-3 md:hidden" x-show="open" x-transition x-cloak>
        <div class="grid gap-2">
            <a class="rounded-xl px-4 py-3 font-bold text-slate-200 hover:bg-white/5" href="{{ route('home') }}">Store</a>
            <a class="rounded-xl px-4 py-3 font-bold text-slate-200 hover:bg-white/5" href="{{ route('friends.index') }}">Teman</a>
            <a class="rounded-xl px-4 py-3 font-bold text-slate-200 hover:bg-white/5" href="{{ route('profile.edit') }}">Profile</a>
            @if(Auth::user()->is_admin)
                <a class="rounded-xl px-4 py-3 font-bold text-[#66c0f4] hover:bg-[#118dff]/15" href="{{ route('admin.dashboard') }}">Admin Panel</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full rounded-xl bg-red-500/10 px-4 py-3 text-left font-bold text-red-300">Keluar</button>
            </form>
        </div>
    </div>
</nav>
