<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 3NF: Pivot table — resolves many-to-many between games and genres.
     *      A game can have multiple genres; a genre can belong to many games.
     *      No transitive dependencies here; each row is uniquely identified by (game_id, genre_id).
     */
    public function up(): void
    {
        Schema::create('game_genres', function (Blueprint $table) {

            // surrogate PK
            $table->id();

            // game_id (FK → games)
            $table->unsignedBigInteger('game_id');

            // genre_id (FK → genres)
            $table->unsignedBigInteger('genre_id');

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('game_id')
                ->references('game_id')
                ->on('games')
                ->onDelete('cascade');

            $table->foreign('genre_id')
                ->references('genre_id')
                ->on('genres')
                ->onDelete('cascade');

            // Prevent duplicate genre per game
            $table->unique(['game_id', 'genre_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_genres');
    }
};
