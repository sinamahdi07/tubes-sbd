<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 3000 users baru
        User::factory()->count(3000)->create();

        $this->command->info('✅ 3000 users berhasil dibuat!');
    }
}
