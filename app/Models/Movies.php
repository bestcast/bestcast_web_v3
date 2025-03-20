<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;

use Illuminate\Http\Request;

class Movies extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='movies';

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
        'release_date',
        'thumbnail_id',
        'medium_id',
        'image_id',
        'portrait_id',
        'portraitsmall_id',
        'duration',//must be in seconds
        'age_restriction',
        'certificate',
        'certificate_text',
        'tag_text',
        'is_upcoming',
        'topten',
        'movie_access',
        'trailer_url',
        'trailer_url_480p',
        'video_url',
        'video_url_480p',
        'video_url_720p',
        'video_url_1080p',
        'moviesource',
        'subtitle_status',                
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
        'release_date'      => 'date',
        'thumbnail_id'      => 'integer',
        'medium_id'         => 'integer',
        'image_id'          => 'integer',
        'portrait_id'       => 'integer',
        'portraitsmall_id'  => 'integer',
        'duration'          => 'string',
        'age_restriction'   => 'integer',
        'certificate'       => 'string',
        'certificate_text'  => 'string',
        'tag_text'          => 'string',
        'is_upcoming'       => 'integer',
        'topten'            => 'integer',
        'movie_access'      => 'integer',
        'trailer_url'       => 'string',
        'trailer_url_480p'  => 'string',
        'video_url'         => 'string',
        'video_url_480p'    => 'string',
        'video_url_720p'    => 'string',
        'video_url_1080p'   => 'string',
        'moviesource'       => 'string',
        'subtitle_status'   => 'integer',
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
        'urlkey' => 'required|max:1000|unique:movies,urlkey',
        'video_url'=>'required',
        'release_date'=>'required',
        'duration'=>'required|numeric'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'video_url.required' => 'Video URL is required.',
        'release_date.required' => 'Release Date is required.',
        'urlkey.required' => 'URL Key is required.',
        'urlkey.unique' => 'URL Key already exists.',
        'duration.required' => 'Duration is required. Must be in seconds',
        'duration.numeric' => 'Duration should be in seconds. eg: 8520 for 2hr 22min'
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
    
    public function medium()
    {
        return $this->belongsTo('App\Models\Media','medium_id','id');
    }
    
    public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }
    
    public function portrait()
    {
        return $this->belongsTo('App\Models\Media','portrait_id','id');
    }

    public function portraitsmall()
    {
        return $this->belongsTo('App\Models\Media','portraitsmall_id','id');
    }

    public function genres()
    {
        return $this->hasMany('App\Models\MoviesGenres','movie_id','id');
    }
    public function languages()
    {
        return $this->hasMany('App\Models\MoviesLanguages','movie_id','id');
    }
    public function users()
    {
        return $this->hasMany('App\Models\MoviesUsers','movie_id','id');
    }
    public function related()
    {
        return $this->hasMany('App\Models\MoviesRelated','movie_id','id')
                ->whereHas('related', function ($q) {
                    $q->where('status', 1);
                });
    }
    public function meta()
    {
        return $this->hasMany('App\Models\Meta','meta_id','id')->where('type','2');
    }
    public function subtitle()
    {
        return $this->hasMany('App\Models\MoviesSubtitle','movie_id','id');
    }
    
    public function usermovies()//$user_id, $profile_id //should not pass here
    {
        return $this->hasMany('App\Models\UsersMovies','movie_id','id');//->where('user_id',$user_id)->where('profile_id',$profile_id); //should not pass here
    }


    public static function getList()
    {        

        $data = Movies::latest();//with('genres','languages')->

        $getGenre=app('request')->input('genre');
        if(!empty($getGenre)){
            $data->whereHas('genres', function($q) use($getGenre) {
                $q->where('genre_id', $getGenre);
            });
        }
        
        $getLanguage=app('request')->input('language');
        if(!empty($getLanguage)){
            $data->whereHas('languages', function($q) use($getLanguage) {
                $q->where('language_id', $getLanguage);
            });
        }
        
        $getSearch=app('request')->input('search');
        if(!empty($getSearch)){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }

        $sortorder=app('request')->input('sortorder');
        if($sortorder==1){
            $data =$data->orderBy('title','asc');
        }elseif($sortorder==2){
            $data =$data->orderBy('title','desc');
        }elseif($sortorder==3){
            $data =$data->orderBy('release_date','asc');
        }elseif($sortorder==4){
            $data =$data->orderBy('release_date','desc');
        }


        $data =$data->paginate(20)->withQueryString();
        return $data;
    }
    
    public static function getApiList($user_id)
    {        

        $data = Movies::where('status',1)->latest();//with('genres','languages')->
            
        $child=app('request')->input('child');if(!empty($child)){ 
            $data=$data->where('age_restriction','>=',13); 
        }  

            $data = $data->with([
                'usermovies'=> function ($query) use ($user_id){
                    $query->where('user_id', $user_id);
//                  if(!empty($user_id)){
                        $profile_id=app('request')->input('profile_id');if(empty($profile_id)){ $profile_id=0; }
                        $query->where('profile_id',$profile_id);
//                  }
                } 
            ]);


        $getGenre=app('request')->input('genre');
        if(!empty($getGenre)){
            $data->whereHas('genres', function($q) use($getGenre) {
                $q->where('genre_id', $getGenre);
            });
        }

        $getLanguage=app('request')->input('language');
        if(!empty($getLanguage)){
            $data =$data->whereHas('languages', function($q) use($getLanguage) {
                $q->where('language_id', $getLanguage);
            });
        }
        
        $getSearch=app('request')->input('search');
        if(!empty($getSearch) && strlen($getSearch)>=3){
            $data =$data->where('title','like',"%".urldecode($getSearch)."%");
        }

        $sortorder=app('request')->input('sortorder');
        if($sortorder==1){
            $data =$data->orderBy('title','asc');
        }elseif($sortorder==2){
            $data =$data->orderBy('title','desc');
        }elseif($sortorder==3){
            $data =$data->orderBy('release_date','asc');
        }elseif($sortorder==4){
            $data =$data->orderBy('release_date','desc');
        }

        $paginate=app('request')->input('paginate');
        $paginate=empty($paginate)?60:$paginate;
        $data =$data->paginate($paginate);
        return $data;
    }



    public static function getProducerList($user_id)
    {        
        $data = Movies::where('status',1)->latest();
        $data->whereHas('users', function($q) use($user_id) {
            $q->where('user_id', $user_id);
        });

        $sortorder=app('request')->input('sortorder');
        $data =$data->orderBy('title','asc')->limit(100)->get();
        return $data;
    }

}
