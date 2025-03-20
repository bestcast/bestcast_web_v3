<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use DB;
use Lib;

class UsersDevice extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;
    public $timestamps = true;
    /**
     *
     * @var Table name
     */
    public $table ='users_device';

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
        'token_id',
        'profile_id',
        'token',
        'ip_address',
        'device',
        'last_used_at'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                => 'integer',
        'user_id'           => 'integer',
        'token_id'          => 'integer',
        'profile_id'        => 'integer',
        'token'             => 'string',
        'ip_address'        => 'string',
        'device'            => 'string',
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

    public function profile()
    {
        return $this->belongsTo('App\Models\UsersProfile','profile_id','id');
    }

    public static function getByUserId($userid){
        return UsersDevice::where('user_id',$userid)->get();
    }

    public static function getByToken($token){
        return UsersDevice::where('token',md5($token))->get();
    }

    public static function getApiList($user)
    {       
        if(empty($user->id))
            return null;

        $data = UsersDevice::where('user_id',$user->id)->whereIn('token_id', function ($query) use ($user) {
                    $query->select('id')->from('personal_access_tokens')->where('tokenable_id',$user->id);
                });
        $data =$data->orderBy('updated_at','desc')->get();
        //$sql = $data->toSql();dd($sql);
        return $data;  
    }
}

