<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('webseries', function (Blueprint $table) {  // use confirmed table name
            $table->enum('region_access', ['india_only', 'global'])->default('india_only');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('webseries', function (Blueprint $table) {
            //
        });
    }
};
