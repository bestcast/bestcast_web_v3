<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'category';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('parent_id')->default(0);
                $table->unsignedBigInteger('post_id')->default(0);
                $table->integer('sort')->default(0);
                $table->integer('postcount')->default(0);
                $table->integer('status')->default(0);
                $table->string('urlkey');
                $table->string('title')->nullable();
                $table->string('path')->nullable();
                $table->string('path_ids')->default(0);
                $table->integer('template')->default(0);
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
        $table = 'category';
        Schema::connection($connection)->dropIfExists($table);
    }
}
