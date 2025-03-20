<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;
use Auth;
use App\Models\UsersProfile;
use Carbon\Carbon;

class Appnotify extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='appnotify';

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
        'movie_id',
        'title',
        'thumbnail_id',
        'notifydate',
        'created_by'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'movie_id'          => 'integer',
        'title'             => 'string',
        'thumbnail_id'      => 'integer',
        'created_by'        => 'integer',
        'notifydate'        => 'datetime',
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
        'movie_id' => 'required',
        'thumbnail_id' => 'required'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'movie_id' => 'Movie is required.',
        'thumbnail_id' => 'Thumbnail for notification is required.'
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
    
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }

    public function movie()
    {
        return $this->belongsTo('App\Models\Movies','movie_id','id');
    }

    public static function getList()
    {
        $data = Appnotify::latest();
        //$data =$data->orderBy('sort','asc')->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;
    }

    public static function getApiList($usersProfile)
    {              
        $data = Appnotify::latest()->limit(5)->get();
        $data = $data->map(function ($item) use ($usersProfile) {
                $item->is_read =1;
                if(empty($usersProfile->appnotify) || $usersProfile->appnotify<=$item->created_at){
                    $item->is_read =0;
                }
                return $item;
            });

        return $data;  
    }
}
