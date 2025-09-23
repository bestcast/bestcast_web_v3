<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use Lib;

class Profileicon extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='profileicon';

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
        'urlkey',
        'title',
        'category',
        'thumbnail_id',
        'image_id',
        'sortorder',
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
        'urlkey'            => 'string',
        'title'             => 'string',
        'category'          => 'integer',
        'thumbnail_id'      => 'integer',
        'image_id'          => 'integer',
        'sortorder'         => 'integer',
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
        'urlkey' => 'required|max:1000|unique:profileicon,urlkey'
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
        $data = Profileicon::latest();

        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }
        
        $data =$data->orderBy('sortorder','asc');
        $data =$data->paginate(50);
        return $data;
    }
    
    public static function getApiList()
    {        
        $data = Profileicon::latest();
        $data =$data->orderBy('sortorder','asc')->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;

    }
}
