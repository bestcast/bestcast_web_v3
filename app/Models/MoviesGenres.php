<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;

class MoviesGenres extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    public $timestamps = false;
    /**
     *
     * @var Table name
     */
    public $table ='movies_genres';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'movie_id',
        'genre_id',
        'group'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'movie_id'         => 'integer',
        'genre_id'          => 'integer',
        'group'          => 'integer'
    ];


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    public function movies()
    {
        return $this->belongsTo('App\Models\Movies','movie_id','id');
    }
    public function genres()
    {
        return $this->belongsTo('App\Models\Genres','genre_id','id');
    }
}

