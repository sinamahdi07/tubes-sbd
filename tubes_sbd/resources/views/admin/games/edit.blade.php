@extends('admin.layouts.app')

@section('title', 'Edit Game: ' . $game->title)

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.games.index') }}" class="text-sm text-gray-400 hover:text-white">← Kembali ke daftar game</a>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="steam-card rounded-lg overflow-hidden">
            <div class="bg-[#1b2838] p-5 border-b border-[#2a475e] flex items-center gap-4">
                @if($game->thumbnail_url)
                    <img src="{{ $game->thumbnail_url }}" alt="{{ $game->title }}" class="w-24 h-14 object-cover rounded">
                @endif
                <div>
                    <h2 class="text-lg font-bold text-white">{{ $game->title }}</h2>
                    <p class="text-sm text-gray-400">ID: {{ $game->game_id }}</p>
                </div>
            </div>

            <form action="{{ route('admin.games.update', $game->game_id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Judul Game <span class="text-red-400">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $game->title) }}" required
                               class="w-full p-3 steam-input rounded text-white">
                    </div>

                    {{-- Developer --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Developer <span class="text-red-400">*</span></label>
                        <select name="developer_id" required class="w-full p-3 steam-input rounded text-white">
                            @foreach($developers as $dev)
                                <option value="{{ $dev->developer_id }}"
                                    {{ old('developer_id', $game->developer_id) == $dev->developer_id ? 'selected' : '' }}>
                                    {{ $dev->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Publisher --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Publisher <span class="text-red-400">*</span></label>
                        <select name="publisher_id" required class="w-full p-3 steam-input rounded text-white">
                            @foreach($publishers as $pub)
                                <option value="{{ $pub->publisher_id }}"
                                    {{ old('publisher_id', $game->publisher_id) == $pub->publisher_id ? 'selected' : '' }}>
                                    {{ $pub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Harga (Rp) <span class="text-red-400">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $game->price) }}" min="0" step="0.01" required
                               class="w-full p-3 steam-input rounded text-white">
                    </div>

                    {{-- Release Date --}}
                    <div>
                        <label class="block text-sm text-gray-300 mb-1">Tanggal Rilis</label>
                        <input type="date" name="release_date"
                               value="{{ old('release_date', $game->release_date ? $game->release_date->format('Y-m-d') : '') }}"
                               class="w-full p-3 steam-input rounded text-white">
                    </div>

                    {{-- Thumbnail URL --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">URL Thumbnail</label>
                        <input type="url" name="thumbnail_url" value="{{ old('thumbnail_url', $game->thumbnail_url) }}"
                               placeholder="https://..." class="w-full p-3 steam-input rounded text-white"
                               id="thumbnail_url_input">
                        <div id="thumb-preview" class="{{ $game->thumbnail_url ? '' : 'hidden' }} mt-2">
                            <img id="thumb-img" src="{{ $game->thumbnail_url }}" alt="Preview" class="h-20 rounded border border-gray-700">
                        </div>
                    </div>

                    {{-- Genres --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-2">Genre</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                            @foreach($genres as $genre)
                                <label class="flex items-center gap-2 p-2 bg-[#1b2838] border border-[#2a475e] rounded cursor-pointer hover:border-[#66c0f4] transition">
                                    <input type="checkbox" name="genres[]" value="{{ $genre->genre_id }}"
                                           {{ in_array($genre->genre_id, old('genres', $selectedGenres)) ? 'checked' : '' }}
                                           class="accent-[#66c0f4]">
                                    <span class="text-sm text-gray-300">{{ $genre->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-1">Deskripsi</label>
                        <textarea name="description" rows="5" class="w-full p-3 steam-input rounded text-white resize-y">{{ old('description', $game->description) }}</textarea>
                    </div>

                    {{-- Screenshots Section --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm text-gray-300 mb-2">Screenshots</label>
                        
                        {{-- Existing Screenshots --}}
                        @if($game->screenshots->count() > 0)
                            <div class="mb-4">
                                <p class="text-xs text-gray-400 mb-2">Screenshot yang ada:</p>
                                <div id="existing-screenshots" class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                    @foreach($game->screenshots as $screenshot)
                                        <div class="relative group screenshot-item" data-id="{{ $screenshot->screenshot_id }}">
                                            <img src="{{ $screenshot->url }}" alt="Screenshot" class="w-full h-32 object-cover rounded border border-[#2a475e]">
                                            <button type="button" onclick="deleteScreenshot({{ $screenshot->screenshot_id }})" 
                                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                ×
                                            </button>
                                            <input type="hidden" name="delete_screenshots[]" value="" class="delete-input">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Add New Screenshots --}}
                        <div>
                            <p class="text-xs text-gray-400 mb-2">Tambah screenshot baru:</p>
                            <div id="screenshots-container" class="space-y-3">
                                <!-- Screenshot inputs will be added here -->
                            </div>
                            <button type="button" onclick="addScreenshotInput()" 
                                    class="mt-3 px-4 py-2 bg-[#1b2838] hover:bg-[#2a475e] border border-[#66c0f4] text-[#66c0f4] rounded text-sm transition">
                                + Tambah Screenshot
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-4 border-t border-[#2a475e]">
                    <button type="submit" class="px-6 py-2 steam-btn-primary rounded font-semibold">
                        Perbarui Game
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
    // Thumbnail preview
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

    // Screenshot management
    let screenshotIndex = 0;

    function addScreenshotInput() {
        const container = document.getElementById('screenshots-container');
        const div = document.createElement('div');
        div.className = 'flex gap-2 items-start screenshot-input-group';
        div.innerHTML = `
            <div class="flex-1">
                <input type="url" 
                       name="screenshots[${screenshotIndex}][url]" 
                       placeholder="https://..." 
                       class="w-full p-3 steam-input rounded text-white screenshot-url-input"
                       data-index="${screenshotIndex}">
                <input type="hidden" name="screenshots[${screenshotIndex}][order]" value="${screenshotIndex}">
                <div class="screenshot-preview-${screenshotIndex} hidden mt-2">
                    <img src="" alt="Preview" class="h-20 rounded border border-gray-700">
                </div>
            </div>
            <button type="button" onclick="removeScreenshotInput(this)" 
                    class="mt-3 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm transition">
                Hapus
            </button>
        `;
        container.appendChild(div);

        // Add preview functionality
        const urlInput = div.querySelector('.screenshot-url-input');
        const previewDiv = div.querySelector(`.screenshot-preview-${screenshotIndex}`);
        const previewImg = previewDiv.querySelector('img');
        
        urlInput.addEventListener('input', function() {
            const url = this.value.trim();
            if (url.startsWith('http')) {
                previewImg.src = url;
                previewDiv.classList.remove('hidden');
            } else {
                previewDiv.classList.add('hidden');
            }
        });

        screenshotIndex++;
    }

    function removeScreenshotInput(button) {
        button.closest('.screenshot-input-group').remove();
    }

    function deleteScreenshot(screenshotId) {
        if (confirm('Hapus screenshot ini?')) {
            const item = document.querySelector(`.screenshot-item[data-id="${screenshotId}"]`);
            if (item) {
                // Mark for deletion
                const deleteInput = item.querySelector('.delete-input');
                deleteInput.value = screenshotId;
                deleteInput.name = 'delete_screenshots[]';
                
                // Hide the item
                item.style.opacity = '0.3';
                item.querySelector('button').disabled = true;
                
                // Add a visual indicator
                const overlay = document.createElement('div');
                overlay.className = 'absolute inset-0 bg-red-900 bg-opacity-50 flex items-center justify-center rounded';
                overlay.innerHTML = '<span class="text-white text-xs font-bold">AKAN DIHAPUS</span>';
                item.appendChild(overlay);
            }
        }
    }

    // Add one screenshot input by default if no screenshots exist
    @if($game->screenshots->count() === 0)
        addScreenshotInput();
    @endif
</script>
@endpush
