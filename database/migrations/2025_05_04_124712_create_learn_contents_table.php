<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('learn_contents', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->unsignedBigInteger('learn_structure_id');
            $table->timestamps();

            $table->foreign('learn_structure_id')->references('id')->on('learn_structures')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('learn_contents');
    }

};
