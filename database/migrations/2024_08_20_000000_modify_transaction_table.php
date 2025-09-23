<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyTransactionTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transaction', function(Blueprint $table)
        {
            $table->string('razorpay_order_id')->nullable()->after('razorpay_subscription_id');
            $table->string('razorpay_receipt')->nullable();
            $table->string('razorpay_entity')->default('order');
            $table->string('razorpay_currency')->default('INR');
            $table->longText('razorpay_data')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

}