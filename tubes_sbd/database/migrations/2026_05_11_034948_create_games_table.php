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
        Schema::create('games', function (Blueprint $table) {

            // game_id
            $table->id('game_id');

            // title
            $table->string('title', 200);

            // description
            $table->text('description')->nullable();

            // price
            $table->decimal('price', 10, 2);

            // release_date
            $table->date('release_date')->nullable();

            // publisher_id
            $table->unsignedBigInteger('publisher_id');

            // developer_id
            $table->unsignedBigInteger('developer_id');

            // stock
            $table->integer('stock')->default(999);

            // timestamps
            $table->timestamps();

            /*
            |--------------------------------------------------------------------------
            | FOREIGN KEY
            |--------------------------------------------------------------------------
            */

            $table->foreign('publisher_id')
                  ->references('publisher_id')
                  ->on('publishers')
                  ->onDelete('cascade');

            $table->foreign('developer_id')
                  ->references('developer_id')
                  ->on('developers')
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