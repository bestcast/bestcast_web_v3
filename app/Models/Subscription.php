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

class Subscription extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='subscription';

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
        'before_price',
        'price',
        'content',
        'video_quality',
        'video_resolution',
        'video_device',
        'video_sametime',
        'device_limit',
        'duration',
        'duration_type', //0-day, 1- month, 2- year, 3-weekly, 4- quartely
        'tagtext',
        'sortorder',
        'razorpay_id',
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
        'before_price'      => 'float',
        'price'             => 'float',
        'content'           => 'string',
        'video_quality'     => 'integer',
        'video_resolution'  => 'integer',
        'video_device'      => 'integer',
        'video_sametime'    => 'integer',
        'device_limit'      => 'integer',
        'duration'          => 'integer',
        'duration_type'     => 'integer',
        'tagtext'           => 'string',
        'sortorder'         => 'integer',
        'razorpay_id'       => 'string',
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
        'urlkey' => 'required|max:1000|unique:subscription,urlkey'
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
    public function duration($val)
    {
        $ar=Subscription::durationValues();
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }
    public static function durationValues()
    {
        return array(0=>"Day",1=>"Month",2=>"Year");
    }
    public static function qualityValues()
    {
        return array(0=>"Fair",1=>"Good",2=>"Great",3=>"Best");
    }
    public static function resolutionValues()
    {
        return array(0=>"480p",1=>"720p",2=>"1080p",3=>"4k");
    }
    public static function deviceValues()
    {
        return array(0=>"Mobile, tablet",1=>"TV, computer, Mobile , Tablet",2=>"TV, computer, Mobile , Tablet",3=>"TV, computer, Mobile , Tablet");
    }
    public function getDuration($model)
    {
        $year=365;
        $month=28;
        if(empty($model->duration)){
            return $month;
        }
        if(empty($model->duration_type)){
            return $model->duration;
        }
        if($model->duration_type==1){
            return $model->duration*$month;
        }
        if($model->duration_type==2){
            return $model->duration*$year;
        }
        return $month;
    }
    public static function getPlanTotalCount($model)
    {
        $year=6;  //razorpay maximum 5 years
        $month=70;
        $day=2000;
        if(empty($model->duration)){
            $duration=1;
        }else{
            $duration=$model->duration;
        }
        if(empty($model->duration_type)){
            return ceil($day/$duration);
        }else{
            if($model->duration_type==1){
                return ceil($month/$duration);
            }
            if($model->duration_type==2){
                return ceil($year/$duration);
            }
        }
        return ceil($month/$duration);
    }
    public static function getDurationText($model)
    {
        $year=365;
        $month=28;
        if(empty($model->duration)){
            return 'month';
        }
        if(empty($model->duration_type)){
            $text=($model->duration==1)?'':$model->duration.' ';
            $plural=($model->duration==1)?'':'s';
            return $text.'day'.$plural;
        }
        if($model->duration_type==1){
            $text=($model->duration==1)?'':$model->duration.' ';
            $plural=($model->duration==1)?'':'s';
            return $text.'month'.$plural;
        }
        if($model->duration_type==2){
            $text=($model->duration==1)?'':$model->duration.' ';
            $plural=($model->duration==1)?'':'s';
            return $text.'year'.$plural;
        }
        return 'month';
    }
    public static function getDurationValue($model)
    {
        $year=365;
        $month=28;
        if(empty($model->duration)){
            return $month;
        }
        if(empty($model->duration_type)){
            return $model->duration;
        }
        if($model->duration_type==1){
            return $model->duration*$month;
        }
        if($model->duration_type==2){
            return $model->duration*$year;
        }
        return $month;
    }


    public static function getList()
    {
        $data = Subscription::latest();
        $data =$data->orderBy('sortorder','desc');
        $data =$data->paginate(20);
        return $data;
    }


    public static function getApiList()
    {
        $data = Subscription::latest()->where('status',1);
        //$data = $data->whereNotNull('razorpay_id')->where('razorpay_id', '!=', '');
        $data =$data->orderBy('sortorder','desc');
        $data =$data->paginate(20);
        return $data;
    }

    public static function planList()
    {
        $type = Subscription::latest()->where('status',1);
        //$type = $type->whereNotNull('razorpay_id')->where('razorpay_id', '!=', '');
        $type = $type->pluck('title','id');
        $type[0]='No Active Plan';
        return $type;
    }

    public static function userGetDeviceCount($user){
        if(empty($user->plan) || empty($user->plan_expiry)  || ($user->plan_expiry < date('Y-m-d H:i:s', strtotime('-12 hours')))){
            return 0;
        }
        $data = Subscription::where('id',$user->plan)->where('status',1)->first();
        return empty($data->device_limit)?0:$data->device_limit;
    }

    public static function getById($id)
    {
        $type = Subscription::latest()->where('status',1);
        //$type = $type->whereNotNull('razorpay_id')->where('razorpay_id', '!=', '');
        $type = $type->where('id',$id)->first();
        return $type;
    }

    public static function getPlan(){
        //return true;
        $user=Auth::user();
        if(empty($user->plan) || empty($user->plan_expiry)  || ($user->plan_expiry < date('Y-m-d H:i:s', strtotime('-12 hours')))){
            return 0;
        }
        $subscription = Subscription::where('id',$user->plan)->where('status',1)->first();
        return empty($subscription->id)?0:$subscription;
    }
}
