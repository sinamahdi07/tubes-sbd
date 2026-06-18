<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\GameDetail;
use App\Models\GameReview;
use App\Models\GameScreenshot;
use App\Models\GameTrailer;
use App\Models\Genre;
use App\Models\Payment;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DemoStoreSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $this->call(PlatformSeeder::class);

            $genres = $this->seedNamedModels(Genre::class, [
                'Action',
                'Adventure',
                'RPG',
                'Racing',
                'Simulation',
                'Strategy',
                'Survival',
                'Indie',
                'Sci-Fi',
                'Fantasy',
            ]);

            $categories = $this->seedNamedModels(Category::class, [
                'Single-player',
                'Multiplayer',
                'Co-op',
                'Controller Support',
                'Open World',
                'Story Rich',
                'Early Access',
                'PvP',
                'Casual',
                'Atmospheric',
            ]);

            $developers = $this->seedNamedModels(Developer::class, [
                'Blue Orbit Studio',
                'Ashen Vale Works',
                'Neon Byte Lab',
                'Northforge Interactive',
                'Pixel Harbor',
            ]);

            $publishers = $this->seedNamedModels(Publisher::class, [
                'PlayMart Publishing',
                'NovaArc Games',
                'IndieForge',
                'Steamline Digital',
            ]);

            $platforms = Platform::query()->pluck('platform_id', 'slug')->all();
            $reviewers = $this->seedUsers();

            $games = [
                [
                    'title' => 'Eclipse Frontier',
                    'developer' => 'Blue Orbit Studio',
                    'publisher' => 'PlayMart Publishing',
                    'genres' => ['RPG', 'Adventure', 'Sci-Fi'],
                    'categories' => ['Single-player', 'Open World', 'Story Rich'],
                    'platforms' => ['windows', 'mac'],
                    'price' => 119000,
                    'discount' => 25,
                    'release_date' => '2026-05-10',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1446776811953-b23d57bd21aa?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'A vast sci-fi RPG set in a fractured galaxy with hostile worlds and ancient secrets.',
                    'description' => 'Build your legend across hostile planets, recruit a crew, and uncover the source of a galaxy-wide eclipse.',
                    'minimum_requirements' => 'OS: Windows 10 64-bit'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM'.PHP_EOL.'Graphics: GTX 1060',
                    'website' => 'https://playmart.local/eclipse-frontier',
                ],
                [
                    'title' => 'Veil of Ashes',
                    'developer' => 'Ashen Vale Works',
                    'publisher' => 'NovaArc Games',
                    'genres' => ['Action', 'RPG', 'Fantasy'],
                    'categories' => ['Single-player', 'Story Rich', 'Controller Support'],
                    'platforms' => ['windows'],
                    'price' => 99000,
                    'discount' => 30,
                    'release_date' => '2026-04-18',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af2176?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'An action RPG where every choice shapes your destiny in a cursed kingdom.',
                    'description' => 'Enter a ruined realm, forge forbidden magic, and decide which houses rise from the ashes.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM',
                    'website' => 'https://playmart.local/veil-of-ashes',
                ],
                [
                    'title' => 'Neon Drift',
                    'developer' => 'Neon Byte Lab',
                    'publisher' => 'Steamline Digital',
                    'genres' => ['Racing', 'Action', 'Sci-Fi'],
                    'categories' => ['Single-player', 'Multiplayer', 'Controller Support'],
                    'platforms' => ['windows', 'linux'],
                    'price' => 79000,
                    'discount' => 20,
                    'release_date' => '2026-05-01',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'High-speed arcade racing in a retro-future city of rain, lights, and rival crews.',
                    'description' => 'Tune hovercars, master neon tracks, and climb the underground racing ladder.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i3'.PHP_EOL.'Memory: 6 GB RAM',
                    'website' => 'https://playmart.local/neon-drift',
                ],
                [
                    'title' => 'Frostbound',
                    'developer' => 'Northforge Interactive',
                    'publisher' => 'IndieForge',
                    'genres' => ['Survival', 'Strategy', 'Adventure'],
                    'categories' => ['Single-player', 'Co-op', 'Atmospheric'],
                    'platforms' => ['windows', 'mac', 'linux'],
                    'price' => 59000,
                    'discount' => 15,
                    'release_date' => '2026-03-22',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1483728642387-6c3bdd6c93e5?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'Survive in a frozen world where cold is your enemy and fire is your currency.',
                    'description' => 'Gather survivors, ration supplies, and push through storms to find the last warm city.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM',
                    'website' => 'https://playmart.local/frostbound',
                ],
                [
                    'title' => 'Iron Legacy',
                    'developer' => 'Northforge Interactive',
                    'publisher' => 'PlayMart Publishing',
                    'genres' => ['Strategy', 'Simulation'],
                    'categories' => ['Single-player', 'Multiplayer', 'PvP'],
                    'platforms' => ['windows'],
                    'price' => 89000,
                    'discount' => 25,
                    'release_date' => '2026-02-14',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1519074069444-1ba4fff66d16?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'Build, command, and conquer as your empire rises from iron and ambition.',
                    'description' => 'Lead armies, manage city states, and outmaneuver rival kingdoms across a living campaign map.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM',
                    'website' => 'https://playmart.local/iron-legacy',
                ],
                [
                    'title' => 'Pixel Haven',
                    'developer' => 'Pixel Harbor',
                    'publisher' => 'IndieForge',
                    'genres' => ['Simulation', 'Indie'],
                    'categories' => ['Single-player', 'Casual', 'Controller Support'],
                    'platforms' => ['windows', 'mac'],
                    'price' => 39000,
                    'discount' => 10,
                    'release_date' => '2026-05-15',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'A cozy farming sim with endless charm, quiet mornings, and tiny mysteries.',
                    'description' => 'Restore a seaside village, grow rare crops, decorate your home, and befriend odd neighbors.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i3'.PHP_EOL.'Memory: 4 GB RAM',
                    'website' => 'https://playmart.local/pixel-haven',
                ],
                [
                    'title' => 'Shadow Protocol',
                    'developer' => 'Neon Byte Lab',
                    'publisher' => 'NovaArc Games',
                    'genres' => ['Action', 'Adventure'],
                    'categories' => ['Single-player', 'Story Rich', 'Atmospheric'],
                    'platforms' => ['windows'],
                    'price' => 89000,
                    'discount' => 20,
                    'release_date' => '2026-01-30',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1535223289827-42f1e9919769?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'A stealth action thriller about a city that watches every move you make.',
                    'description' => 'Hack security grids, move through shadows, and expose a corporate conspiracy.',
                    'minimum_requirements' => 'OS: Windows 10'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM',
                    'website' => 'https://playmart.local/shadow-protocol',
                ],
                [
                    'title' => 'Starfall Odyssey',
                    'developer' => 'Blue Orbit Studio',
                    'publisher' => 'PlayMart Publishing',
                    'genres' => ['Adventure', 'Sci-Fi', 'Indie'],
                    'categories' => ['Single-player', 'Open World', 'Early Access'],
                    'platforms' => ['windows', 'linux'],
                    'price' => 109000,
                    'discount' => 20,
                    'release_date' => '2026-05-18',
                    'thumbnail_url' => 'https://images.unsplash.com/photo-1462331940025-496dfbfc7564?q=80&w=1400&auto=format&fit=crop',
                    'short_description' => 'Chart a lonely route through collapsed star systems and impossible signals.',
                    'description' => 'Explore derelict stations, decode alien beacons, and decide what humanity should become next.',
                    'minimum_requirements' => 'OS: Windows 10 64-bit'.PHP_EOL.'Processor: Intel i5'.PHP_EOL.'Memory: 8 GB RAM',
                    'website' => 'https://playmart.local/starfall-odyssey',
                ],
            ];

            foreach ($games as $index => $gameData) {
                $game = $this->seedGame($gameData, $developers, $publishers);
                $game->genres()->sync($this->idsFor($genres, $gameData['genres']));
                $game->categories()->sync($this->idsFor($categories, $gameData['categories']));
                $game->platforms()->sync($this->idsFor($platforms, $gameData['platforms']));
                $this->seedMedia($game, $gameData, $index);
                $this->seedReviews($game, $reviewers, $index);
            }
        });
    }

    private function seedNamedModels(string $modelClass, array $names): array
    {
        $models = [];

        foreach ($names as $name) {
            $model = $modelClass::withTrashed()->firstOrNew(['name' => $name]);
            $model->name = $name;
            $model->deleted_at = null;
            $model->save();

            $models[$name] = $model->getKey();
        }

        return $models;
    }

    private function seedUsers(): array
    {
        $users = [
            [
                'name' => 'Bima Player',
                'email' => 'bima@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Sari Quest',
                'email' => 'sari@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Raka Drift',
                'email' => 'raka@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Dina Arcade',
                'email' => 'dina@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Nova Hunter',
                'email' => 'nova@example.com',
                'password' => 'password',
            ],
            [
                'name' => 'Arga Console',
                'email' => 'arga@example.com',
                'password' => 'password',
            ],
        ];

        $result = [];

        foreach ($users as $userData) {
            $user = User::withTrashed()->firstOrNew(['email' => $userData['email']]);
            $user->fill([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'is_admin' => false,
                'email_verified_at' => now(),
            ]);
            $user->deleted_at = null;
            $user->save();

            $result[] = $user;
        }

        return $result;
    }

    private function seedGame(array $data, array $developers, array $publishers): Game
    {
        $game = Game::withTrashed()->firstOrNew(['title' => $data['title']]);
        $game->fill([
            'title' => $data['title'],
            'description' => $data['description'],
            'price' => $data['price'],
            'release_date' => $data['release_date'],
            'thumbnail_url' => $data['thumbnail_url'],
            'developer_id' => $developers[$data['developer']],
            'publisher_id' => $publishers[$data['publisher']],
        ]);
        $game->deleted_at = null;
        $game->save();

        $detail = GameDetail::withTrashed()->firstOrNew(['game_id' => $game->game_id]);
        $detail->fill([
            'discount' => $data['discount'],
            'short_description' => $data['short_description'],
            'header_image' => $data['thumbnail_url'],
            'website' => $data['website'],
            'minimum_requirements' => $data['minimum_requirements'],
        ]);
        $detail->deleted_at = null;
        $detail->save();

        return $game;
    }

    private function seedMedia(Game $game, array $data, int $index): void
    {
        GameScreenshot::withTrashed()->where('game_id', $game->game_id)->forceDelete();
        GameTrailer::withTrashed()->where('game_id', $game->game_id)->forceDelete();

        $screenshots = [
            $data['thumbnail_url'],
            'https://images.unsplash.com/photo-1511512578047-dfb367046420?q=80&w=1400&auto=format&fit=crop',
            'https://images.unsplash.com/photo-1493711662062-fa541adb3fc8?q=80&w=1400&auto=format&fit=crop',
        ];

        foreach ($screenshots as $order => $url) {
            $game->screenshots()->create([
                'url' => $url,
                'order' => $order,
            ]);
        }

        $game->trailers()->create([
            'title' => $data['title'].' Trailer',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => $index,
        ]);
    }

    private function seedReviews(Game $game, array $reviewers, int $index): void
    {
        $purchaseTargets = [6, 5, 4, 3, 3, 2, 2, 1];
        $buyerCount = min(count($reviewers), $purchaseTargets[$index] ?? 2);
        $buyers = array_slice($reviewers, 0, $buyerCount);
        $buyerIds = collect($buyers)->pluck('id')->all();

        $this->syncDemoPurchases($game, $buyers);

        $reviewTexts = [
            ['is_recommended' => true, 'body' => 'Visualnya kuat, gameplay-nya enak, dan worth it buat dicoba.'],
            ['is_recommended' => true, 'body' => 'Kontennya terasa lengkap. Cocok buat main santai maupun serius.'],
            ['is_recommended' => false, 'body' => 'Idenya bagus, tapi masih butuh polishing di beberapa bagian.'],
        ];

        foreach ($reviewers as $offset => $user) {
            if ($offset >= 3) {
                continue;
            }

            if (! in_array($user->id, $buyerIds, true)) {
                continue;
            }

            if (($index + $offset) % 3 === 2) {
                continue;
            }

            $review = $reviewTexts[($index + $offset) % count($reviewTexts)];

            GameReview::updateOrCreate(
                [
                    'game_id' => $game->game_id,
                    'user_id' => $user->id,
                ],
                $review
            );
        }
    }

    private function syncDemoPurchases(Game $game, array $buyers): void
    {
        $buyerIds = collect($buyers)->pluck('id');

        Payment::query()
            ->where('payment_code', 'like', 'DEMO-%-'.$game->game_id)
            ->when($buyerIds->isNotEmpty(), function ($query) use ($buyerIds) {
                $query->whereNotIn('user_id', $buyerIds->all());
            })
            ->get()
            ->each(function (Payment $payment) use ($game) {
                $payment->items()->where('game_id', $game->game_id)->delete();

                if (! $payment->items()->exists()) {
                    $payment->delete();
                }
            });

        foreach ($buyers as $user) {
            $this->seedPaidPayment($user, $game);
        }
    }

    private function seedPaidPayment(User $user, Game $game): void
    {
        $discount = $game->discount_percent;
        $subtotal = (float) $game->price;
        $lineTotal = $game->final_price;

        $payment = Payment::updateOrCreate(
            ['payment_code' => 'DEMO-'.$user->id.'-'.$game->game_id],
            [
                'user_id' => $user->id,
                'method' => 'qris',
                'status' => Payment::STATUS_PAID,
                'paid_at' => now()->subDays($game->game_id % 12),
            ]
        );

        $payment->items()->updateOrCreate(
            ['game_id' => $game->game_id],
            [
                'title' => $game->title,
                'unit_price' => $subtotal,
                'discount_percent' => $discount,
                'quantity' => 1,
            ]
        );
    }

    private function idsFor(array $lookup, array $names): array
    {
        return collect($names)
            ->map(fn (string $name) => $lookup[$name] ?? null)
            ->filter()
            ->values()
            ->all();
    }
}
