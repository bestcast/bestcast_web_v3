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
        Schema::table('users_device', function (Blueprint $table) {
            $table->boolean('is_quiz_active')->default(0)->after('last_login');
            $table->timestamp('quiz_started_at')->nullable()->after('is_quiz_active');
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
