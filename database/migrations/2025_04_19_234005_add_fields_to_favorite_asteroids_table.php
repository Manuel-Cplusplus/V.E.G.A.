<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('favorite_asteroids', function (Blueprint $table) {
            $table->date('cad')->nullable();
            $table->boolean('isSentry')->default(false);
            $table->decimal('impact_probability', 20, 15)->nullable();
            $table->date('impact_date')->nullable();
            $table->unsignedTinyInteger('torino_scale')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('favorite_asteroids', function (Blueprint $table) {
            $table->dropColumn([
                'cad',
                'isSentry',
                'impact_probability',
                'impact_date',
                'torino_scale'
            ]);
        });
    }

};
