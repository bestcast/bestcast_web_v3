<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Media;

class Webseries extends Model
{
    use SoftDeletes;
    /**
     *
     * @var Table name
     */
    public $table ='webseries';

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
        'title',
        'status',
        'urlkey', 
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
        'trailer_url',
        'trailer_url_480p',
        'video_url',
        'movie_access',
        'moviesource',
        'subtitle_status',
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'title'             => 'string',
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
        'movie_access'      => 'integer',
        'moviesource'       => 'string',
        'subtitle_status'   => 'integer',
        'created_by'        => 'integer',
        'updated_by'        => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        /*'created_at'        => 'datetime',
        'updated_at'        => 'datetime',*/
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
        'trailer_url'=>'required',
        'release_date'=>'required',
        'duration'=>'required|numeric'
    ];


    public static $messages = [
        'title.required' => 'Title is required.',
        'trailer_url.required' => 'Video URL is required.',
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
    
    /*public function thumbnail()
    {
        return $this->belongsTo('App\Models\Media','thumbnail_id','id');
    }*/
    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
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
        return $this->hasMany('App\Models\WebseriesGenres','webseries_id','id');
    }
    public function languages()
    {
        return $this->hasMany('App\Models\WebseriesLanguages','webseries_id','id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\EpisodeUsers','episode_id','id');
    }
    public function related()
    {
        return $this->hasMany(EpisodeRelated::class, 'episode_id');
    }
    public function meta()
    {
        return $this->hasMany('App\Models\Meta','meta_id','id')->where('type','2');
    }
    public function subtitle()
    {
        return $this->hasMany('App\Models\MoviesSubtitle','movie_id','id');
    }
    
    public function userepisodes()//$user_id, $profile_id //should not pass here
    {
        return $this->hasMany('App\Models\UsersEpisodes','episode_id','id');//->where('user_id',$user_id)->where('profile_id',$profile_id); //should not pass here
    }


    public static function getList()
    {        

        $data = Webseries::latest();//with('genres','languages')->

        /*$getGenre=app('request')->input('genre');
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
        }*/


        $data =$data->paginate(20)->withQueryString();
        return $data;
    }
    public function seasons()
    {
        return $this->hasMany(Season::class)->orderBy('season_number');
    }
    public function firstSeason()
    {
        return $this->hasOne(Season::class, 'webseries_id')->orderBy('id', 'asc');
    }
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($webseries) {

            if ($webseries->isForceDeleting()) {
                // permanent delete
                $webseries->seasons()->withTrashed()->forceDelete();
            } else {
                // soft delete
                foreach ($webseries->seasons as $season) {
                    $season->delete();
                }
            }
        });
    }

}