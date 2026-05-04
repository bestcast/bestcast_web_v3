<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use Lib;
use App\Models\EpisodeRelated;

class Episode extends Model
{
    use SoftDeletes;

    public $table ='episodes';

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
        'season_id',
        'episode_number',
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
        'season_id'         => 'integer',
        'episode_number'    => 'integer',
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
        return $this->hasMany('App\Models\EpisodeGenres','episode_id','id');
    }
    public function languages()
    {
        return $this->hasMany('App\Models\EpisodeLanguages','episode_id','id');
    }
    public function users()
    {
        return $this->hasMany('App\Models\EpisodeUsers','episode_id','id');
    }
    /*public function related()
    {
        return $this->hasMany('App\Models\EpisodeRelated','episode_id','id')
                ->whereHas('related', function ($q) {
                    $q->where('status', 1);
                });
    }*/
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
        return $this->hasMany('App\Models\EpisodeSubtitle','episode_id','id');
    }
    
    public function userepisodes()//$user_id, $profile_id //should not pass here
    {
        return $this->hasMany('App\Models\UsersEpisodes','episode_id','id');//->where('user_id',$user_id)->where('profile_id',$profile_id); //should not pass here
    }
    public function episode_users()
    {
        return $this->hasMany(EpisodeUsers::class, 'episode_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class);
    }

}
