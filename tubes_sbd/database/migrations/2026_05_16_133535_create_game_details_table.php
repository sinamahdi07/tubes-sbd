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
        Schema::create('game_details', function (Blueprint $table) {
            $table->id('game_detail_id');
            $table->unsignedBigInteger('game_id')->unique();
            $table->unsignedBigInteger('appid')->nullable();
            $table->integer('discount')->default(0);
            $table->text('short_description')->nullable();
            $table->string('header_image', 1000)->nullable();
            $table->string('website', 1000)->nullable();
            $table->longText('minimum_requirements')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('game_details');
    }
};
