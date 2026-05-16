@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Add Category Form --}}
        <div class="steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e]">
                <h2 class="font-bold text-[#66c0f4]">Tambah Kategori</h2>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-4 space-y-4">
                @csrf
                <div>
                    <label class="block text-sm text-gray-300 mb-1">Nama Kategori</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full p-2 steam-input rounded text-white" placeholder="misal: Single-player, Multiplayer...">
                </div>
                <button type="submit" class="w-full px-4 py-2 steam-btn-primary rounded font-semibold text-sm">
                    + Tambah Kategori
                </button>
            </form>
        </div>

        {{-- Categories List --}}
        <div class="md:col-span-2 steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-4 border-b border-[#2a475e] flex items-center justify-between">
                <h2 class="font-bold text-[#66c0f4]">Daftar Kategori ({{ $categories->total() }})</h2>
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                           class="p-1.5 text-sm steam-input rounded text-white w-40">
                    <button type="submit" class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 text-white rounded">Go</button>
                </form>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left steam-table">
                    <thead>
                        <tr>
                            <th class="p-4">Nama Kategori</th>
                            <th class="p-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td class="p-4">
                                    <span class="px-3 py-1 bg-[#1b2838] text-[#66c0f4] border border-[#2a475e] rounded-full text-sm">
                                        {{ $category->name }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <div class="flex justify-center gap-2">
                                        <button onclick="openEditModal({{ $category->category_id }}, '{{ addslashes($category->name) }}')"
                                                class="px-3 py-1 text-xs bg-[#1b2838] hover:bg-[#2a475e] text-[#66c0f4] border border-[#2a475e] rounded transition">
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST"
                                              onsubmit="return confirm('Hapus kategori {{ addslashes($category->name) }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-3 py-1 text-xs bg-red-900/50 hover:bg-red-800 text-red-300 border border-red-800 rounded transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-8 text-center text-gray-500">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-[#2a475e] bg-[#1b2838]">
                {{ $categories->links() }}
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/70 z-50 hidden items-center justify-center">
        <div class="steam-card rounded-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-white mb-4">Edit Kategori</h3>
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm text-gray-300 mb-1">Nama Kategori</label>
                    <input type="text" name="name" id="edit-name" required class="w-full p-2 steam-input rounded text-white">
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
function openEditModal(id, name) {
    document.getElementById('edit-name').value = name;
    document.getElementById('edit-form').action = '/admin/categories/' + id;
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
