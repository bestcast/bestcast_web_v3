<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersDeviceTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_device', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('token_id')->nullable();
            $table->unsignedBigInteger('profile_id')->nullable();
            $table->string('token')->nullable();
            $table->string('ip_address')->nullable();
            $table->longText('device')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_device');
    }
};
