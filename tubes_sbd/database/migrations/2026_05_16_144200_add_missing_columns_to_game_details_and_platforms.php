<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan kolom yang belum ada:
     * - game_details: short_description, header_image
     * - platforms: icon
     */
    public function up(): void
    {
        // Tambah kolom di game_details
        Schema::table('game_details', function (Blueprint $table) {
            if (!Schema::hasColumn('game_details', 'short_description')) {
                $table->text('short_description')->nullable()->after('discount');
            }
            if (!Schema::hasColumn('game_details', 'header_image')) {
                $table->string('header_image', 1000)->nullable()->after('short_description');
            }
        });

        // Tambah kolom icon di platforms
        Schema::table('platforms', function (Blueprint $table) {
            if (!Schema::hasColumn('platforms', 'icon')) {
                $table->text('icon')->nullable()->after('slug');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_details', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'header_image']);
        });

        Schema::table('platforms', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
