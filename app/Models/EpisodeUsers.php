<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeUsers extends Model
{
    public $table = 'episode_users';

    public $timestamps = false;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'episode_id',
        'user_id',
        'group'
    ];

    protected $casts = [
        'id'         => 'integer',
        'episode_id' => 'integer',
        'user_id'    => 'integer',
        'group'      => 'integer'
    ];

    public function episodes()
    {
        return $this->belongsTo('App\Models\Episode', 'episode_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }
}