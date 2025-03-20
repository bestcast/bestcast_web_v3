<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

class Blocks extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='blocks';

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
        'type',
        'page_id',
        'sortorder',
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
        'type'              => 'integer',
        'page_id'           => 'integer',
        'sortorder'         => 'integer',
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
        'urlkey' => 'required|max:1000|unique:blocks,urlkey'
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

    public function movies()
    {
        return $this->hasMany('App\Models\BlocksMovies','blocks_id','id');
    }

    public function shows()
    {
        return $this->hasMany('App\Models\BlocksShows','blocks_id','id');
    }
    
    public function page()
    {
        return $this->belongsTo('App\Models\Post','page_id','id');
    }

    public function genres()
    {
        return $this->hasMany('App\Models\BlocksGenres','blocks_id','id');
    }

    public function languages()
    {
        return $this->hasMany('App\Models\BlocksLanguages','blocks_id','id');
    }

    public static function getList()
    {
        $data = Blocks::latest();

        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }
        
        $data =$data->orderBy('sortorder','asc')->orderBy('title','asc');
        $data =$data->paginate(20);
        return $data;
    }

    public static function getApiList($user_id)
    {        

        $data = Blocks::with([
            'movies' => function ($query) {
                $query->whereHas('movies', function ($q) {
                    $q->where('status',1);      
                    $child=app('request')->input('child');if(!empty($child)){ 
                        $q->where('age_restriction','>=',13); 
                    }              
                });
            },
            'movies.movies',
            'movies.movies.image',
            'movies.movies.thumbnail',
            'movies.movies.usermovies'=> function ($query) use ($user_id){
                    $query->where('user_id', $user_id);

                    $profile_id=app('request')->input('profile_id');if(empty($profile_id)){ $profile_id=0; }
                    $query->where('profile_id',$profile_id);
                },
            'genres' ,
            'languages' 
        ]);


        //type 0 is movies, type 1 is tv shows
        $data = $data->where('status',1)->where('type',0)->latest();//with('genres','languages')->
        

        $genre_id=app('request')->input('genre_id');if(!empty($genre_id)){ //filter by genre
            $data = $data->whereHas('genres', function ($q) use ($genre_id) {
                $q->where('genre_id',$genre_id);
            });
        }

        $genre_id=app('request')->input('language_id');if(!empty($genre_id)){ //filter by language
            $data = $data->whereHas('languages', function ($q) use ($genre_id) {
                $q->where('language_id',$genre_id);
            });
        }

        $getpageid=app('request')->input('page_id');if(!empty($getpageid)){ //filter by page
            $data =$data->where('page_id',$getpageid);
        }

        $data =$data->orderBy('sortorder','asc')->orderBy('title','asc');
        //dd($data->first()->movies->first()->movies->title);
        //dd($data->first()->movies);

        $paginate=app('request')->input('paginate');
        $paginate=empty($paginate)?5:$paginate;

        $data =$data->paginate($paginate);

        //$data =$data->paginate(5);
        return $data;
    }
}
