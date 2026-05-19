<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('game_trailers')) {
            Schema::create('game_trailers', function (Blueprint $table) {
                $table->id('trailer_id');
                $table->unsignedBigInteger('game_id');
                $table->string('title', 200)->nullable();
                $table->string('url', 500);
                $table->unsignedTinyInteger('order')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('game_id')
                    ->references('game_id')
                    ->on('games')
                    ->cascadeOnDelete();

                $table->unique(['game_id', 'order']);
            });

            return;
        }

        if (!Schema::hasColumn('game_trailers', 'deleted_at')) {
            Schema::table('game_trailers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('game_trailers') && Schema::hasColumn('game_trailers', 'deleted_at')) {
            Schema::table('game_trailers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
