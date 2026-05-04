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
        Schema::create('episode_subtitles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('episode_id')->default(0);
            $table->integer('is_active')->default(0);
            $table->string('label')->nullable();
            $table->string('url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episode_subtitles');
    }
};
