<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('media_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['movie', 'webseries', 'general'])->default('general');
            $table->unsignedBigInteger('reference_id')->nullable(); // movie_id / webseries_id
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_folders');
    }
};
