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
        Schema::table('favorite_asteroids', function (Blueprint $table) {
            $table->string('asteroid_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favorite_asteroids', function (Blueprint $table) {
            $table->unsignedBigInteger('asteroid_id')->change();
        });
    }
};
