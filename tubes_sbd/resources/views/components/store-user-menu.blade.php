@php
    $user = auth()->user();
    $initial = $user ? strtoupper(substr($user->name, 0, 1)) : '';
@endphp

@auth
    <details class="relative">
        <summary
            class="flex cursor-pointer list-none items-center gap-3 rounded-xl border border-[#2a475e] bg-[#0f1923] px-3 py-2 text-sm text-white transition hover:border-[#66c0f4] hover:bg-[#16202d]"
            style="list-style: none;"
        >
            <span class="flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-r from-[#06bfff] to-[#2d73ff] font-bold text-white">
                {{ $initial }}
            </span>

            <span class="hidden text-left sm:block">
                <span class="block text-xs text-gray-400">Profile</span>
                <span class="block max-w-32 truncate font-semibold text-[#66c0f4]">
                    {{ $user->name }}
                </span>
            </span>

            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </summary>

        <div class="absolute right-0 z-[999] mt-3 w-72 overflow-hidden rounded-2xl border border-[#2a475e] bg-[#0f1923] shadow-2xl shadow-black/40">
            <div class="border-b border-[#2a475e] p-4">
                <p class="truncate font-bold text-white">{{ $user->name }}</p>
                <p class="truncate text-sm text-gray-400">{{ $user->email }}</p>
            </div>

            <div class="p-2 text-sm font-semibold text-gray-300">
                @if($user->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                        Admin Panel
                    </a>
                @endif

                <a href="{{ route('profile.show') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                    Detail Profile
                </a>
                <a href="{{ route('profile.games') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                    Game Dibeli
                </a>
                <a href="{{ route('friends.index') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                    Teman
                </a>
                <a href="{{ route('payments.history') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                    Riwayat Payment
                </a>
                <a href="{{ route('profile.edit') }}" class="block rounded-xl px-4 py-3 hover:bg-[#16202d] hover:text-white">
                    Edit Profile
                </a>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="border-t border-[#2a475e] p-2">
                @csrf
                <button type="submit" class="block w-full rounded-xl px-4 py-3 text-left text-sm font-semibold text-red-200 transition hover:bg-red-600 hover:text-white">
                    Logout
                </button>
            </form>
        </div>
    </details>
@else
    <div class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-300 hover:text-white">
            Login
        </a>
        <a href="{{ route('register') }}" class="rounded bg-[#5c7e10] px-4 py-2 text-sm font-semibold transition hover:bg-[#7ea64b]">
            Register
        </a>
    </div>
@endauth
