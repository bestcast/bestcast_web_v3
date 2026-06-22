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
        DB::table('meta')
        ->where('path', 'pricing_content')
        ->update([
            'value' => '<p>Streaming quality varies by internet speed, device, and content. HD, Full HD, 4K and HDR may not be available for all titles. Watch on supported devices with up to 4 simultaneous streams. See our <a href="../../../terms-conditions">Terms of Use</a> for more details.</p>'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
