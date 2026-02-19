<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Episode extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'season_id',
        'episode_number',
        'urlkey',
        'title',
        'status'
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

}
