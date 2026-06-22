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
        Schema::table('webseries', function(Blueprint $table)
        {
            //$table->string('urlkey')->unique()->after('title');
            $table->string('urlkey')->nullable()->unique()->after('title');
            $table->longText('content')->nullable()->after('urlkey');
            $table->datetime('published_date')->nullable()->after('content');
            $table->date('release_date')->nullable()->after('published_date');
            $table->string('duration')->nullable()->after('portraitsmall_id');
            $table->integer('age_restriction')->default(0)->after('duration');
            $table->string('certificate')->default('U/A 13+')->after('age_restriction');
            $table->string('certificate_text')->nullable()->after('certificate');
            $table->string('tag_text')->nullable()->after('certificate_text');
            $table->integer('is_upcoming')->default(0)->after('tag_text');
            $table->integer('topten')->default(0)->after('is_upcoming');
            $table->string('trailer_url')->nullable()->after('topten');
            $table->string('trailer_url_480p')->nullable()->after('trailer_url');
            $table->string('video_url')->nullable()->after('trailer_url_480p');
            $table->string('moviesource')->nullable()->after('video_url');
            $table->integer('subtitle_status')->default(0)->after('moviesource');
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
