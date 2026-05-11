<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name'     => 'Administrator',
                'email'    => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
            ]
        );

        $this->command->info('✅ Akun admin berhasil dibuat/diperbarui!');
        $this->command->info('   Email    : admin@admin.com');
        $this->command->info('   Password : admin123');
        $this->command->info('   URL      : http://localhost:8000/admin');
    }
}
