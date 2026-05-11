<!DOCTYPE html>
<html>
<head>
    <title>{{ $game->title }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#1b2838] text-white">

    <div class="max-w-6xl mx-auto px-6 py-10">

        <img
            src="{{ $game->thumbnail }}"
            class="w-full h-[500px] object-cover rounded-2xl"
        >

        <div class="mt-8">

            <h1 class="text-5xl font-bold mb-4">

                {{ $game->title }}

            </h1>

            <div class="flex gap-4 text-sm text-[#66c0f4] mb-6">

                <span>
                    Publisher:
                    {{ $game->publisher->name ?? '-' }}
                </span>

                <span>
                    Developer:
                    {{ $game->developer->name ?? '-' }}
                </span>

            </div>

            <p class="text-gray-300 leading-relaxed text-lg">

                {{ $game->description }}

            </p>

            <div class="mt-10 flex items-center gap-5">

                <div class="text-4xl font-bold text-[#66c0f4]">

                    Rp {{ number_format($game->price, 0, ',', '.') }}

                </div>

                <button class="bg-[#5c7e10]
                               hover:bg-[#7ea64b]
                               px-8 py-4
                               rounded-xl
                               font-bold">

                    Add to Cart

                </button>

            </div>

        </div>

    </div>

</body>
</html>