<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('carts', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')
              ->constrained()
              ->onDelete('cascade');

        $table->foreignId('game_id')
              ->constrained('games', 'game_id')
              ->onDelete('cascade');

        $table->integer('quantity')->default(1);

        $table->timestamps();

        $table->unique(['user_id', 'game_id'], 'carts_user_game_unique');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
