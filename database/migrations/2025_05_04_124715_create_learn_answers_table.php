<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('learn_answers', function (Blueprint $table) {
            $table->id();
            $table->text('answer');
            $table->boolean('is_correct');
            $table->unsignedBigInteger('learn_quiz_id');
            $table->timestamps();

            $table->foreign('learn_quiz_id')->references('id')->on('learn_quizzes')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('learn_answers');
    }
};
