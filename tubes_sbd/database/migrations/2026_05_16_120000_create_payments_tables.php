<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('payment_code', 40)->unique();
            $table->string('method', 40);
            $table->string('status', 20)->default('pending');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });

        Schema::create('payment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')
                ->constrained('payments')
                ->cascadeOnDelete();
            $table->unsignedBigInteger('game_id')->nullable();
            $table->string('title', 200);
            $table->decimal('price', 12, 2)->default(0);
            $table->unsignedInteger('discount_percent')->default(0);
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('game_id')
                ->references('game_id')
                ->on('games')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_items');
        Schema::dropIfExists('payments');
    }
};
