<?php

namespace App\Imports;

use App\Models\Developer;
use App\Models\Game;
use App\Models\GameScreenshot;
use App\Models\Genre;
use App\Models\Publisher;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class GamesImport implements ToCollection, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    /**
     * Daftar error baris yang gagal diimport.
     * @var array
     */
    public array $failures = [];

    /**
     * Jumlah baris yang berhasil diimport.
     * @var int
     */
    public int $imported = 0;

    /**
     * Proses seluruh koleksi baris dari Excel.
     *
     * Kolom Excel yang diharapkan (header row):
     *   title | developer | publisher | genre | price | release_date | thumbnail | screenshot | description
     *
     * - genre      : bisa satu atau lebih, pisahkan dengan koma  (contoh: "Action, RPG")
     * - screenshot : bisa satu atau lebih URL, pisahkan dengan koma
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            // Lewati baris kosong
            if (empty($row['title'])) {
                continue;
            }

            try {

                // ── 1. Developer (firstOrCreate → 3NF) ──────────────────────
                $developer = Developer::firstOrCreate(
                    ['name' => trim($row['developer'] ?? 'Unknown')]
                );

                // ── 2. Publisher (firstOrCreate → 3NF) ──────────────────────
                $publisher = Publisher::firstOrCreate(
                    ['name' => trim($row['publisher'] ?? 'Unknown')]
                );

                // ── 3. Game ──────────────────────────────────────────────────
                $releaseDate = $this->parseDate($row['release_date'] ?? null);

                $game = Game::create([
                    'title'         => trim($row['title']),
                    'description'   => $row['description'] ?? null,
                    'price'         => is_numeric($row['price'] ?? null) ? (float) $row['price'] : 0,
                    'release_date'  => $releaseDate,
                    'thumbnail_url' => $row['thumbnail'] ?? null,
                    'developer_id'  => $developer->developer_id,
                    'publisher_id'  => $publisher->publisher_id,
                ]);

                // ── 4. Genres (many-to-many → 3NF) ──────────────────────────
                if (!empty($row['genre'])) {
                    $genreNames = array_filter(array_map('trim', explode(',', $row['genre'])));
                    foreach ($genreNames as $genreName) {
                        $genre = Genre::firstOrCreate(['name' => $genreName]);
                        // attach hanya jika belum ada (avoid duplicate)
                        $game->genres()->syncWithoutDetaching([$genre->genre_id]);
                    }
                }

                // ── 5. Screenshots (one-to-many → pisah tabel) ──────────────
                if (!empty($row['screenshot'])) {
                    $urls = array_filter(array_map('trim', explode(',', $row['screenshot'])));
                    foreach ($urls as $order => $url) {
                        GameScreenshot::create([
                            'game_id' => $game->game_id,
                            'url'     => $url,
                            'order'   => $order,
                        ]);
                    }
                }

                $this->imported++;

            } catch (\Throwable $e) {
                $this->failures[] = [
                    'row'   => $index + 2, // +2 karena row 1 = header
                    'title' => $row['title'] ?? '-',
                    'error' => $e->getMessage(),
                ];
            }
        }
    }

    /**
     * Parse tanggal dari berbagai format Excel.
     * Excel sering menyimpan tanggal sebagai integer serial atau string.
     */
    private function parseDate(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Excel serial date (integer)
        if (is_numeric($value) && (int) $value > 20000 && (int) $value < 100000) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                    ->format('Y-m-d');
            } catch (\Throwable) {
                // fallthrough
            }
        }

        // String date
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }
}
