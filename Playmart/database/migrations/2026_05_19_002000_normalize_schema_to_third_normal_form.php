<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeCarts();
        $this->normalizeScreenshots();
        $this->normalizePaymentItems();
        $this->normalizePayments();
    }

    public function down(): void
    {
        if (Schema::hasTable('payments')) {
            Schema::table('payments', function (Blueprint $table) {
                if (! Schema::hasColumn('payments', 'subtotal')) {
                    $table->decimal('subtotal', 12, 2)->default(0)->after('status');
                }

                if (! Schema::hasColumn('payments', 'discount_total')) {
                    $table->decimal('discount_total', 12, 2)->default(0)->after('subtotal');
                }

                if (! Schema::hasColumn('payments', 'total')) {
                    $table->decimal('total', 12, 2)->default(0)->after('discount_total');
                }
            });
        }

        if (Schema::hasTable('payment_items')) {
            Schema::table('payment_items', function (Blueprint $table) {
                if (! Schema::hasColumn('payment_items', 'price')) {
                    $table->decimal('price', 12, 2)->default(0)->after('title');
                }

                if (! Schema::hasColumn('payment_items', 'line_total')) {
                    $table->decimal('line_total', 12, 2)->default(0)->after('quantity');
                }
            });

            $priceColumn = Schema::hasColumn('payment_items', 'unit_price') ? 'unit_price' : 'price';

            DB::table('payment_items')->update([
                'price' => DB::raw($priceColumn),
                'line_total' => DB::raw($this->lineTotalExpression($priceColumn)),
            ]);

            DB::statement('
                UPDATE payments
                SET subtotal = COALESCE((
                    SELECT SUM(payment_items.price * payment_items.quantity)
                    FROM payment_items
                    WHERE payment_items.payment_id = payments.id
                ), 0),
                total = COALESCE((
                    SELECT SUM(payment_items.line_total)
                    FROM payment_items
                    WHERE payment_items.payment_id = payments.id
                ), 0)
            ');

            DB::statement('
                UPDATE payments
                SET discount_total = GREATEST(subtotal - total, 0)
            ');
        }
    }

    private function normalizeCarts(): void
    {
        if (! Schema::hasTable('carts')) {
            return;
        }

        DB::table('carts')
            ->select('user_id', 'game_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('user_id', 'game_id')
            ->havingRaw('COUNT(*) > 1')
            ->get()
            ->each(function ($duplicate) {
                DB::table('carts')
                    ->where('user_id', $duplicate->user_id)
                    ->where('game_id', $duplicate->game_id)
                    ->where('id', '!=', $duplicate->keep_id)
                    ->delete();
            });

        if (Schema::hasColumn('carts', 'quantity')) {
            DB::table('carts')->update(['quantity' => 1]);
        }

        if (! $this->hasIndex('carts', 'carts_user_game_unique')) {
            Schema::table('carts', function (Blueprint $table) {
                $table->unique(['user_id', 'game_id'], 'carts_user_game_unique');
            });
        }
    }

    private function normalizeScreenshots(): void
    {
        if (! Schema::hasTable('screenshots')) {
            return;
        }

        if (Schema::hasTable('game_screenshots')) {
            DB::table('screenshots')
                ->orderBy('screenshot_id')
                ->get()
                ->each(function ($screenshot) {
                    DB::table('game_screenshots')->insertOrIgnore([
                        'game_id' => $screenshot->game_id,
                        'url' => $screenshot->image_url,
                        'order' => 0,
                        'created_at' => $screenshot->created_at ?? now(),
                        'updated_at' => $screenshot->updated_at ?? now(),
                    ]);
                });
        }

        Schema::dropIfExists('screenshots');
    }

    private function normalizePaymentItems(): void
    {
        if (! Schema::hasTable('payment_items')) {
            return;
        }

        if (! Schema::hasColumn('payment_items', 'unit_price')) {
            Schema::table('payment_items', function (Blueprint $table) {
                $table->decimal('unit_price', 12, 2)->default(0)->after('title');
            });
        }

        if (Schema::hasColumn('payment_items', 'price')) {
            DB::statement('
                UPDATE payment_items
                SET unit_price = price
                WHERE price IS NOT NULL
            ');
        }

        Schema::table('payment_items', function (Blueprint $table) {
            if (Schema::hasColumn('payment_items', 'price')) {
                $table->dropColumn('price');
            }

            if (Schema::hasColumn('payment_items', 'line_total')) {
                $table->dropColumn('line_total');
            }
        });
    }

    private function normalizePayments(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            foreach (['subtotal', 'discount_total', 'total'] as $column) {
                if (Schema::hasColumn('payments', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    private function lineTotalExpression(string $priceColumn): string
    {
        return "GREATEST({$priceColumn} * quantity * (1 - (discount_percent / 100)), 0)";
    }

    private function hasIndex(string $table, string $index): bool
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA index_list('{$table}')"))
                ->contains(fn ($row) => ($row->name ?? null) === $index);
        }

        return collect(DB::select('SHOW INDEX FROM `'.$table.'` WHERE Key_name = ?', [$index]))->isNotEmpty();
    }
};
