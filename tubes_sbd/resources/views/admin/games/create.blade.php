@extends('admin.layouts.app')

@section('title', 'Tambah Game Baru')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.games.index') }}" class="text-sm text-gray-400 hover:text-white">← Kembali ke daftar game</a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-5 border-b border-[#2a475e]">
                <h2 class="text-lg font-bold text-[#66c0f4]">Informasi Game</h2>
            </div>

            <form action="{{ route('admin.games.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Judul Game <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full p-3 steam-input rounded text-white">
                    </div>

                    {{-- Developer --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Developer <span class="text-red-400">*</span></label>
                        <select name="developer_id" required class="w-full p-3 steam-input rounded text-white">
                            <option value="">-- Pilih Developer --</option>
                            @foreach($developers as $dev)
                                <option value="{{ $dev->developer_id }}" {{ old('developer_id') == $dev->developer_id ? 'selected' : '' }}>
                                    {{ $dev->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Publisher --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Publisher <span class="text-red-400">*</span></label>
                        <select name="publisher_id" required class="w-full p-3 steam-input rounded text-white">
                            <option value="">-- Pilih Publisher --</option>
                            @foreach($publishers as $pub)
                                <option value="{{ $pub->publisher_id }}" {{ old('publisher_id') == $pub->publisher_id ? 'selected' : '' }}>
                                    {{ $pub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Harga (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="price" value="{{ old('price', 0) }}" min="0" step="0.01" required
                               class="w-full p-3 steam-input rounded text-white">
                        <p class="text-xs text-gray-500 mt-1">Isi 0 untuk game gratis</p>
                    </div>

                    {{-- Release Date --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Tanggal Rilis</label>
                        <input type="date" name="release_date" value="{{ old('release_date') }}"
                               class="w-full p-3 steam-input rounded text-white">
                    </div>

                    {{-- Thumbnail URL --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">URL Thumbnail</label>
                        <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url') }}"
                               placeholder="https://..." class="w-full p-3 steam-input rounded text-white"
                               id="thumbnail_url_input">
                        <div id="thumb-preview" class="mt-2 hidden">
                            <img id="thumb-img" src="" alt="Preview" class="h-20 rounded border border-gray-700">
                        </div>
                    </div>

                    {{-- Genres --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-2">Genre</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach($genres as $genre)
                                <label class="flex items-center gap-2 p-2 bg-[#1b2838] border border-[#2a475e] rounded cursor-pointer hover:border-[#66c0f4] transition">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->genre_id }}"
                                           {{ in_array($genre->genre_id, old('genres', [])) ? 'checked' : '' }}
                                           class="accent-[#66c0f4]">
                                    <span class="text-sm text-gray-300">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="description" rows="5" class="w-full p-3 steam-input rounded text-white resize-y"
                                  placeholder="Deskripsi singkat tentang game...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex gap-4 pt-4 border-t border-[#2a475e]">
                    <button type="submit" class="px-6 py-2 steam-btn-primary rounded font-semibold">
                        Simpan Game
                    </button>
                    <a href="{{ route('admin.games.index') }}" class="px-6 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded transition">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const input = document.getElementById('thumbnail_url_input');
    const preview = document.getElementById('thumb-preview');
    const img = document.getElementById('thumb-img');

    input.addEventListener('input', function () {
        const url = this.value.trim();
        if (url.startsWith('http')) {
            img.src = url;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    });

    // Show preview if old value present
    if (input.value.trim().startsWith('http')) {
        img.src = input.value.trim();
        preview.classList.remove('hidden');
    }
</script>
@endpush
