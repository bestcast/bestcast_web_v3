<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'users_profile';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('profileicon_id')->default(0);
                $table->string('name')->nullable();
                $table->integer('language')->default(0);
                $table->integer('autoplay')->default(1);
                $table->integer('is_child')->default(0);
                $table->integer('pin')->nullable();
                $table->datetime('appnotify')->default(now()->subDays(30));
                $table->datetime('last_login')->nullable();
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
        $table = 'users_profile';
        Schema::connection($connection)->dropIfExists($table);
    }
}
