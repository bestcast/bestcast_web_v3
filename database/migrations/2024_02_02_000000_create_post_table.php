<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'post';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                //$table->unsignedBigInteger('copy_id')->default(0);
                $table->longText('copy_id')->nullable();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->string('urlkey');//->unique()
                $table->longText('title')->nullable();
                $table->longText('excerpt')->nullable();
                $table->string('type')->default('page');
                $table->string('status')->default(0); 
                $table->string('template')->default(0); 
                $table->datetime('published_date')->nullable();
                $table->string('password')->nullable();
                $table->unsignedBigInteger('thumbnail_id')->nullable();
                $table->unsignedBigInteger('image_id')->nullable();
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
        $table = 'post';
        Schema::connection($connection)->dropIfExists($table);
    }
}
