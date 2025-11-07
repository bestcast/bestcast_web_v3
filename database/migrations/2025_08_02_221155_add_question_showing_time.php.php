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
        Schema::table('questions', function(Blueprint $table)
        {
            $table->unsignedInteger('show_time_hour')->default(0)->after('question_name');
            $table->unsignedInteger('show_time_min')->default(0)->after('show_time_hour');
            $table->unsignedInteger('show_time_sec')->default(0)->after('show_time_min');
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
