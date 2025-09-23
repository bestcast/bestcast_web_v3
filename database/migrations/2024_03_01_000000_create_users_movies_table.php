<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'users_movies';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('profile_id')->default(0);
                $table->unsignedBigInteger('movie_id')->default(0);
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
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('roles.connection');
        $table = 'users_movies';
        Schema::connection($connection)->dropIfExists($table);
    }
}
