@extends('admin.layouts.app')

@section('title', 'Manajemen Platform')

@section('content')
    @php
        $isTrash = request()->boolean('trash');
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Add Platform Form --}}
        <div class="steam-card rounded-lg overflow-hidden {{ $isTrash ? 'opacity-60' : '' }}">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                <h2 class="font-bold text-[#66c0f4]">Tambah Platform</h2>
            </div>
            <form action="{{ route('admin.platforms.store') }}" method="POST" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nama Platform</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full p-2 steam-input rounded text-white" placeholder="misal: Windows, macOS...">
                </div>
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                           class="w-full p-2 steam-input rounded text-white" placeholder="misal: windows, macos...">
                </div>
                <button type="submit" class="w-full px-4 py-2 steam-btn-primary rounded font-semibold text-sm">
                    + Tambah Platform
                </button>
                @if($isTrash)
                    <p class="text-xs text-gray-400">Mode terhapus aktif. Kembali ke data aktif untuk menambah data.</p>
                @endif
            </form>
        </div>

        {{-- Platforms List --}}
        <div class="md:col-span-2 steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex flex-col lg:flex-row gap-3 lg:items-center lg:justify-between">
                <h2 class="font-bold text-[#66c0f4]">Daftar Platform ({{ $platforms->total() }})</h2>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.platforms.index', request()->except(['trash', 'page'])) }}"
                       class="px-3 py-1.5 rounded text-sm font-semibold transition {{ $isTrash ? 'bg-[#0f1923] text-gray-300 border border-[#2a475e] hover:text-white' : 'steam-btn-primary' }}">Aktif</a>
                    <a href="{{ route('admin.platforms.index', array_merge(request()->except('page'), ['trash' => 1])) }}"
                       class="px-3 py-1.5 rounded text-sm font-semibold transition {{ $isTrash ? 'steam-btn-primary' : 'bg-[#0f1923] text-gray-300 border border-[#2a475e] hover:text-white' }}">Terhapus</a>
                </div>
                <form method="GET" action="{{ route('admin.platforms.index') }}" class="flex gap-2">
                    @if($isTrash)
                        <input type="hidden" name="trash" value="1">
                    @endif
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                           class="p-1.5 text-sm steam-input rounded text-white w-40">
                    <button type="submit" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 text-white rounded">Go</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left steam-table">
                    <thead>
                        <tr>
                            <th class="p-4">Nama Platform</th>
                            <th class="p-4">Slug</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($platforms as $platform)
                            <tr>
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-[#1b2838] text-[#66c0f4] border border-[#2a475e] rounded-full text-sm">
                                        {{ $platform->name }}
                                    </span>
                                </td>
                                <td class="p-4 text-gray-300 text-sm">{{ $platform->slug }}</td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        @if($isTrash)
                                            <form action="{{ route('admin.platforms.restore', $platform->platform_id) }}" method="POST"
                                                  onsubmit="return confirm('Restore platform {{ addslashes($platform->name) }}?');">
                                                @csrf
                                                <button type="submit" class="px-3 py-1 text-xs bg-green-900/50 hover:bg-green-800 text-green-300 border border-green-800 rounded transition">Restore</button>
                                            </form>
                                        @else
                                            <button onclick="openEditModal({{ $platform->platform_id }}, '{{ addslashes($platform->name) }}', '{{ addslashes($platform->slug) }}')"
                                                    class="px-3 py-1 text-xs bg-[#1b2838] hover:bg-[#2a475e] text-[#66c0f4] border border-[#2a475e] rounded transition">Edit</button>
                                            <form action="{{ route('admin.platforms.destroy', $platform->platform_id) }}" method="POST"
                                                  onsubmit="return confirm('Hapus platform {{ addslashes($platform->name) }}?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="p-8 text-center text-gray-500">{{ $isTrash ? 'Tidak ada platform terhapus.' : 'Belum ada platform.' }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-[#2a475e] bg-[#1b2838]">
                {{ $platforms->links() }}
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center">
        <div class="steam-card rounded-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-white mb-4">Edit Platform</h3>
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm text-gray-300 mb-1">Nama Platform</label>
                    <input type="text" name="name" id="edit-name" required class="w-full p-2 steam-input rounded text-white">
                </div>
                <div class="mb-4">
                    <label class="block text-sm text-gray-300 mb-1">Slug</label>
                    <input type="text" name="slug" id="edit-slug" required class="w-full p-2 steam-input rounded text-white">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-2 steam-btn-primary rounded font-semibold">Simpan</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">Batal</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function openEditModal(id, name, slug) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-slug').value = slug;
    document.getElementById('edit-form').action = '/admin/platforms/' + id;
    document.getElementById('edit-modal').classList.remove('hidden');
    document.getElementById('edit-modal').classList.add('flex');
}
function closeEditModal() {
    document.getElementById('edit-modal').classList.add('hidden');
    document.getElementById('edit-modal').classList.remove('flex');
}
document.getElementById('edit-modal').addEventListener('click', function(e) {
    if (e.target === this) closeEditModal();
});
</script>
@endpush
