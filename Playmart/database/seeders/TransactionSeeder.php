<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\GameReview;
use App\Models\Payment;
use App\Models\PaymentItem;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', 0)->get();
        $games = Game::whereIn('game_id', [1, 2, 8, 9, 11, 12, 13, 15, 16, 17, 20, 22, 23, 25, 26, 27, 28, 29, 30, 31, 66, 67, 73, 117, 118])->get();

        $methods = ['credit_card', 'bank_transfer', 'gopay', 'dana'];

        // Generate 15000 transactions
        for ($i = 0; $i < 15000; $i++) {
            $user = $users->random();
            $game = $games->random();

            // Cek user belum pernah beli game ini
            $exists = Payment::whereHas('items', function ($q) use ($game) {
                $q->where('game_id', $game->game_id);
            })->where('user_id', $user->id)->exists();

            if ($exists) {
                continue;
            }

            $payment = Payment::create([
                'user_id' => $user->id,
                'payment_code' => 'TXN-'.strtoupper(uniqid()),
                'method' => $methods[array_rand($methods)],
                'status' => 'paid',
                'paid_at' => now()->subDays(rand(0, 180)),
            ]);

            PaymentItem::create([
                'payment_id' => $payment->id,
                'game_id' => $game->game_id,
                'title' => $game->title,
                'unit_price' => $game->price,
                'discount_percent' => rand(0, 50) > 70 ? rand(0, 50) : 0,
                'quantity' => 1,
            ]);

            // 65% chance create review
            if (rand(1, 100) <= 65) {
                GameReview::create([
                    'game_id' => $game->game_id,
                    'user_id' => $user->id,
                    'is_recommended' => rand(1, 100) <= 85 ? 1 : 0,
                    'body' => $this->getReviewText(),
                ]);
            }

            if ($i % 1000 == 0) {
                $this->command->info("✅ {$i} transactions created...");
            }
        }

        $this->command->info('🎉 15000 transactions completed!');
    }

    private function getReviewText()
    {
        $positive = [
            'Game yang sangat bagus! Gameplay seru dan grafis memukau.',
            'Worth it banget, highly recommended!',
            'Gameplay smooth, cerita menarik.',
            'Free to play tapi kualitas premium.',
            'Replay value tinggi, gak bosen.',
        ];

        $negative = [
            'Agak buggy di beberapa bagian.',
            'Terlalu sulit untuk pemula.',
            'Server sering down.',
            'Microtransaction terlalu agresif.',
        ];

        return rand(1, 100) <= 85
            ? $positive[array_rand($positive)]
            : $negative[array_rand($negative)];
    }
}
