<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Developer;
use App\Models\Game;
use App\Models\GameReview;
use App\Models\Genre;
use App\Models\Platform;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_can_be_rendered(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Dashboard');
    }

    public function test_admin_dashboard_orders_recent_games_by_release_date(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $developer = Developer::create(['name' => 'Dashboard Developer']);
        $publisher = Publisher::create(['name' => 'Dashboard Publisher']);

        Game::create([
            'title' => 'Old Release',
            'description' => 'Old release game.',
            'price' => 10000,
            'release_date' => '2024-01-01',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);

        Game::create([
            'title' => 'New Release',
            'description' => 'New release game.',
            'price' => 10000,
            'release_date' => '2026-01-01',
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSeeInOrder(['New Release', 'Old Release']);
    }

    public function test_admin_games_index_can_be_rendered(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.games.index'))
            ->assertOk()
            ->assertSee('Manajemen Game');
    }

    public function test_admin_categories_index_can_be_rendered(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Manajemen Kategori');
    }

    public function test_admin_reviews_index_can_be_rendered_and_review_can_be_deleted(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $reviewer = User::factory()->create(['name' => 'Review Admin Target']);
        $developer = Developer::create(['name' => 'Admin Review Developer']);
        $publisher = Publisher::create(['name' => 'Admin Review Publisher']);
        $game = Game::create([
            'title' => 'Admin Review Game',
            'description' => 'Game untuk admin review.',
            'price' => 50000,
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);
        $review = GameReview::create([
            'game_id' => $game->game_id,
            'user_id' => $reviewer->id,
            'is_recommended' => true,
            'body' => 'Review ini perlu bisa dilihat dan dihapus admin.',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.reviews.index'))
            ->assertOk()
            ->assertSee('Manajemen Review')
            ->assertSee('Admin Review Game')
            ->assertSee('Review Admin Target')
            ->assertSee('Like');

        $this->actingAs($admin)
            ->delete(route('admin.reviews.destroy', $review))
            ->assertRedirect();

        $this->assertDatabaseMissing('game_reviews', ['id' => $review->id]);
    }

    public function test_admin_game_edit_can_be_rendered(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $developer = Developer::create(['name' => 'Edit Test Developer']);
        $publisher = Publisher::create(['name' => 'Edit Test Publisher']);
        $game = Game::create([
            'title' => 'Editable Game',
            'description' => 'Game untuk test edit.',
            'price' => 50000,
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.games.edit', $game))
            ->assertOk()
            ->assertSee('Edit Game: Editable Game');
    }

    public function test_admin_can_update_game(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $developer = Developer::create(['name' => 'Update Test Developer']);
        $publisher = Publisher::create(['name' => 'Update Test Publisher']);
        $game = Game::create([
            'title' => 'Before Update Game',
            'description' => 'Game sebelum update.',
            'price' => 50000,
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.games.update', $game), [
                'title' => 'After Update Game',
                'description' => 'Game sesudah update.',
                'price' => 75000,
                'release_date' => '2026-05-19',
                'developer_id' => $developer->developer_id,
                'publisher_id' => $publisher->publisher_id,
                'discount' => 10,
                'short_description' => 'Deskripsi pendek setelah update.',
            ])
            ->assertRedirect(route('admin.games.index'));

        $this->assertDatabaseHas('games', [
            'game_id' => $game->game_id,
            'title' => 'After Update Game',
            'price' => 75000,
        ]);

        $this->assertDatabaseHas('game_details', [
            'game_id' => $game->game_id,
            'discount' => 10,
            'short_description' => 'Deskripsi pendek setelah update.',
        ]);
    }

    public function test_admin_can_soft_delete_and_restore_user(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $target = User::factory()->create(['name' => 'Soft Delete Target']);

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertRedirect();

        $this->assertSoftDeleted('users', ['id' => $target->id]);

        $this->actingAs($admin)
            ->get(route('admin.users.index', ['trash' => 1]))
            ->assertOk()
            ->assertSee('Soft Delete Target');

        $this->actingAs($admin)
            ->post(route('admin.users.restore', $target->id))
            ->assertRedirect(route('admin.users.index', ['trash' => 1]));

        $this->assertNotSoftDeleted('users', ['id' => $target->id]);
    }

    public function test_admin_can_permanently_delete_soft_deleted_admin_records(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $targetUser = User::factory()->create(['name' => 'Permanent Delete Target']);
        $developer = Developer::create(['name' => 'Permanent Delete Developer']);
        $publisher = Publisher::create(['name' => 'Permanent Delete Publisher']);
        $game = Game::create([
            'title' => 'Permanent Delete Game',
            'description' => 'Game untuk test hapus permanen.',
            'price' => 50000,
            'developer_id' => $developer->developer_id,
            'publisher_id' => $publisher->publisher_id,
        ]);
        $genre = Genre::create(['name' => 'Permanent Delete Genre']);
        $category = Category::create(['name' => 'Permanent Delete Category']);
        $platform = Platform::create([
            'name' => 'Permanent Delete Platform',
            'slug' => 'permanent-delete-platform',
        ]);

        $targets = [
            [$targetUser, 'admin.users.force-destroy', 'users', 'id', $targetUser->id],
            [$game, 'admin.games.force-destroy', 'games', 'game_id', $game->game_id],
            [$developer, 'admin.developers.force-destroy', 'developers', 'developer_id', $developer->developer_id],
            [$publisher, 'admin.publishers.force-destroy', 'publishers', 'publisher_id', $publisher->publisher_id],
            [$genre, 'admin.genres.force-destroy', 'genres', 'genre_id', $genre->genre_id],
            [$category, 'admin.categories.force-destroy', 'categories', 'category_id', $category->category_id],
            [$platform, 'admin.platforms.force-destroy', 'platforms', 'platform_id', $platform->platform_id],
        ];

        foreach ($targets as [$model, $routeName, $table, $key, $id]) {
            $model->delete();

            $this->assertSoftDeleted($table, [$key => $id]);

            $this->actingAs($admin)
                ->delete(route($routeName, $id))
                ->assertRedirect();

            $this->assertDatabaseMissing($table, [$key => $id]);
        }
    }
}
