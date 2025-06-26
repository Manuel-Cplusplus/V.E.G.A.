<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::table('learn_contents', function (Blueprint $table) {
            $table->unsignedInteger('version')->default(1)->after('learn_structure_id');
            $table->unsignedBigInteger('original_content_id')->nullable()->after('version');

            $table->foreign('original_content_id')
                ->references('id')
                ->on('learn_contents')
                ->onDelete('cascade'); 
        });
    }

    public function down(): void {
        Schema::table('learn_contents', function (Blueprint $table) {
            $table->dropForeign(['original_content_id']);
            $table->dropColumn(['version', 'original_content_id']);
        });
    }
};
