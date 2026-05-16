<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_trailers', function (Blueprint $table) {
            $table->id('trailer_id');
            $table->unsignedBigInteger('game_id');
            $table->string('title', 200)->nullable();
            $table->string('url', 500);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('game_id')
                ->references('game_id')
                ->on('games')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_trailers');
    }
};
