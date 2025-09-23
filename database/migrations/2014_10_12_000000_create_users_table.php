<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->integer('status')->default(1); //User status Active or Disabled. Default Active
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable()->unique();
            $table->string('otp')->nullable();
            $table->string('password');
            $table->string('title')->nullable();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('photo')->nullable();
            $table->integer('type')->default(0); //Actor, Actress etc. Default type is User
            $table->integer('subscribe')->default(1); //For newsletter. Default Subscribed
            $table->integer('plan')->default(0); //Subscription Plan
            $table->datetime('plan_expiry')->nullable();
            $table->string('tvcode')->nullable();
            $table->string('referal_code')->nullable();
            $table->integer('credits_used')->default(0);
            $table->string('refferer')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
