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
        DB::table('movies')
            ->whereNull('release_time')
            ->update(['release_time' => '00:00:00']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('movies')
            ->where('release_time', '00:00:00')
            ->update(['release_time' => null]);
    }
};
