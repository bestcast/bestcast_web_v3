<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrailerWatchLog extends Model
{
    use HasFactory;

    protected $fillable = ['movie_id', 'user_id', 'session_id', 'ip_address', 'watch_count', 'first_watched_at', 'last_watched_at'];

    public $table ='trailer_watch_logs';
}
