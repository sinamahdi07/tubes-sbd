<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('game_details')
            && Schema::hasColumn('game_details', 'id')
            && ! Schema::hasColumn('game_details', 'game_detail_id')
        ) {
            Schema::table('game_details', function ($table) {
                $table->renameColumn('id', 'game_detail_id');
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('game_details')
            && Schema::hasColumn('game_details', 'game_detail_id')
            && ! Schema::hasColumn('game_details', 'id')
        ) {
            Schema::table('game_details', function ($table) {
                $table->renameColumn('game_detail_id', 'id');
            });
        }
    }
};
