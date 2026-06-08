<?php

namespace Database\Seeders;

use App\Imports\GamesImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class GamesSeeder extends Seeder
{
    /**
     * Seed games dari file Excel.
     *
     * Letakkan file Excel di: database/seeders/data/games.xlsx
     * (atau .csv / .xls)
     *
     * Format kolom Excel (baris pertama = header):
     *   title | developer | publisher | genre | price | release_date | thumbnail | screenshot | description
     *
     * Catatan:
     *   - genre      : pisahkan dengan koma jika lebih dari satu (contoh: "Action, RPG")
     *   - screenshot : pisahkan URL dengan koma jika lebih dari satu
     */
    public function run(): void
    {
        $filePath = database_path('seeders/data/games.xlsx');

        if (! file_exists($filePath)) {
            $this->command->warn('⚠️  File tidak ditemukan: '.$filePath);
            $this->command->warn('   Letakkan file Excel kamu di: database/seeders/data/games.xlsx');

            return;
        }

        $this->command->info('📥  Mengimport data games dari Excel...');

        $import = new GamesImport;
        Excel::import($import, $filePath);

        $this->command->info("✅  Berhasil import: {$import->imported} game.");

        if (! empty($import->failures)) {
            $this->command->warn('⚠️  Baris yang gagal diimport:');
            foreach ($import->failures as $failure) {
                $this->command->warn("   Baris {$failure['row']} [{$failure['title']}]: {$failure['error']}");
            }
        }
    }
}
