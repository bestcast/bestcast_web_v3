<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;
use Auth;
use App\Http\Resources\UserprofileResource;

class UsersProfile extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;
    public $timestamps = true;
    /**
     *
     * @var Table name
     */
    public $table ='users_profile';

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
        'profileicon_id',
        'name',
        'language',
        'autoplay',
        'is_child',
        'pin',
        'appnotify',
        'last_login'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'name'              => 'string',
        'profileicon_id'    => 'integer',
        'language'          => 'integer',
        'autoplay'          => 'integer',
        'is_child'          => 'integer',
        'pin'               => 'integer',
        'appnotify'         => 'datetime',
        'last_login'        => 'datetime',
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
    public function profileicon()
    {
        return $this->belongsTo('App\Models\Profileicon','profileicon_id','id');
    }

    public static function getByUserId($userid){
        return UsersProfile::where('user_id',$userid)->get();
    }


    public static function getApiList($user)
    {       
        if(empty($user->id))
            return null;

        $data = UsersProfile::with('profileicon')->where('user_id',$user->id)->latest();//with('genres','languages')->
        $data =$data->orderBy('name','asc')->get();
        return $data;  
    }

    public static function getData($id){
        $user=Auth::user();
        $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);

        // Dont enable this, have issue, it create many
        // if(empty($usersProfile)){
        //     $usersProfile=new UsersProfile();
        //     $usersProfile->user_id=$user->id;
        //     $usersProfile->profileicon_id=1;
        //     $usersProfile->name=empty($user->firstname)?'User':$user->firstname;
        //     $usersProfile->save();
        // }

        if(empty($usersProfile))
            return null;
        
        return new UserprofileResource($usersProfile);
    }

    public static function getList(){
        $user=Auth::user();
        $data=UsersProfile::getApiList($user);
        if(empty($data))
            return null;
        
        return UserprofileResource::collection($data);
    }

    public static function getImage($usersProfile){
        if(!empty($usersProfile->profileicon) && !empty($usersProfile->profileicon->thumbnail) && !empty($usersProfile->profileicon->thumbnail->urlkey)){
            return $usersProfile->profileicon->thumbnail->urlkey;
        }
        return null;
    }
}

