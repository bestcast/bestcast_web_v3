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
        Schema::create('webseries_languages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('webseries_id');
            $table->unsignedInteger('language_id');
            $table->integer('group')->default(0);
            $table->unique(['webseries_id', 'language_id']);
            $table->foreign('webseries_id')->references('id')->on('webseries')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
