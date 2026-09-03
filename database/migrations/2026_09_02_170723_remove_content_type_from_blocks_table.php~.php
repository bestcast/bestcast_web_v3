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
        $connection = config('roles.connection');
        $table = 'blocks';

        if (Schema::connection($connection)->hasColumn($table, 'content_type')) {
            Schema::connection($connection)->table($table, function (Blueprint $table) {
                $table->dropColumn('content_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('roles.connection');
        $table = 'blocks';

        if (!Schema::connection($connection)->hasColumn($table, 'content_type')) {
            Schema::connection($connection)->table($table, function (Blueprint $table) {
                $table->string('content_type')->nullable()->default('movies');
            });
        }
    }
};