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
        DB::statement("UPDATE users SET country_code = '+91' WHERE country_code IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
