<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoreConfigFieldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'core_config_field';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('path');
                $table->string('label')->nullable();
                $table->string('type')->default('input');
                $table->string('placeholder')->nullable();
                $table->string('comment')->nullable();
                $table->string('classname')->nullable();
                $table->string('errormessage')->nullable();
                $table->string('option')->default('');
                $table->string('group')->default('General');
                $table->string('subgroup')->default('General');
                $table->integer('sort')->default(0);
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
        $table = 'core_config_field';
        Schema::connection($connection)->dropIfExists($table);
    }
}
