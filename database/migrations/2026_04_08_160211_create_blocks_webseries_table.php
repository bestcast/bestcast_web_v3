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
        Schema::create('blocks_webseries', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedBigInteger('blocks_id')->default(0);
            $table->unsignedBigInteger('webseries_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blocks_webseries');
    }
};
