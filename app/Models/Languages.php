<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

class Languages extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='languages';

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
        'status',
        'sort',
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
        'status'            => 'string',
        'sort'              => 'integer',
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
        'urlkey' => 'required|max:1000|unique:languages,urlkey'
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
        $ar=['0' => 'Active', '1' => 'Disable'];
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }

    public static function getList()
    {
        $data = Languages::latest();

        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }

        $data =$data->orderBy('sort','asc')->orderBy('title','asc');
        $data =$data->paginate(50);
        return $data;
    }

    public static function getApiList()
    {       
        $data = Languages::where('status',1)->latest();//with('genres','languages')->
        $data =$data->orderBy('sort','asc')->orderBy('title','asc')->get();
        return $data;  
    }
}
