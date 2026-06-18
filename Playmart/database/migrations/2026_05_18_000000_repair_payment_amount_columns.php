<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'payment_code')) {
                $table->string('payment_code', 40)->nullable()->unique()->after('user_id');
            }

            if (! Schema::hasColumn('payments', 'method')) {
                $table->string('method', 40)->default('qris')->after('payment_code');
            }

            if (! Schema::hasColumn('payments', 'status')) {
                $table->string('status', 20)->default('pending')->after('method');
            }

            if (! Schema::hasColumn('payments', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('status');
            }

            if (! Schema::hasColumn('payments', 'discount_total')) {
                $table->decimal('discount_total', 12, 2)->default(0)->after('subtotal');
            }

            if (! Schema::hasColumn('payments', 'total')) {
                $table->decimal('total', 12, 2)->default(0)->after('discount_total');
            }

            if (! Schema::hasColumn('payments', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('total');
            }
        });

        if (Schema::hasTable('payment_items')) {
            Schema::table('payment_items', function (Blueprint $table) {
                if (! Schema::hasColumn('payment_items', 'title')) {
                    $table->string('title', 200)->default('Unknown Game');
                }

                if (! Schema::hasColumn('payment_items', 'price')) {
                    $table->decimal('price', 12, 2)->default(0);
                }

                if (! Schema::hasColumn('payment_items', 'discount_percent')) {
                    $table->unsignedInteger('discount_percent')->default(0);
                }

                if (! Schema::hasColumn('payment_items', 'quantity')) {
                    $table->unsignedInteger('quantity')->default(1);
                }

                if (! Schema::hasColumn('payment_items', 'line_total')) {
                    $table->decimal('line_total', 12, 2)->default(0);
                }
            });

            if (Schema::hasColumn('payment_items', 'title') && Schema::hasTable('games')) {
                DB::statement("UPDATE payment_items SET title = COALESCE((SELECT games.title FROM games WHERE games.game_id = payment_items.game_id), title, 'Unknown Game') WHERE title = 'Unknown Game' OR title IS NULL OR title = ''");
            }

            if (Schema::hasColumn('payment_items', 'price') && Schema::hasColumn('payment_items', 'unit_price')) {
                DB::statement('UPDATE payment_items SET price = unit_price WHERE price = 0');
            }

            if (
                Schema::hasColumn('payment_items', 'line_total')
                && Schema::hasColumn('payment_items', 'price')
                && Schema::hasColumn('payment_items', 'quantity')
            ) {
                DB::statement('UPDATE payment_items SET line_total = price * quantity WHERE line_total = 0');
            }

            if (Schema::hasColumn('payments', 'subtotal')) {
                DB::statement('UPDATE payments SET subtotal = (SELECT COALESCE(SUM(price * quantity), 0) FROM payment_items WHERE payment_items.payment_id = payments.id) WHERE subtotal = 0');
            }

            if (Schema::hasColumn('payments', 'total')) {
                DB::statement('UPDATE payments SET total = (SELECT COALESCE(SUM(line_total), 0) FROM payment_items WHERE payment_items.payment_id = payments.id) WHERE total = 0');
            }
        }
    }

    public function down(): void
    {
        //
    }
};
