@php
    $user = auth()->user();
    $initial = $user ? strtoupper(substr($user->name, 0, 1)) : '';
@endphp

@auth
    <details class="relative">
        <summary
            class="flex h-14 cursor-pointer list-none items-center gap-3 rounded-lg border border-[#2a475e] bg-[#0f1923]/86 px-3 text-sm text-white shadow-lg shadow-black/20 transition hover:border-[#66c0f4] hover:bg-[#16202d]"
            style="list-style: none;"
        >
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-[#06bfff] to-[#2d73ff] text-lg font-black text-white shadow-md shadow-blue-950/40">
                {{ $initial }}
            </span>

            <span class="hidden min-w-0 text-left sm:block">
                <span class="block max-w-36 truncate font-black leading-tight text-white">
                    {{ $user->is_admin ? 'Administrator' : $user->name }}
                </span>
            </span>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
            </svg>
        </summary>

        <div class="absolute right-0 z-[999] mt-3 w-72 overflow-hidden rounded-lg border border-[#2a475e] bg-[#0f1923] shadow-2xl shadow-black/45">
            <div class="border-b border-[#2a475e] p-4">
                <p class="truncate font-black text-white">{{ $user->name }}</p>
                <p class="truncate text-sm text-gray-400">{{ $user->email }}</p>
            </div>

            <div class="p-2 text-sm font-semibold text-gray-300">
                @if($user->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                        Admin Panel
                    </a>
                @endif

                <a href="{{ route('profile.show') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Detail Profile
                </a>
                <a href="{{ route('profile.games') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Game Dibeli
                </a>
                <a href="{{ route('wishlist.index') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Wishlist
                </a>
                <a href="{{ route('friends.index') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Teman
                </a>
                <a href="{{ route('chat.index') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Chat
                </a>
                <a href="{{ route('payments.history') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Riwayat Payment
                </a>
                <a href="{{ route('profile.edit') }}" class="block rounded-md px-4 py-3 transition hover:bg-[#16202d] hover:text-white">
                    Edit Profile
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-[#2a475e] p-2">
                @csrf
                <button type="submit" class="block w-full rounded-md px-4 py-3 text-left text-sm font-semibold text-red-200 transition hover:bg-red-600 hover:text-white">
                    Logout
                </button>
            </form>
        </div>
    </details>
@else
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="rounded-md border border-[#2a475e] px-4 py-3 text-sm font-bold text-gray-300 transition hover:border-[#66c0f4] hover:text-white">
            Login
        </a>
        <a href="{{ route('register') }}" class="rounded-md bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-blue-950/30 transition hover:brightness-110">
            Register
        </a>
    </div>
@endauth
