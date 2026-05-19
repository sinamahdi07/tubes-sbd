<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('game_trailers')) {
            Schema::create('game_trailers', function (Blueprint $table) {
                $table->id('trailer_id');
                $table->unsignedBigInteger('game_id');
                $table->string('title', 200)->nullable();
                $table->string('url', 500);
                $table->unsignedInteger('order')->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('game_id')
                    ->references('game_id')
                    ->on('games')
                    ->cascadeOnDelete();
            });

            return;
        }

        Schema::table('game_trailers', function (Blueprint $table) {
            if (! Schema::hasColumn('game_trailers', 'title')) {
                $table->string('title', 200)->nullable()->after('game_id');
            }

            if (! Schema::hasColumn('game_trailers', 'url')) {
                $table->string('url', 500)->after('title');
            }

            if (! Schema::hasColumn('game_trailers', 'order')) {
                $table->unsignedInteger('order')->default(0)->after('url');
            }

            if (! Schema::hasColumn('game_trailers', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        // Intentionally keep this table because older migrations also own it.
    }
};
