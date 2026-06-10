@extends('admin.layouts.app')

@section('title', 'Manajemen User')

@section('content')
    @php
        $isTrash = request()->boolean('trash');
    @endphp

    <div class="steam-card rounded-lg overflow-hidden mb-6 p-4">
        <div class="flex items-center gap-2 mb-4">
            <a href="{{ route('admin.users.index', request()->except(['trash', 'page'])) }}"
               class="px-4 py-2 rounded text-sm font-semibold transition {{ $isTrash ? 'bg-[#1b2838] text-gray-300 border border-[#2a475e] hover:text-white' : 'steam-btn-primary' }}">
                Aktif
            </a>
            <a href="{{ route('admin.users.index', array_merge(request()->except('page'), ['trash' => 1])) }}"
               class="px-4 py-2 rounded text-sm font-semibold transition {{ $isTrash ? 'steam-btn-primary' : 'bg-[#1b2838] text-gray-300 border border-[#2a475e] hover:text-white' }}">
                Terhapus
            </a>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col md:flex-row gap-4 items-end">
            @if($isTrash)
                <input type="hidden" name="trash" value="1">
            @endif
            <div class="w-full md:w-1/3">
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Cari User</label>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full p-2 steam-input" placeholder="Nama atau Email...">
            </div>
            <div class="w-full md:w-1/4">
                <label class="block text-xs text-gray-400 uppercase tracking-wider mb-1">Role</label>
                <select name="role" class="w-full p-2 steam-input">
                    <option value="">Semua Role</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition">Filter</button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('admin.users.index', $isTrash ? ['trash' => 1] : []) }}" class="px-4 py-2 text-gray-400 hover:text-white ml-2">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div class="steam-card rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left steam-table">
                <thead>
                    <tr>
                        <th class="p-4">ID</th>
                        <th class="p-4">Nama</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Bergabung</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="p-4 text-gray-400">#{{ $user->id }}</td>
                            <td class="p-4 font-medium text-white">{{ $user->name }}</td>
                            <td class="p-4 text-gray-400">{{ $user->email }}</td>
                            <td class="p-4">
                                @if($user->is_admin)
                                    <span class="px-2 py-1 text-xs rounded bg-blue-900 text-blue-200 border border-blue-700">Admin</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-gray-800 text-gray-300 border border-gray-600">User</span>
                                @endif
                            </td>
                            <td class="p-4 text-sm text-gray-400">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="p-4 flex flex-wrap justify-center gap-2">
                                @if($isTrash)
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Restore user ini?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs rounded bg-green-900 hover:bg-green-800 text-green-200 transition">Restore</button>
                                    </form>
                                    <form action="{{ route('admin.users.force-destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus permanen user ini? Data tidak bisa dikembalikan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs rounded bg-red-900 hover:bg-red-800 text-red-200 transition">Hapus Permanen</button>
                                    </form>
                                @elseif($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.toggle-admin', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-xs rounded {{ $user->is_admin ? 'bg-orange-900 hover:bg-orange-800 text-orange-200' : 'bg-blue-900 hover:bg-blue-800 text-blue-200' }} transition">
                                            {{ $user->is_admin ? 'Cabut Admin' : 'Jadikan Admin' }}
                                        </button>
                                    </form>
                                    
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1 text-xs rounded bg-red-900 hover:bg-red-800 text-red-200 transition">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs text-gray-500 italic">It's you</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    <p>{{ $isTrash ? 'Tidak ada user terhapus.' : 'Tidak ada user yang ditemukan.' }}</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-[#2a475e] bg-[#1b2838]">
            {{ $users->links() }}
        </div>
    </div>
@endsection
