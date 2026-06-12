<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;
use App\Models\Episode;
use Illuminate\Database\Eloquent\Model;


class UsersEpisodes extends Model
{
    /**
     *
     * @var Table name
     */
    public $table ='users_episodes';

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
        'user_id',
        'profile_id',
        'episode_id',
        'mylist',
        'likes',
        'watch_time',
        'watching',
        'watched_percent',
        'watched', //for producer account count when user watch movie 20min atleast
        'viewed'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'profile_id'        => 'integer',
        'episode_id'          => 'integer',
        'mylist'            => 'integer',
        'likes'             => 'integer',
        'watch_time'        => 'string',
        'watching'          => 'integer',
        'watched_percent'   => 'integer',
        'watched'           => 'integer',
        'viewed'            => 'integer',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
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


    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\UsersProfile','profile_id','id');
    }

    public function episode()
    {
        return $this->belongsTo('App\Models\Episode','episode_id','id');
    }

    public static function getUsersEpisodes($user_id,$profileid,$episodeid)
    {       
        if(empty($user_id) || empty($episodeid))
            return null;

        $data=UsersEpisodes::where('user_id',$user_id)->where('profile_id',$profileid)->where('episode_id',$episodeid)->first();
        return $data;  
    }

    public static function getApiList($user_id,$profile_id)
    {        
        $data = Episode::where('status',1);
        //For to get relation if have
        $data =$data->with(['userepisodes'=> function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
                }]);
        //For to filter by relation
        $data =$data->whereHas('userepisodes', function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
                    
                    $mylist=app('request')->input('mylist');
                    if(!empty($mylist)){
                        $query->where('mylist',1);
                    }
                    $mylist=app('request')->input('likes');
                    if(!empty($mylist)){
                        $query->where('likes',1);
                    }
                    $mylist=app('request')->input('watched');
                    if(!empty($mylist)){
                        $query->where('watched','!=',0);
                    }
                    $mylist=app('request')->input('watching');
                    if(!empty($mylist)){
                        $query->where('watching',1);
                    }
                    $mylist=app('request')->input('watch_time');
                    if(!empty($mylist)){
                        $query->where('watch_time','!=',0);
                    }
               });
        $data =$data->orderBy('updated_at','desc');

        $data =$data->paginate(60);
        return $data;
    }


    public static function getEpisode($user_id,$profile_id,$episodeid)
    {       
        if(empty($user_id) || empty($episodeid))
            return null;

        $data = Episode::where('status',1);//->first();

        //For to get relation if have
        $data =$data->with(['userepisodes'=> function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
                }]);
        //For to filter by relation
        $data =$data->whereHas('userepisodes', function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
               });

        $data =$data->where('id',$episodeid)->first();
        return $data;  
    }


    /*public static function getProducerMovieCount($movieid)
    {       
        if(empty($movieid))
            return 0;

        $data = UsersMovies::where('movie_id',$movieid)->where('watched','>=',3600)->count(); //1 hour
        return $data;  
    }*/

}

