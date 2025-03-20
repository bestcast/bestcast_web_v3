<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;

class MoviesLanguages extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;
    public $timestamps = false;
    /**
     *
     * @var Table name
     */
    public $table ='movies_languages';

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
        'language_id',
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
        'language_id'          => 'integer',
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
    public function languages()
    {
        return $this->belongsTo('App\Models\Languages','language_id','id');
    }
}

