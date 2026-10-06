<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('episodes', function (Blueprint $table) {
            $table->unsignedInteger('intro_start')->nullable()->default(0)->after('duration');
            $table->unsignedInteger('intro_end')->nullable()->default(0)->after('intro_start');
            // intro_start and intro_end are in SECONDS
            // e.g. intro_start=30, intro_end=90 means intro runs from 0:30 to 1:30
        });
    }
    public function down(): void {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropColumn(['intro_start', 'intro_end']);
        });
    }

};
