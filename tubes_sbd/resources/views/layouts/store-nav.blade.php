<nav x-data="{ open: false }" class="top-nav fixed w-full z-50">
    <div class="store-container flex justify-between h-16">
        <div class="flex items-center">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="text-[#66c0f4] font-black text-2xl tracking-tighter uppercase">
                    PlayMart
                </a>
            </div>

            <!-- Navigation Links (Desktop) -->
            <div class="hidden space-x-6 sm:-my-px sm:ml-10 sm:flex">
                <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'is-active' : '' }} px-1 pt-1 text-sm font-bold uppercase tracking-widest transition">Store</a>
                <a href="{{ url('/about') }}" class="nav-link {{ request()->routeIs('about') ? 'is-active' : '' }} px-1 pt-1 text-sm font-bold uppercase tracking-widest transition">About</a>
                <a href="{{ url('/support') }}" class="nav-link {{ request()->routeIs('support') ? 'is-active' : '' }} px-1 pt-1 text-sm font-bold uppercase tracking-widest transition">Support</a>
            </div>
        </div>

        <!-- Right Side Navigation (Desktop) -->
        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
            <a href="{{ route('cart.index') }}" class="relative text-gray-300 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l2.1 11.1a2 2 0 0 0 2 1.65h7.9a2 2 0 0 0 1.96-1.6L20 8H6M9 20.25h.01M17 20.25h.01"/>
                </svg>
                @if($cartCount > 0)
                    <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full h-4 w-4 flex items-center justify-center">{{ $cartCount }}</span>
                @endif
            </a>
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-transparent hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        @if(Auth::user()->is_admin)
                            <x-dropdown-link :href="route('admin.dashboard')">
                                🛡 Admin Panel
                            </x-dropdown-link>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="text-[#66c0f4] hover:text-white text-sm font-bold uppercase">Login</a>
            @endauth
        </div>

        <!-- Hamburger -->
        <div class="-mr-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-[#1b2838] focus:outline-none transition-all duration-200">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Responsive Navigation Menu (Mobile) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-[#171a21] border-t border-[#1b2838] shadow-2xl overflow-hidden transition-all duration-300">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('home') }}" class="block pl-4 pr-4 py-3 text-base font-bold text-gray-300 hover:text-white hover:bg-[#1b2838] border-l-4 border-transparent hover:border-[#66c0f4] transition">Store</a>
            <a href="{{ url('/about') }}" class="block pl-4 pr-4 py-3 text-base font-bold text-gray-300 hover:text-white hover:bg-[#1b2838] border-l-4 border-transparent hover:border-[#66c0f4] transition">About</a>
            <a href="{{ url('/support') }}" class="block pl-4 pr-4 py-3 text-base font-bold text-gray-300 hover:text-white hover:bg-[#1b2838] border-l-4 border-transparent hover:border-[#66c0f4] transition">Support</a>
            <a href="{{ route('cart.index') }}" class="block pl-4 pr-4 py-3 text-base font-bold text-gray-300 hover:text-white hover:bg-[#1b2838] border-l-4 border-transparent hover:border-[#66c0f4] transition">Cart ({{ $cartCount }})</a>
        </div>

        <!-- Mobile Auth Info -->
        <div class="pt-4 pb-1 border-t border-[#1b2838]">
            @auth
                <div class="px-4">
                    <div class="font-medium text-base text-white">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">{{ __('Profile') }}</x-responsive-nav-link>
                    @if(Auth::user()->is_admin)<x-responsive-nav-link :href="route('admin.dashboard')">🛡 Admin Panel</x-responsive-nav-link>@endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log Out') }}</x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="px-4 py-2">
                    <a href="{{ route('login') }}" class="block w-full text-center py-3 bg-[#66c0f4] text-white font-black rounded-md">Login</a>
                </div>
            @endauth
        </div>
    </div>
</nav>