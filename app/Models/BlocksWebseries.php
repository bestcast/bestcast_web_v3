<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use DB;
use Lib;

class BlocksWebseries extends Model
{
    public $table ='blocks_webseries';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'blocks_id',
        'webseries_id'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'webseries_id'      => 'integer',
        'shows_id'          => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }


    public function blocks()
    {
        return $this->belongsTo('App\Models\Blocks','blocks_id','id');
    }
    /*public function Webseries()
    {
        return $this->belongsTo('App\Models\Webseries','webseries_id','id');
    }*/
    public function webseries()
    {
        return $this->belongsTo(\App\Models\Webseries::class, 'webseries_id');
    }
}
