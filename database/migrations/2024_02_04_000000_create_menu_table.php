<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'menu';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('status')->default(0);
                $table->integer('category')->default(0);
                $table->string('name');
                $table->string('classname')->nullable();
                $table->string('post_id')->default(0);
                $table->string('parent_id')->default(0);
                $table->string('url')->nullable();
                $table->string('target')->default(0);
                $table->string('icon_id')->nullable();
                $table->string('image_id')->nullable();
                $table->string('thumbnail_id')->nullable();
                $table->integer('sort')->default(0);
                $table->integer('type')->default(0);//line,megamenu
                $table->longText('content')->nullable();
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
        $table = 'menu';
        Schema::connection($connection)->dropIfExists($table);
    }
}
