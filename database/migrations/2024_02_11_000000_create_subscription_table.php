<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'subscription';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('status')->default(0); 
                $table->string('urlkey')->unique();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->integer('video_quality')->default(0);
                $table->integer('video_resolution')->default(0);
                $table->integer('video_device')->default(0);
                $table->integer('video_sametime')->default(10);
                $table->integer('device_limit')->default(1); 
                $table->integer('duration')->default(28);
                $table->integer('duration_type')->default(0);
                $table->decimal('before_price', 9, 2)->default(0); 
                $table->decimal('price', 9, 2)->default(0); 
                $table->integer('ads')->default(0); 
                $table->string('tagtext')->nullable(); 
                $table->integer('sortorder')->default(0);
                $table->string('razorpay_id')->nullable(); 
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
        $table = 'subscription';
        Schema::connection($connection)->dropIfExists($table);
    }
}
