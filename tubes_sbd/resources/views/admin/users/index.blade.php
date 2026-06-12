@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@push('styles')
<style>
    .user-row { transition: background 0.2s ease; }
    .user-row:hover { background: rgba(17, 141, 255, 0.04); }
    .user-row td { border-bottom: 1px solid rgba(255,255,255,0.04); }
    .avatar-circle {
        width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; font-weight: 900; letter-spacing: -0.02em;
    }
</style>
@endpush

@section('content')
    @php $isTrash = request()->boolean('trash'); @endphp

    {{-- ===== FILTER BAR ===== --}}
    <div class="flex items-center gap-3 mb-6 flex-wrap lg:flex-nowrap">

        {{-- Tab: Aktif / Terhapus --}}
        <div class="flex items-center gap-1 p-1 rounded-xl bg-white/5 border border-white/8 flex-shrink-0">
            <a href="{{ route('admin.users.index', request()->except(['trash', 'page'])) }}"
               class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                      {{ !$isTrash ? 'bg-[#118dff] text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Aktif
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['trash' => 1])) }}"
               class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                      {{ $isTrash ? 'bg-red-500/80 text-white shadow-lg shadow-red-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                Terhapus
            </a>
        </div>

        {{-- Search + Role + Buttons (satu form) --}}
        <form method="GET" action="{{ route('admin.users.index') }}"
              class="flex items-center gap-2 flex-1 min-w-0">
            @if($isTrash)
                <input type="hidden" name="trash" value="1">
            @endif

            {{-- Search --}}
            <div class="relative flex-1 min-w-0">
                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm font-medium text-white placeholder-gray-500
                              bg-white/5 border border-white/10 focus:border-[#118dff]/60
                              focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all">
            </div>

            {{-- Role Select --}}
            <select name="role"
                    class="py-2.5 px-3 rounded-xl text-sm font-medium text-white
                           bg-white/5 border border-white/10 focus:border-[#118dff]/60
                           focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all
                           flex-shrink-0 w-36 lg:w-44 appearance-none cursor-pointer"
                    style="background-image: url(\"data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m19 9-7 7-7-7'/%3E%3C/svg%3E\"); background-repeat: no-repeat; background-position: right 10px center; background-size: 16px; padding-right: 32px;">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user"  {{ request('role') == 'user'  ? 'selected' : '' }}>User</option>
            </select>

            {{-- Filter Button --}}
            <button type="submit"
                    class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                           bg-white/5 border border-white/10 text-gray-300 hover:bg-[#118dff]/15 hover:border-[#118dff]/40
                           hover:text-white transition-all whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/>
                </svg>
                Filter
            </button>

            {{-- Reset --}}
            @if(request()->hasAny(['search', 'role']))
                <a href="{{ route('admin.users.index', $isTrash ? ['trash' => 1] : []) }}"
                   class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                          text-gray-500 hover:text-red-400 border border-transparent hover:border-red-500/30 transition-all whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/>
                    </svg>
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- ===== TABLE ===== --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-white/5 bg-white/3">
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 w-16">ID</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Pengguna</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Email</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 w-24 text-center">Role</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 w-32">Bergabung</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center w-48">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="user-row">
                            {{-- ID --}}
                            <td class="px-6 py-4">
                                <span class="text-xs font-black text-gray-600 tracking-wider">#{{ $user->id }}</span>
                            </td>

                            {{-- Nama + Avatar --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @php
                                        $colors = ['bg-blue-500/20 text-blue-400','bg-purple-500/20 text-purple-400','bg-emerald-500/20 text-emerald-400','bg-amber-500/20 text-amber-400','bg-rose-500/20 text-rose-400','bg-cyan-500/20 text-cyan-400'];
                                        $colorClass = $colors[$user->id % count($colors)];
                                    @endphp
                                    <div class="avatar-circle {{ $colorClass }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-black text-white">{{ $user->name }}</span>
                                </div>
                            </td>

                            {{-- Email + Verified --}}
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-300 font-medium mb-1">{{ $user->email }}</div>
                                @if($user->hasVerifiedEmail())
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest
                                                 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                        <span class="w-1 h-1 rounded-full bg-emerald-400"></span>
                                        Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-black uppercase tracking-widest
                                                 bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                        <span class="w-1 h-1 rounded-full bg-amber-400"></span>
                                        Belum Verified
                                    </span>
                                @endif
                            </td>

                            {{-- Role --}}
                            <td class="px-6 py-4 text-center">
                                @if($user->is_admin)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-[#118dff]/15 text-[#118dff] border border-[#118dff]/30">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z"/></svg>
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                 bg-white/5 text-gray-400 border border-white/8">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        User
                                    </span>
                                @endif
                            </td>

                            {{-- Bergabung --}}
                            <td class="px-6 py-4">
                                <span class="text-sm font-bold text-gray-400">{{ $user->created_at->format('d M Y') }}</span>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    @if($isTrash)
                                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline"
                                              onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin mengembalikan user ini?', 'info', 'Restore User');">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                           bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                           hover:bg-emerald-500/20 transition-all">
                                                Restore
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.force-destroy', $user->id) }}" method="POST" class="inline"
                                              onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin menghapus permanen user ini? Data tidak dapat dikembalikan.', 'danger', 'Hapus Permanen');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                           bg-red-500/10 text-red-400 border border-red-500/20
                                                           hover:bg-red-500/20 transition-all">
                                                Hapus Permanen
                                            </button>
                                        </form>

                                    @elseif($user->id !== auth()->id())
                                        @unless($user->hasVerifiedEmail())
                                            <form action="{{ route('admin.users.verify-email', $user) }}" method="POST" class="inline"
                                                  onsubmit="return adminConfirmSubmit(event, 'Verifikasi alamat email user ini secara manual?', 'info', 'Verifikasi Email');">
                                                @csrf
                                                <button type="submit"
                                                        class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                               bg-emerald-500/10 text-emerald-400 border border-emerald-500/20
                                                               hover:bg-emerald-500/20 transition-all whitespace-nowrap">
                                                    Verifikasi
                                                </button>
                                            </form>
                                        @endunless

                                        <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap
                                                           {{ $user->is_admin
                                                               ? 'bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500/20'
                                                               : 'bg-[#118dff]/10 text-[#118dff] border border-[#118dff]/20 hover:bg-[#118dff]/20' }}">
                                                {{ $user->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                              onsubmit="return adminConfirmSubmit(event, 'Yakin ingin menghapus user ini ke tempat sampah?', 'danger', 'Hapus User');">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest
                                                           bg-red-500/10 text-red-400 border border-red-500/20
                                                           hover:bg-red-500/20 transition-all">
                                                Hapus
                                            </button>
                                        </form>

                                    @else
                                        <span class="flex items-center gap-1.5 text-xs font-black text-gray-600 italic">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#118dff]"></span>
                                            It's you
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center gap-3">
                                    <div class="w-16 h-16 rounded-2xl bg-white/5 flex items-center justify-center text-gray-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-bold text-gray-500">{{ $isTrash ? 'Tidak ada user terhapus.' : 'Tidak ada user yang ditemukan.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-white/5 bg-white/2 flex items-center justify-between gap-4">
            <p class="text-xs font-bold text-gray-500">
                Total: <span class="text-gray-300">{{ $users->total() }}</span> {{ $isTrash ? 'user terhapus' : 'pengguna' }}
            </p>
            {{ $users->links() }}
        </div>
    </div>
@endsection
