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
        Schema::create('learn_structures', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('content');
            $table->unsignedBigInteger('LLMID');
            $table->unsignedBigInteger('learn_prompt_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->foreign('LLMID')->references('id')->on('llms')->onDelete('cascade');
            $table->foreign('learn_prompt_id')->references('id')->on('learn_prompts')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learn_structures');
    }
};
