<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'banner';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('status')->default(0); 
                $table->string('urlkey')->unique();
                $table->string('title')->nullable();
                $table->integer('type')->default(0);  //Movies or TV Shows
                $table->unsignedBigInteger('movies_id')->default(0); 
                $table->unsignedBigInteger('shows_id')->default(0); 
                $table->unsignedBigInteger('page_id')->nullable(); 
                $table->integer('sortorder')->default(0);
                $table->unsignedBigInteger('thumbnail_id')->nullable();
                $table->unsignedBigInteger('image_id')->nullable();
                $table->unsignedBigInteger('logo_id')->nullable();
                $table->unsignedBigInteger('portrait_id')->nullable();
                $table->unsignedBigInteger('portraitsmall_id')->nullable();
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
        $table = 'banner';
        Schema::connection($connection)->dropIfExists($table);
    }
}
