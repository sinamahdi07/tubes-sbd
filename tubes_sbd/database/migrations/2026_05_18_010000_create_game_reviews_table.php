<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_recommended')->default(true);
            $table->text('body');
            $table->timestamps();

            $table->foreign('game_id')
                ->references('game_id')
                ->on('games')
                ->cascadeOnDelete();

            $table->unique(['game_id', 'user_id']);
            $table->index(['game_id', 'is_recommended']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_reviews');
    }
};
