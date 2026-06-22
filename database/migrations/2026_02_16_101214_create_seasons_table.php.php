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
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('webseries_id');
            $table->unsignedInteger('season_number');

            $table->string('title');
            $table->boolean('status')->default(1);

            $table->timestamps();

            // Foreign key
            $table->foreign('webseries_id')
                  ->references('id')
                  ->on('webseries')
                  ->onDelete('cascade');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
