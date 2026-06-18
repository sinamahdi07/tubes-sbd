<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 3NF: Genre entity — stores genre name independently.
     *      Supports many-to-many with games via game_genres pivot.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {

            // genre_id
            $table->id('genre_id');

            // name — unique, e.g. "Action", "RPG", "Strategy"
            $table->string('name', 50)->unique();

            // timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
