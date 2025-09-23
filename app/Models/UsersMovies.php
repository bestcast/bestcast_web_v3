<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;
use App\Models\Movies;

class UsersMovies extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;
    public $timestamps = true;
    /**
     *
     * @var Table name
     */
    public $table ='users_movies';

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
        'movie_id',
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
        'movie_id'          => 'integer',
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

    public function movies()
    {
        return $this->belongsTo('App\Models\Movies','movie_id','id');
    }

    /*public static function getApiList($user,$profileid)
    {       
        if(empty($user->id))
            return null;

        $data = UsersMovies::with('movies')->where('profile_id',$profileid)->where('user_id',$user->id)->latest();
        $data =$data->orderBy('updated_at','desc')->get();
        return $data;  
    }*/

    public static function getUsersMovies($user_id,$profileid,$movieid)
    {       
        if(empty($user_id) || empty($movieid))
            return null;

        $data=UsersMovies::where('user_id',$user_id)->where('profile_id',$profileid)->where('movie_id',$movieid)->first();
        return $data;  
    }

    public static function getApiList($user_id,$profile_id)
    {        
        $data = Movies::where('status',1);
        //For to get relation if have
        $data =$data->with(['usermovies'=> function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
                }]);
        //For to filter by relation
        $data =$data->whereHas('usermovies', function ($query) use ($user_id, $profile_id) {
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


    public static function getMovie($user_id,$profile_id,$movieid)
    {       
        if(empty($user_id) || empty($movieid))
            return null;

        $data = Movies::where('status',1);//->first();

        //For to get relation if have
        $data =$data->with(['usermovies'=> function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
                }]);
        //For to filter by relation
        $data =$data->whereHas('usermovies', function ($query) use ($user_id, $profile_id) {
                    $query->where('user_id', $user_id);
                    $query->where('profile_id', $profile_id);
               });

        $data =$data->where('id',$movieid)->first();
        return $data;  
    }


    public static function getProducerMovieCount($movieid)
    {       
        if(empty($movieid))
            return 0;

        $data = UsersMovies::where('movie_id',$movieid)->where('watched','>=',1200)->count(); //20mins
        return $data;  
    }

}

