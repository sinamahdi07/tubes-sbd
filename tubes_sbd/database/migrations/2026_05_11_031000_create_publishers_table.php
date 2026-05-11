<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 3NF: Publisher entity — stores publisher name independently.
     */
    public function up(): void
    {
        Schema::create('publishers', function (Blueprint $table) {

            // publisher_id
            $table->id('publisher_id');

            // name — unique, no transitive dependency
            $table->string('name', 100)->unique();

            // timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('publishers');
    }
};
