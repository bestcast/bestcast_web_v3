<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileiconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'profileicon';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('urlkey')->unique();
                $table->string('title')->nullable();
                $table->integer('category')->default(0);
                $table->unsignedBigInteger('thumbnail_id')->nullable();
                $table->unsignedBigInteger('image_id')->nullable();
                $table->integer('sortorder')->default(0);
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                //movies
                //shows
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
        $table = 'profileicon';
        Schema::connection($connection)->dropIfExists($table);
    }
}
