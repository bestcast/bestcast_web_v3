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
        Schema::table('user_movie_watch_logs', function (Blueprint $table) {
            $table->enum('watch_type', ['movie', 'trailer'])
                  ->default('movie')
                  ->after('platform');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_movie_watch_logs', function (Blueprint $table) {
            $table->dropColumn('watch_type');
        });
    }
};
