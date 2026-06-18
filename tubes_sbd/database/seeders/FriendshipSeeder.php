<?php

namespace Database\Seeders;

use App\Models\Friendship;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('is_admin', 0)->get();

        if ($users->count() < 2) {
            $this->command->warn('Skipping friendships: at least two non-admin users are required.');

            return;
        }

        $count = 0;

        // Super connectors (10 users with 200-400 friends)
        $superConnectors = $users->random(min(10, $users->count()));
        foreach ($superConnectors as $user) {
            $friends = $users->where('id', '!=', $user->id)->random(min(300, $users->count() - 1));
            foreach ($friends as $friend) {
                $this->createFriendship($user->id, $friend->id);
                $count++;
            }
        }

        // Active users (100 users with 50-100 friends)
        $activeUsers = $users->random(min(100, $users->count()));
        foreach ($activeUsers as $user) {
            $friends = $users->where('id', '!=', $user->id)->random(min(80, $users->count() - 1));
            foreach ($friends as $friend) {
                $this->createFriendship($user->id, $friend->id);
                $count++;
            }
        }

        // Casual users
        foreach ($users as $user) {
            $existingFriends = Friendship::where('requester_id', $user->id)
                ->orWhere('addressee_id', $user->id)
                ->count();

            if ($existingFriends < 30) {
                $friendsCount = rand(5, 30);
                $friends = $users->where('id', '!=', $user->id)
                    ->random(min($friendsCount, $users->count() - 1));

                foreach ($friends as $friend) {
                    $this->createFriendship($user->id, $friend->id);
                    $count++;
                }
            }
        }

        $this->command->info("🤝 {$count} friendships created!");
    }

    private function createFriendship($requesterId, $addresseeId)
    {
        // Check if already exists
        $exists = Friendship::where(function ($q) use ($requesterId, $addresseeId) {
            $q->where('requester_id', $requesterId)
                ->where('addressee_id', $addresseeId);
        })->orWhere(function ($q) use ($requesterId, $addresseeId) {
            $q->where('requester_id', $addresseeId)
                ->where('addressee_id', $requesterId);
        })->exists();

        if (! $exists && $requesterId != $addresseeId) {
            Friendship::create([
                'requester_id' => $requesterId,
                'addressee_id' => $addresseeId,
                'status' => rand(1, 100) <= 85 ? 'accepted' : 'pending',
            ]);
        }
    }
}
