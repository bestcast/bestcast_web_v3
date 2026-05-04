<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'webseries_id',
        'season_number',
        'title',
        'status'
    ];

    public function webseries()
    {
        return $this->belongsTo(Webseries::class);
    }
    /*public function episodes()
    {
        return $this->hasMany(Episode::class, 'season_id');
    }*/
    public function episodes()
    {
        return $this->hasMany(Episode::class)->orderBy('episode_number');
    }
    public function firstEpisode()
    {
        return $this->hasOne(Episode::class, 'season_id')->orderBy('id', 'asc');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($season) {

            if ($season->isForceDeleting()) {
                $season->episodes()->withTrashed()->forceDelete();
            } else {
                foreach ($season->episodes as $episode) {
                    $episode->delete();
                }
            }
        });
    }

}
