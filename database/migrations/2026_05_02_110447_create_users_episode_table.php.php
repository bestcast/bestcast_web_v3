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
        Schema::create('users_episodes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('profile_id')->default(0);
            $table->unsignedBigInteger('episode_id')->default(0);
            $table->integer('mylist')->default(0);
            $table->integer('likes')->default(0);
            $table->string('watch_time')->default(0);
            $table->integer('watching')->default(0);
            $table->integer('watched_percent')->default(0);
            $table->integer('watched')->default(0); //for producer count when user watch movie 20min atleast
            $table->integer('viewed')->default(0);
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
