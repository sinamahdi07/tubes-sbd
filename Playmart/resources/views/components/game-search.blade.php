@props([
    'value' => '',
    'categories' => collect(),
    'genres' => collect(),
    'selectedCategory' => null,
    'selectedGenre' => null,
    'contained' => true,
])

@once
    <style>
        .playmart-filter-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            background-image: none !important;
        }

        .playmart-filter-select::-ms-expand {
            display: none;
        }
    </style>
@endonce

<section class="relative z-30 {{ $contained ? 'mx-auto max-w-[1700px] px-4 py-5 sm:px-6 lg:px-8' : '' }}">
    <div class="rounded-xl border border-[#118dff]/35 bg-[#02060d]/95 p-3 shadow-[0_18px_48px_rgba(0,0,0,0.45),inset_0_1px_0_rgba(102,192,244,0.08)] backdrop-blur-xl">
        <div class="relative" data-game-search data-autocomplete-url="{{ route('games.autocomplete') }}">
            <form action="{{ route('games.search') }}" method="GET" class="grid gap-3 md:grid-cols-2 xl:grid-cols-[minmax(0,1fr)_220px_240px_140px]">
                <div class="relative md:col-span-2 xl:col-span-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-5 top-1/2 h-6 w-6 -translate-y-1/2 text-[#2fb7ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.1" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                    <input
                        type="text"
                        name="search"
                        value="{{ $value }}"
                        autocomplete="off"
                        placeholder="Search for games, DLCs, and more..."
                        data-game-search-input
                        class="h-14 w-full rounded-lg border border-[#118dff]/55 bg-[#030912]/95 pl-14 pr-5 text-base font-bold text-white outline-none shadow-[inset_0_1px_0_rgba(102,192,244,0.07)] transition placeholder:text-slate-500 hover:border-[#2fb7ff] hover:bg-[#06111f] focus:border-[#2fb7ff] focus:bg-[#06111f] focus:ring-0"
                    >
                </div>

                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-6 w-6 -translate-y-1/2 text-[#2fb7ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h4m-2-2v4m7-2h.01M18 10h.01M7.5 7h9A4.5 4.5 0 0 1 21 11.5v2.2a3.3 3.3 0 0 1-5.72 2.24l-.72-.77a2.2 2.2 0 0 0-1.6-.7h-1.92a2.2 2.2 0 0 0-1.6.7l-.72.77A3.3 3.3 0 0 1 3 13.7v-2.2A4.5 4.5 0 0 1 7.5 7Z"/>
                    </svg>
                    <select
                        name="genre"
                        data-game-search-genre
                        onchange="this.form.submit()"
                        class="playmart-filter-select h-14 w-full appearance-none rounded-lg border border-[#2a475e] bg-[#030912]/95 pl-12 pr-10 text-sm font-black text-white outline-none shadow-[inset_0_1px_0_rgba(102,192,244,0.07)] transition hover:border-[#2fb7ff] hover:bg-[#06111f] focus:border-[#2fb7ff] focus:bg-[#06111f] focus:ring-0"
                        style="color-scheme: dark;"
                    >
                        <option value="" class="bg-[#07111d] text-slate-100" style="background-color: #07111d; color: #f8fafc;" @selected(!$selectedGenre)>All Genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->genre_id }}" class="bg-[#07111d] text-slate-100" style="background-color: #07111d; color: #f8fafc;" @selected((string) $selectedGenre === (string) $genre->genre_id)>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#2fb7ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m6 9 6 6 6-6"/>
                    </svg>
                </div>

                <div class="relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute left-4 top-1/2 h-6 w-6 -translate-y-1/2 text-[#2fb7ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m12 3 8 4-8 4-8-4 8-4Zm8 8-8 4-8-4m16 4-8 4-8-4"/>
                    </svg>
                    <select
                        name="category"
                        data-game-search-category
                        onchange="this.form.submit()"
                        class="playmart-filter-select h-14 w-full appearance-none rounded-lg border border-[#2a475e] bg-[#030912]/95 pl-12 pr-10 text-sm font-black text-white outline-none shadow-[inset_0_1px_0_rgba(102,192,244,0.07)] transition hover:border-[#2fb7ff] hover:bg-[#06111f] focus:border-[#2fb7ff] focus:bg-[#06111f] focus:ring-0"
                        style="color-scheme: dark;"
                    >
                        <option value="" class="bg-[#07111d] text-slate-100" style="background-color: #07111d; color: #f8fafc;" @selected(!$selectedCategory)>All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" class="bg-[#07111d] text-slate-100" style="background-color: #07111d; color: #f8fafc;" @selected((string) $selectedCategory === (string) $category->category_id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-[#2fb7ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="m6 9 6 6 6-6"/>
                    </svg>
                </div>

                <button type="submit" class="h-14 rounded-lg bg-gradient-to-br from-[#06bfff] to-[#2d73ff] px-5 text-sm font-black uppercase tracking-wide text-white shadow-[0_12px_28px_rgba(17,141,255,0.28)] transition hover:-translate-y-0.5 hover:brightness-110 md:col-span-2 xl:col-span-1">
                    <span class="flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                        </svg>
                        Search
                    </span>
                </button>
            </form>

            <div
                data-game-search-results
                class="relative z-40 mt-4 hidden max-h-96 w-full overflow-auto rounded-xl border border-[#118dff]/35 bg-[#030912] shadow-2xl shadow-black/50"
            ></div>
        </div>
    </div>
</section>

@once
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-game-search]').forEach((root) => {
                const input = root.querySelector('[data-game-search-input]');
                const genre = root.querySelector('[data-game-search-genre]');
                const category = root.querySelector('[data-game-search-category]');
                const results = root.querySelector('[data-game-search-results]');
                const autocompleteUrl = root.dataset.autocompleteUrl;
                let controller = null;
                let activeIndex = -1;

                const hideResults = () => {
                    results.classList.add('hidden');
                    results.innerHTML = '';
                    activeIndex = -1;
                };

                const formatPrice = (price) => {
                    const value = Number(price || 0);
                    return value === 0 ? 'Gratis' : `Rp ${value.toLocaleString('id-ID')}`;
                };

                const setActive = (links, index) => {
                    links.forEach((link) => link.classList.remove('bg-[#1f2f42]'));
                    activeIndex = index;

                    if (links[activeIndex]) {
                        links[activeIndex].classList.add('bg-[#1f2f42]');
                        links[activeIndex].scrollIntoView({ block: 'nearest' });
                    }
                };

                const renderResults = (games) => {
                    results.innerHTML = '';

                    if (games.length === 0) {
                        const empty = document.createElement('div');
                        empty.className = 'p-4 text-sm font-semibold text-gray-400';
                        empty.textContent = 'Game not found';
                        results.appendChild(empty);
                        results.classList.remove('hidden');
                        return;
                    }

                    games.forEach((game) => {
                        const link = document.createElement('a');
                        link.href = game.url;
                        link.className = 'flex items-center gap-4 p-3 transition hover:bg-[#1f2f42]';

                        const image = document.createElement('img');
                        image.src = game.thumbnail_url || 'https://images.unsplash.com/photo-1552820728-8b83bb6b773f?q=70&w=400&auto=format&fit=crop';
                        image.alt = game.title;
                        image.className = 'h-14 w-24 rounded-md object-cover';

                        const info = document.createElement('div');
                        info.className = 'min-w-0';

                        const title = document.createElement('div');
                        title.className = 'truncate text-sm font-black uppercase text-white';
                        title.textContent = game.title;

                        const price = document.createElement('div');
                        price.className = 'mt-1 text-sm font-bold text-[#66c0f4]';
                        price.textContent = formatPrice(game.price);

                        info.append(title, price);
                        link.append(image, info);
                        results.appendChild(link);
                    });

                    results.classList.remove('hidden');
                };

                input.addEventListener('input', async () => {
                    const query = input.value.trim();

                    if (query.length < 1) {
                        hideResults();
                        return;
                    }

                    if (controller) {
                        controller.abort();
                    }

                    controller = new AbortController();

                    try {
                        const params = new URLSearchParams({ search: query });

                        if (genre && genre.value) {
                            params.set('genre', genre.value);
                        }

                        if (category && category.value) {
                            params.set('category', category.value);
                        }

                        const response = await fetch(`${autocompleteUrl}?${params.toString()}`, {
                            headers: { Accept: 'application/json' },
                            signal: controller.signal,
                        });

                        if (!response.ok) {
                            hideResults();
                            return;
                        }

                        renderResults(await response.json());
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            hideResults();
                        }
                    }
                });

                [genre, category].filter(Boolean).forEach((filter) => {
                    filter.addEventListener('change', () => {
                        if (input.value.trim().length >= 1) {
                            input.dispatchEvent(new Event('input'));
                        }
                    });
                });

                input.addEventListener('keydown', (event) => {
                    const links = Array.from(results.querySelectorAll('a'));

                    if (results.classList.contains('hidden') || links.length === 0) {
                        return;
                    }

                    if (event.key === 'ArrowDown') {
                        event.preventDefault();
                        setActive(links, Math.min(activeIndex + 1, links.length - 1));
                    }

                    if (event.key === 'ArrowUp') {
                        event.preventDefault();
                        setActive(links, Math.max(activeIndex - 1, 0));
                    }

                    if (event.key === 'Enter' && activeIndex >= 0) {
                        event.preventDefault();
                        links[activeIndex].click();
                    }

                    if (event.key === 'Escape') {
                        hideResults();
                    }
                });

                document.addEventListener('click', (event) => {
                    if (!root.contains(event.target)) {
                        hideResults();
                    }
                });
            });
        });
    </script>
@endonce
