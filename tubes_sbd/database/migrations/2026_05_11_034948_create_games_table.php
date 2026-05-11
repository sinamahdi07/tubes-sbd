<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * NOTE: Requires developers, publishers tables to exist first.
     * Rename this file timestamp to run AFTER those migrations if needed.
     */
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {

            // game_id
            $table->id('game_id');

            // title
            $table->string('title', 200);

            // description
            $table->text('description')->nullable();

            // price
            $table->decimal('price', 10, 2)->default(0);

            // release_date
            $table->date('release_date')->nullable();

            // thumbnail_url
            $table->string('thumbnail_url', 500)->nullable();

            // developer_id (FK → developers)
            $table->unsignedBigInteger('developer_id');

            // publisher_id (FK → publishers)
            $table->unsignedBigInteger('publisher_id');

            // timestamps
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('developer_id')
                  ->references('developer_id')
                  ->on('developers')
                  ->onDelete('cascade');

            $table->foreign('publisher_id')
                  ->references('publisher_id')
                  ->on('publishers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};