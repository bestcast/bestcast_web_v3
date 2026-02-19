<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    /*protected $dates = [
        'created_at',
        'updated_at',
    ];*/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'thumbnail_id',
        'medium_id',
        'image_id',
        'portrait_id',
        'portraitsmall_id',
        'status',
        'movie_access'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'title'             => 'string',
        'thumbnail_id'      => 'integer',
        'medium_id'         => 'integer',
        'image_id'          => 'integer',
        'portrait_id'       => 'integer',
        'portraitsmall_id'  => 'integer',
        'status'            => 'string',
        'movie_access'      => 'integer',
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
        'title' => 'required|max:1000'
    ];


    public static $messages = [
        'title.required' => 'Title is required.'
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

    /*public function genres()
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
    }*/
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
    /*public function subtitle()
    {
        return $this->hasMany('App\Models\MoviesSubtitle','movie_id','id');
    }
    
    public function usermovies()//$user_id, $profile_id //should not pass here
    {
        return $this->hasMany('App\Models\UsersMovies','movie_id','id');//->where('user_id',$user_id)->where('profile_id',$profile_id); //should not pass here
    }*/


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
        return $this->hasMany(Season::class);
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
