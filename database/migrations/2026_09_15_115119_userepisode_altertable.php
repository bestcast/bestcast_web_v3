<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users_episodes', function (Blueprint $table) {
            $table->unsignedBigInteger('webseries_id')->nullable()->after('episode_id');
            $table->unsignedBigInteger('season_id')->nullable()->after('episode_id');
            $table->index(['user_id', 'profile_id', 'webseries_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
