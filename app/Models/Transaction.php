<?php

namespace App\Models;

use jeremykenedy\LaravelRoles\Contracts\RoleHasRelations as RoleHasRelationsContract;
use jeremykenedy\LaravelRoles\Database\Database;
use jeremykenedy\LaravelRoles\Traits\RoleHasRelations;
use jeremykenedy\LaravelRoles\Traits\Slugable;
use App\Models\Media;
use App\Models\CoreConfig;
use App\User;
use Lib;
use Auth;
use Paymentgateway;
use DB;

class Transaction extends Database implements RoleHasRelationsContract
{
    use RoleHasRelations;
    use Slugable;

    /**
     *
     * @var Table name
     */
    public $table ='transaction';

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
        'subscription_id',
        'status', //0-pending, 1-processing, 2-completed, 3-cancelled, 4-failed, 5-refund
        'title',
        'razorpay_plan_id',
        'razorpay_subscription_id',
        'razorpay_payment_id',
        'razorpay_offer_id',
        'razorpay_signature',
        'price',
        'counts',
        'notified'
    ];

    /**
     * Typecast for protection.
     *
     * @var array
     */
    protected $casts = [
        'id'                        => 'integer',
        'user_id'                   => 'integer',
        'subscription_id'           => 'integer',
        'status'                    => 'string',
        'title'                     => 'string',
        'razorpay_plan_id'          => 'string',
        'razorpay_subscription_id'  => 'string',
        'razorpay_payment_id'       => 'string',
        'razorpay_offer_id'         => 'string',
        'price'                     => 'float',
        'counts'                    => 'integer',
        'notified'                  => 'integer',
        'created_at'                => 'datetime',
        'updated_at'                => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;


    /**
     * Create a new model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function status($val)
    {
        $ar=['0' => 'Pending', '1' => 'Processing', '2' => 'Completed', '3' => 'Cancelled', '4' => 'Failed', '5' => 'Refunded'];
        return empty($ar[$val])?$ar[0]:$ar[$val];;
    }

    public static function getRevenueTotal()
    {
        $sum = Transaction::selectRaw('SUM(price * counts) AS total')->where('status','=',2)->first()->total;
        return $sum;
    }


    public static function getRevenueMonthList()
    {
        $sumByMonth = Transaction::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(price * counts) AS total')
                        ->where('status','=',2)
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->get();
        return $sumByMonth;
    }
    public static function getUserList()
    {
        $sumByMonth = User::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(status) AS total')
                        ->where('status','=',1)->where('type','=',0)
                        ->groupBy(DB::raw('YEAR(created_at), MONTH(created_at)'))
                        ->get();
        return $sumByMonth;
    }



    public static function getList()
    {
        $data = Transaction::latest()->where('status','!=',0);
        if(!empty($_GET['user_id'])){
            $data =$data->where('user_id',$_GET['user_id']);
        }
        $data =$data->orderBy('created_at','desc');
        $data =$data->paginate(20);
        return $data;
    }

    public static function getPending($user,$planid)
    {
        //Transaction::latest()->where('user_id',$user->id)->where('subscription_id',$planid)->where('status',0)->whereNotNull('razorpay_order_id')->where('razorpay_order_id', '!=', '')->delete();
        return Transaction::latest()->where('user_id',$user->id)->where('subscription_id',$planid)->where('status',0)->first();
    }
    public static function getByRazorpayOrderId($user,$razorpay_order_id)
    {
        return Transaction::latest()->where('user_id',$user->id)->where('razorpay_order_id',$razorpay_order_id)->first();
    }
    public static function verifyOrderPayment($user,$oid)
    {
        $trans=Transaction::getByRazorpayOrderId($user,$oid);
        if(!empty($trans)){
            if($trans->status==1 && !empty($trans->razorpay_order_id) && !empty($trans->razorpay_payment_id) && !empty($trans->razorpay_signature)){

                $sig=hash_hmac('sha256', $trans->razorpay_order_id."|".$trans->razorpay_payment_id,Paymentgateway::razorpay_value('secret'));
                if ($sig == $trans->razorpay_signature) {
                    $trans->status=2;
                    $trans->counts=$trans->counts+1;
                    $trans->save();
                }else{
                    $trans->status=4;
                    $trans->save();
                }
            }
        }  
        return $trans; 
    }
    public static function getByRazorpaySubscriptionId($user,$razorpay_subscription_id)
    {
        return Transaction::latest()->where('user_id',$user->id)->where('razorpay_subscription_id',$razorpay_subscription_id)->first();
    }
    public static function verifyPayment($user,$sid)
    {
        $trans=Transaction::getByRazorpaySubscriptionId($user,$sid);
        if(!empty($trans)){
            if($trans->status==1 && !empty($trans->razorpay_subscription_id) && !empty($trans->razorpay_payment_id) && !empty($trans->razorpay_signature)){

                $sig=hash_hmac('sha256', $trans->razorpay_payment_id."|".$trans->razorpay_subscription_id,Paymentgateway::razorpay_value('secret'));
                if ($sig == $trans->razorpay_signature) {
                    $trans->status=2;
                    $trans->counts=$trans->counts+1;
                    $trans->save();
                }else{
                    $trans->status=4;
                    $trans->save();
                }
            }
        }  
        return $trans; 
    }
    public static function updatePlanToUser($user,$sid)
    {
        $trans=Transaction::getByRazorpaySubscriptionId($user,$sid);
        $res='';
        if(!empty($trans)){
            if($trans->razorpay_subscription_id==$sid){
                try{
                    $api=Paymentgateway::razorpay();
                    // if(empty($api->subscription)){
                    //     return $res; 
                    // }
                    $res=$api->subscription->fetch($trans->razorpay_subscription_id);
                    if(!empty($res->status) && $res->status=='active'){
                        if(!empty($res->current_end)){
                            if(strtotime($user->plan_expiry)<$res->current_end){
                                $user->plan_expiry=date("Y-m-d h:i:s",$res->current_end);
                            }
                            $user->plan=$trans->subscription_id;
                            $user->save();

                            $trans->counts=$res->total_count-$res->remaining_count;
                            $trans->save();
                        }
                    }
                }catch (\Razorpay\Api\Errors\BadRequestError $e) {
                }catch(Exception $error){

                }
                //dd(date("Y-m-d h:i:s",$x->current_end));
            }
        }  
        return $res; 
    }
    public static function updateOrderToUser($user,$oid)
    {
        $trans=Transaction::getByRazorpayOrderId($user,$oid);
        $res='';
        if(!empty($trans)){
            if($trans->razorpay_order_id==$oid){
                try{
                    $api=Paymentgateway::razorpay();
                    // if(empty($api->order)){
                    //     return $res; 
                    // }
                    $res=$api->order->fetch($trans->razorpay_order_id);
                    if(!empty($res->status) && $res->status=='paid'){

                        $subscription=Subscription::find($trans->subscription_id);
                        $dayscount=Subscription::getDurationValue($subscription);
                        $setexpiredate = date("Y-m-d h:i:s", strtotime("+" . $dayscount . " days"));
                        if(strtotime($user->plan_expiry)<strtotime($setexpiredate)){
                            $user->plan_expiry=$setexpiredate;
                        }
                        $user->plan=$trans->subscription_id;
                        $user->save();
                    }
                }catch (\Razorpay\Api\Errors\BadRequestError $e) {
                }catch(Exception $error){

                }
                //dd(date("Y-m-d h:i:s",$x->current_end));
            }
        }  
        return $res; 
    }
    public static function getProcessing($user)
    {
        return Transaction::latest()->where('user_id',$user->id)->where('status',1)->first();
    }
    public static function getActive($user)
    {
        $data=Transaction::latest()->where('user_id',$user->id)->where('status',2)->first();
        if(empty($data)){
          $data=Transaction::latest()->where('user_id',$user->id)->where('status',1)->first();  
        }
        return $data;
    }
}
