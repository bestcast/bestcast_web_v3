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
            //$table->unsignedInteger('show_question_time')->after('question_name');

            if (!Schema::hasColumn('questions', 'show_question_time')) {
                $table->unsignedInteger('show_question_time')->after('question_name');
            }
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
