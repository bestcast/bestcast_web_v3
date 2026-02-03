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
        Schema::table('reward_claims', function (Blueprint $table) {
            if (Schema::hasColumn('reward_claims', 'bank_name')) {
                $table->dropColumn('bank_name');
            }
            if (Schema::hasColumn('reward_claims', 'account_no')) {
                $table->dropColumn('account_no');
            }
            if (Schema::hasColumn('reward_claims', 'ifsc')) {
                $table->dropColumn('ifsc');
            }
            if (Schema::hasColumn('reward_claims', 'branch')) {
                $table->dropColumn('branch');
            }
            if (Schema::hasColumn('reward_claims', 'upi')) {
                $table->dropColumn('upi');
            }

            $table->string('door_no', 50)->nullable()->after('full_name');
            $table->string('street_name', 255)->nullable()->after('door_no');
            $table->string('country', 100)->nullable()->after('street_name');
            $table->string('state', 100)->nullable()->after('country');
            $table->string('city', 100)->nullable()->after('state');
            $table->string('pin_code', 10)->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reward_claims', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('branch')->nullable();
            $table->string('upi')->nullable();

            $table->dropColumn([
                'door_no',
                'street_name',
                'country',
                'state',
                'city',
                'pin_code',
            ]);
        });
    }
};
