<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

class Shows extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='shows';

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
        'updated_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'urlkey',
        'title',
        'content',
        'published_date',
        'thumbnail_id',
        'image_id',
        'created_by',
        'updated_by'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'status'            => 'string',
        'urlkey'            => 'string',
        'title'             => 'string',
        'content'           => 'string',
        'published_date'    => 'datetime',
        'thumbnail_id'      => 'integer',
        'image_id'          => 'integer',
        'created_by'        => 'integer',
        'updated_by'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;



    /**
     * Validation rules for the attributes
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:1000',
        'urlkey' => 'required|max:1000|unique:shows,urlkey'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'urlkey.required' => 'URL Key is required.',
        'urlkey.unique' => 'URL Key already exists.'
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

    public function status($val)
    {
        $ar=['0' => 'Disable', '1' => 'Active'];
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media','image_id','id');
    }
    
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }


    public static function getList()
    {
        $data = Shows::select('id','title','urlkey','status');
        $data =$data->latest();
        $data =$data->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;
    }
}
