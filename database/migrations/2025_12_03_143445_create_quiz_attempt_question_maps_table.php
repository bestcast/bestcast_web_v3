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
        Schema::create('quiz_attempt_question_maps', function (Blueprint $table) {
            $table->id();

            // Attempt ID
            $table->unsignedBigInteger('attempt_id');
            $table->foreign('attempt_id')
                ->references('id')
                ->on('quiz_attempts')
                ->onDelete('cascade');

            // Question ID
            $table->unsignedBigInteger('question_id');
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_question_maps');
    }
};
