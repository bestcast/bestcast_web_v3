<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;

class WebseriesGenres extends Model
{
    use RoleHasRelations;
    use Slugable;
    
    /**
     *
     * @var Table name
     */
    public $table ='webseries_genres';

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
        'webseries_id',
        'genre_id',
        'group'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'          => 'integer',
        'webseries_id'  => 'integer',
        'genre_id'    => 'integer',
        'group'       => 'integer'
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


    public function webseries()
    {
        return $this->belongsTo('App\Models\Webseries','webseries_id','id');
    }
    public function genres()
    {
        return $this->belongsTo('App\Models\Genres','genre_id','id');
    }
}