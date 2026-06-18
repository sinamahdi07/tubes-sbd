<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 3NF: Developer entity — stores developer name independently.
     */
    public function up(): void
    {
        Schema::create('developers', function (Blueprint $table) {

            // developer_id
            $table->id('developer_id');

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
        Schema::dropIfExists('developers');
    }
};
