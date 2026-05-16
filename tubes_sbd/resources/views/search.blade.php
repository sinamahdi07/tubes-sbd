@extends('layouts.store')

@section('content')

<!-- SEARCH BAR -->
    <section class="bg-[#1f2f42] border-y border-[#2a475e] py-5 relative z-50">

    <div class="max-w-7xl mx-auto px-6">

        <div class="relative">

            <form action="{{ route('games.search') }}" method="GET" class="flex gap-4">

                <input
                    type="text"
                    id="search-input"
                    name="search"
                    autocomplete="off"
                    placeholder="Search games..."
                    class="w-full
                           bg-[#0f1923]
                           border border-[#316282]
                           focus:border-[#66c0f4]
                           outline-none
                           px-5 py-4
                           rounded-xl
                           text-white"
                >

                <button
                    type="submit"
                    class="steam-blue px-8 rounded-xl font-semibold"
                >
                    Search
                </button>

            </form>


<div class="max-w-7xl mx-auto px-6 py-10">

    <!-- SEARCH HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white mb-4">
            Search Results
        </h1>

        <p class="text-gray-400">
            {{ $games->total() }} games found for:
            <span class="text-[#66c0f4] font-semibold">
                "{{ $search }}"
            </span>
        </p>

    </div>

            <!-- DROPDOWN -->
            <div id="search-results"
                 class="absolute
                        top-full
                        left-0
                        w-full
                        bg-[#16202d]
                        border border-[#2a475e]
                        rounded-xl
                        mt-2
                        hidden
                        overflow-hidden
                        shadow-2xl
                        z-[999]">
            </div>

        </div>

    </div>

</section>

    <!-- GAME LIST -->
    <div class="space-y-5">

        @forelse($games as $game)

            <a href="{{ url('/game/' . $game->game_id) }}"
               class="block">

                <div class="bg-[#16202d]
                            hover:bg-[#1f2f42]
                            border border-[#2a475e]
                            rounded-2xl
                            overflow-hidden
                            transition">

                    <div class="flex gap-6">

                        <img
                            src="{{ $game->thumbnail_url }}"
                            class="w-[320px] h-[180px] object-cover"
                        >

                        <div class="p-6 flex flex-col justify-between flex-1">

                            <div>

                                <h2 class="text-3xl font-bold text-white mb-3">
                                    {{ $game->title }}
                                </h2>

                                <p class="text-gray-400 line-clamp-3">
                                    {{ $game->description }}
                                </p>

                            </div>

                            <div class="flex justify-between items-center mt-6">

                                <div class="text-[#66c0f4]">
                                    {{ $game->publisher->name ?? 'Unknown' }}
                                </div>

                                <div class="text-2xl font-bold text-white">
                                    Rp {{ number_format($game->price, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </a>

        @empty

            <div class="text-center py-20">

                <h2 class="text-4xl font-bold text-gray-300">
                    Game not found
                </h2>

            </div>

        @endforelse

    </div>

    <!-- PAGINATION -->
    <div class="mt-12 flex justify-center">

        {{ $games->links() }}

    </div>

</div>

@endsection