<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->seedStoreData();

        $response = $this->get('/');

        $response->assertStatus(200);
    }

    private function seedStoreData(): void
    {
        $now = now();

        $developerId = DB::table('developers')->insertGetId([
            'name' => 'Test Developer',
            'created_at' => $now,
            'updated_at' => $now,
        ], 'developer_id');

        $publisherId = DB::table('publishers')->insertGetId([
            'name' => 'Test Publisher',
            'created_at' => $now,
            'updated_at' => $now,
        ], 'publisher_id');

        $genreId = DB::table('genres')->insertGetId([
            'name' => 'Action',
            'created_at' => $now,
            'updated_at' => $now,
        ], 'genre_id');

        $gameId = DB::table('games')->insertGetId([
            'title' => 'Test Game',
            'description' => 'Game untuk kebutuhan test homepage.',
            'price' => 100000,
            'release_date' => '2026-05-16',
            'thumbnail_url' => 'https://example.com/game.jpg',
            'developer_id' => $developerId,
            'publisher_id' => $publisherId,
            'created_at' => $now,
            'updated_at' => $now,
        ], 'game_id');

        DB::table('game_genres')->insert([
            'game_id' => $gameId,
            'genre_id' => $genreId,
        ]);
    }
}
