<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'transaction';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->unsignedBigInteger('user_id')->default(0);
                $table->unsignedBigInteger('subscription_id')->default(0);//Subscription table
                $table->integer('status')->default(0); 
                $table->string('title')->nullable();
                $table->string('razorpay_plan_id')->nullable(); 
                $table->string('razorpay_subscription_id')->nullable();  
                $table->string('razorpay_payment_id')->nullable(); 
                $table->string('razorpay_offer_id')->nullable(); 
                $table->string('razorpay_signature')->nullable(); 
                $table->decimal('price', 9, 2)->default(0); 
                $table->integer('counts')->default(0); 
                $table->integer('notified')->default(0); 
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
        $table = 'transaction';
        Schema::connection($connection)->dropIfExists($table);
    }
}
