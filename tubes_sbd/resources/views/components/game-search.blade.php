@props([
    'value' => '',
    'categories' => collect(),
    'genres' => collect(),
    'selectedCategory' => null,
    'selectedGenre' => null,
    'contained' => true,
])

<section class="relative z-[60] {{ $contained ? 'mx-auto max-w-[1700px] px-4 py-5 sm:px-6 lg:px-8' : '' }}">
    <div class="rounded-lg border border-[#2a475e] bg-[#0f1923]/90 p-2 shadow-2xl shadow-black/25">
        <div class="relative" data-game-search data-autocomplete-url="{{ route('games.autocomplete') }}">
            <form action="{{ route('games.search') }}" method="GET" class="grid gap-3 lg:grid-cols-[minmax(0,1fr)_220px_220px_120px]">
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $value }}"
                        autocomplete="off"
                        placeholder="Search for games, DLCs, and more..."
                        data-game-search-input
                        class="h-12 w-full rounded-md border border-[#22364b] bg-[#07111d] pl-5 pr-12 text-sm font-semibold text-white outline-none transition placeholder:text-gray-500 focus:border-[#66c0f4] focus:ring-0"
                    >
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z"/>
                    </svg>
                </div>

                <div class="relative">
                    <select
                        name="genre"
                        data-game-search-genre
                        onchange="this.form.submit()"
                        class="h-12 w-full appearance-none rounded-md border border-[#22364b] bg-[#07111d] px-5 pr-10 text-sm font-semibold text-white outline-none transition focus:border-[#66c0f4] focus:ring-0"
                    >
                        <option value="" @selected(!$selectedGenre)>All Genres</option>
                        @foreach($genres as $genre)
                            <option value="{{ $genre->genre_id }}" @selected((string) $selectedGenre === (string) $genre->genre_id)>
                                {{ $genre->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                    </svg>
                </div>

                <div class="relative">
                    <select
                        name="category"
                        data-game-search-category
                        onchange="this.form.submit()"
                        class="h-12 w-full appearance-none rounded-md border border-[#22364b] bg-[#07111d] px-5 pr-10 text-sm font-semibold text-white outline-none transition focus:border-[#66c0f4] focus:ring-0"
                    >
                        <option value="" @selected(!$selectedCategory)>All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->category_id }}" @selected((string) $selectedCategory === (string) $category->category_id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <svg xmlns="http://www.w3.org/2000/svg" class="pointer-events-none absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 9 6 6 6-6"/>
                    </svg>
                </div>

                <button type="submit" class="steam-blue h-12 rounded-md px-7 text-sm font-black text-white shadow-lg shadow-blue-950/35 transition hover:brightness-110">
                    Search
                </button>
            </form>

            <div
                data-game-search-results
                class="absolute left-0 top-full z-[70] mt-2 hidden max-h-96 w-full overflow-auto rounded-lg border border-[#2a475e] bg-[#0f1923] shadow-2xl shadow-black/40"
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
