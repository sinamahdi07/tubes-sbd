<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DemoStoreSeeder::class,
            UserSeeder::class,
            TransactionSeeder::class,
            FriendshipSeeder::class,
        ]);

        if (file_exists(database_path('seeders/data/games.xlsx'))) {
            $this->call(GamesSeeder::class);
        }
    }
}
