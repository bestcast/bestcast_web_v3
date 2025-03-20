<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'shows';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('status')->default(0); 
                $table->string('urlkey')->unique();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->datetime('published_date')->nullable();
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
        $table = 'shows';
        Schema::connection($connection)->dropIfExists($table);
    }
}
