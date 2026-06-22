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
        Schema::create('webseries_genres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webseries_id');
            $table->unsignedInteger('genre_id');
            $table->integer('group')->default(0);
            $table->unique(['webseries_id', 'genre_id']);
            $table->timestamps();
            $table->foreign('webseries_id')->references('id')->on('webseries')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webseries_genres');
    }
};
