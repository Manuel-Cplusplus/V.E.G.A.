<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('learn_quizzes', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->unsignedBigInteger('learn_content_id');
            $table->timestamps();

            $table->foreign('learn_content_id')->references('id')->on('learn_contents')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('learn_quizzes');
    }
};
