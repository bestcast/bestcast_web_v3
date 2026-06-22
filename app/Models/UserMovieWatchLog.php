<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Movies;
use App\User;

class UserMovieWatchLog extends Model
{
    protected $fillable = ['user_id', 'movie_id', 'watched_at', 'watch_type'];

    public $table ='user_movie_watch_logs';
    
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function movie() {
        return $this->belongsTo(Movies::class);
    }
}

