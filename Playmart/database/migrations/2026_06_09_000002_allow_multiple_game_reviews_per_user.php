<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_reviews', function (Blueprint $table) {
            $table->dropUnique(['game_id', 'user_id']);
        });
    }

    public function down(): void
    {
        //
    }
};
