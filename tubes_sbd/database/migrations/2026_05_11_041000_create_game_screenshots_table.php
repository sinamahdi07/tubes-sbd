<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 3NF: Extracted from games table to avoid multi-value attribute violation (1NF).
     *      A game can have multiple screenshots; screenshot URL depends only on screenshot_id.
     */
    public function up(): void
    {
        Schema::create('game_screenshots', function (Blueprint $table) {

            // screenshot_id
            $table->id('screenshot_id');

            // game_id (FK → games)
            $table->unsignedBigInteger('game_id');

            // url — the screenshot image URL
            $table->string('url', 500);

            // order — display order of screenshot (0-indexed)
            $table->unsignedTinyInteger('order')->default(0);

            // timestamps
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('game_id')
                  ->references('game_id')
                  ->on('games')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_screenshots');
    }
};
