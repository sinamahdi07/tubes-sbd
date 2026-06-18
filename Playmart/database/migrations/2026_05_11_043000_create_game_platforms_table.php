<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_platforms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('platform_id');
            $table->timestamps();

            $table->foreign('game_id')
                ->references('game_id')
                ->on('games')
                ->onDelete('cascade');

            $table->foreign('platform_id')
                ->references('platform_id')
                ->on('platforms')
                ->onDelete('cascade');

            $table->unique(['game_id', 'platform_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_platforms');
    }
};
