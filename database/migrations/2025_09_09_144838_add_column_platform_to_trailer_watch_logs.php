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
        Schema::table('trailer_watch_logs', function (Blueprint $table) {
            $table->string('platform')->default('web')->after('watch_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trailer_watch_logs', function (Blueprint $table) {
            //
        });
    }
};
