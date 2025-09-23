<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = config('roles.connection');
        $table = 'movies';
        $tableCheck = Schema::connection($connection)->hasTable($table);

        if (!$tableCheck) {
            Schema::connection($connection)->create($table, function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('status')->default(0); 
                $table->string('urlkey')->unique();
                $table->string('title')->nullable();
                $table->longText('content')->nullable();
                $table->datetime('published_date')->nullable();
                $table->date('release_date')->nullable();
                $table->unsignedBigInteger('thumbnail_id')->nullable();
                $table->unsignedBigInteger('medium_id')->nullable();
                $table->unsignedBigInteger('image_id')->nullable();
                $table->unsignedBigInteger('portrait_id')->nullable();
                $table->unsignedBigInteger('portraitsmall_id')->nullable();
                $table->string('duration')->nullable();
                $table->integer('age_restriction')->default(0);
                $table->string('certificate')->default('U/A 13+');
                $table->string('certificate_text')->nullable();
                $table->string('tag_text')->nullable();
                $table->integer('is_upcoming')->default(0);
                $table->integer('topten')->default(0);
                $table->integer('movie_access')->default(0);
                $table->string('trailer_url')->nullable();
                $table->string('trailer_url_480p')->nullable();
                $table->string('video_url')->nullable();
                $table->string('video_url_480p')->nullable();
                $table->string('video_url_720p')->nullable();
                $table->string('video_url_1080p')->nullable();
                $table->string('moviesource')->nullable();
                $table->integer('subtitle_status')->default(0);
                // subtitle in meta
                // genres by group (genres, this movie is)
                // language
                // user by group (producer, director, actor, actress, music director)
                // related_movies by group (related, you may like also)
                $table->integer('created_by')->nullable();
                $table->integer('updated_by')->nullable();
                $table->timestamps();
            });
        }
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $connection = config('roles.connection');
        $table = 'movies';
        Schema::connection($connection)->dropIfExists($table);
    }
}
