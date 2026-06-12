@extends('admin.layouts.app')

@section('title', 'Manajemen Kategori')

@push('styles')
<style>
    .data-row { transition: background 0.2s ease; }
    .data-row:hover { background: rgba(17, 141, 255, 0.04); }
    .data-row td { border-bottom: 1px solid rgba(255,255,255,0.04); }
    .admin-input {
        padding: 0.625rem 0.75rem; border-radius: 0.75rem; font-size: 0.875rem;
        font-weight: 500; color: white; width: 100%;
        background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);
        transition: all 0.2s ease; outline: none;
    }
    .admin-input:focus { border-color: rgba(17,141,255,0.6); box-shadow: 0 0 0 2px rgba(17,141,255,0.2); }
    .admin-input::placeholder { color: #6b7280; }
</style>
@endpush

@section('content')
    @php $isTrash = request()->boolean('trash'); @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== ADD FORM ===== --}}
        <div class="premium-card overflow-hidden">
            <div class="px-6 py-5 border-b border-white/5 bg-white/3">
                <div class="flex items-center gap-3">
                    <div class="h-2 w-2 rounded-full bg-teal-400 animate-pulse"></div>
                    <h2 class="text-sm font-black uppercase tracking-widest text-white">Tambah Kategori</h2>
                </div>
            </div>
            <div class="p-6">
                @unless($isTrash)
                    <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Nama Kategori</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="admin-input" placeholder="misal: Single-player, Multiplayer...">
                        </div>
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                                       bg-[#118dff] text-white hover:bg-blue-500 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Kategori
                        </button>
                    </form>
                @else
                    <p class="text-xs text-gray-500 italic">Mode terhapus aktif. Kembali ke data aktif untuk menambah data.</p>
                @endunless
            </div>
        </div>

        {{-- ===== LIST ===== --}}
        <div class="lg:col-span-2 flex flex-col gap-4">

            {{-- Filter Bar --}}
            <div class="flex items-center gap-3 flex-wrap lg:flex-nowrap">
                <div class="flex items-center gap-1 p-1 rounded-xl bg-white/5 border border-white/8 flex-shrink-0">
                    <a href="{{ route('admin.categories.index', request()->except(['trash', 'page'])) }}"
                       class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                              {{ !$isTrash ? 'bg-[#118dff] text-white shadow-lg shadow-blue-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Aktif
                    </a>
                    <a href="{{ route('admin.categories.index', array_merge(request()->except('page'), ['trash' => 1])) }}"
                       class="px-4 py-2 rounded-lg text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap
                              {{ $isTrash ? 'bg-red-500/80 text-white shadow-lg shadow-red-500/20' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                        Terhapus
                    </a>
                </div>
                <form method="GET" action="{{ route('admin.categories.index') }}" class="flex items-center gap-2 flex-1 min-w-0">
                    @if($isTrash)<input type="hidden" name="trash" value="1">@endif
                    <div class="relative flex-1 min-w-0">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kategori..."
                               class="w-full pl-9 pr-3 py-2.5 rounded-xl text-sm font-medium text-white placeholder-gray-500
                                      bg-white/5 border border-white/10 focus:border-[#118dff]/60
                                      focus:outline-none focus:ring-2 focus:ring-[#118dff]/20 transition-all">
                    </div>
                    <button type="submit" class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest
                                                   bg-white/5 border border-white/10 text-gray-300 hover:bg-[#118dff]/15 hover:border-[#118dff]/40 hover:text-white transition-all whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4h18M7 8h10M11 12h2M13 16h-2"/></svg>
                        Filter
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.categories.index', $isTrash ? ['trash'=>1] : []) }}"
                           class="flex-shrink-0 flex items-center gap-1.5 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest text-gray-500 hover:text-red-400 border border-transparent hover:border-red-500/30 transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18 18 6M6 6l12 12"/></svg>
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Table --}}
            <div class="premium-card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/3">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500">Nama Kategori</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 text-center w-40">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categories as $category)
                                <tr class="data-row">
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-black
                                                     bg-teal-500/10 text-teal-300 border border-teal-500/20">
                                            {{ $category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($isTrash)
                                                <form action="{{ route('admin.categories.restore', $category->category_id) }}" method="POST" class="inline"
                                                      onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin mengembalikan kategori {{ addslashes($category->name) }}?', 'info', 'Restore Kategori');">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/20 transition-all">Restore</button>
                                                </form>
                                                <form action="{{ route('admin.categories.force-destroy', $category->category_id) }}" method="POST" class="inline"
                                                      onsubmit="return adminConfirmSubmit(event, 'Apakah Anda yakin ingin menghapus permanen kategori ini? Data tidak dapat dikembalikan.', 'danger', 'Hapus Permanen');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all">Hapus Permanen</button>
                                                </form>
                                            @else
                                                <button onclick="openEditModal({{ $category->category_id }}, '{{ addslashes($category->name) }}')"
                                                        class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-[#118dff]/10 text-[#118dff] border border-[#118dff]/20 hover:bg-[#118dff]/20 transition-all">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.categories.destroy', $category->category_id) }}" method="POST" class="inline"
                                                      onsubmit="return adminConfirmSubmit(event, 'Yakin ingin menghapus kategori {{ addslashes($category->name) }}?', 'danger', 'Hapus Kategori');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition-all">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="px-6 py-12 text-center text-sm font-bold text-gray-500">{{ $isTrash ? 'Tidak ada kategori terhapus.' : 'Belum ada kategori.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-white/5 bg-white/2 flex items-center justify-between gap-4">
                    <p class="text-xs font-bold text-gray-500">Total: <span class="text-gray-300">{{ $categories->total() }}</span> kategori</p>
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div id="edit-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center">
        <div class="premium-card w-full max-w-md p-6 mx-4">
            <h3 class="text-base font-black uppercase tracking-widest text-white mb-5">Edit Kategori</h3>
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 mb-2">Nama Kategori</label>
                    <input type="text" name="name" id="edit-name" required class="admin-input">
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest bg-[#118dff] text-white hover:bg-blue-500 transition-all">Simpan</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest bg-white/5 text-gray-400 hover:text-white border border-white/10 transition-all">Batal</button>
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
document.getElementById('edit-modal').addEventListener('click', function(e) { if(e.target===this) closeEditModal(); });
</script>
@endpush
