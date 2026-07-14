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
        Schema::table('transaction', function (Blueprint $table) {
            $table->timestamp('bmp_event_sent_at')->nullable()->after('bmp_paid_event_sent');
        });
    }

    public function down()
    {
        Schema::table('transaction', function (Blueprint $table) {
            $table->dropColumn('bmp_event_sent_at');
        });
    }
};
