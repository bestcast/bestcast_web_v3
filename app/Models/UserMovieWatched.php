<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movies;
use App\User;

class UserMovieWatched extends Model
{
    protected $fillable = ['user_id', 'movie_id', 'first_watched_at', 'last_watched_at', 'watch_count'];

    public $table ='user_movie_watched';
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movie() {
        return $this->belongsTo(Movies::class);
    }
}

