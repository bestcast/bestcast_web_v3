<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'notification';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->string('type')->nullable();//admin,user
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->integer('mark_read')->default(0);
                $table->integer('visibility')->default(0);//visible to user login 0 - admin only can see
                $table->string('model')->nullable(); //Movies,User etc
                $table->unsignedBigInteger('relation_id')->default(0);
                $table->integer('icon')->default(0);
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
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
        $table = 'notification';
        Schema::connection($connection)->dropIfExists($table);
    }
}
