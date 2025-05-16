<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Auth;
use App\Models\UsersDevice;
use App\Models\Meta;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;

class User extends Authenticatable
{
    use HasApiTokens;
    use Notifiable;
    use HasRoleAndPermission;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
        'name',
        'email',
        'phone',
        'otp',
        'password',
        'title',
        'firstname',
        'lastname',
        'dob',
        'gender',
        'email_verified_at',
        'phone_verified_at',
        'photo',
        'type',
        'subscribe',
        'plan',
        'plan_expiry',
        'tvcode',
        'tvcode_status',
        'otp_message_type',
        'referal_code',
        'credits_used',
        'refferer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status'    => 'integer',
        'dob'       => 'date',
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'type'        => 'integer',
        'subscribe'   => 'integer',
        'plan'        => 'integer',
        'plan_expiry' => 'datetime'
    ];

    public static function getReferralCode($id)
    {
        return strtoupper(substr(md5($id), 0, 12));
    }
    public static function getReferralUrl($user)
    {
        $code=empty($user->referal_code)?User::getReferralCode($user->id):$user->referal_code;
        return url('/refer/'.$code);
    }
    public static function getReferralCreditsTotal($user)
    {
        $refer=Meta::refer(1,true);
        if(empty($refer['refer_credits']) && !is_numeric($refer['refer_credits']))
            return 0;

        $credit=0;
        $list=User::getReferralData($user);
        foreach($list as $item){
            if(!empty($item->plan)){
                $credit=$credit+$refer['refer_credits'];
            }
        }

        $credit=$credit-User::getReferralCreditsUsed($user);
        $credit=($credit<=0)?0:$credit;
        return $credit;
    }
    public static function getReferralCreditsUsed($user)
    {
        return empty($user->credits_used)?0:$user->credits_used;
    }
    public static function getReferralData($user)
    {
        if(!empty($user->referal_code)){
            return User::where('refferer',$user->referal_code)->get();
        }
        return false;
    }
    public static function getReferralList()
    {
        $data=User::where('plan','!=',0)->where('type',0);
        $data=$data->orderBy('created_at','asc');
        $data=$data->paginate(20)->withQueryString();
        return $data;
    }
    public static function getReferralCodeisValid($code)
    {
        if(!empty($code)){
            $val=User::where('referal_code',$code)->first();
            if(!empty($val))
                return $code;
        }
        return 'INVALID';
    }

    public static function type()
    {
        $type=[0=>'User',3=>'Producer',4=>'Director',5=>'Actor',6=>'Actress',7=>'Music Director']; //2=>'Editor',
        if(Auth::user()->isAdmin()){
            $type[2]='Admin';
        }
        return $type;
    }
    public static function getType($id=0)
    {
        $type=User::type();
        return $type[$id];
    }
    public static function getTypeRole($id=0)
    {
        $type=User::type();
        if($id==100 || empty($type[$id])){return 'NA';}
        $cast=array('Director','Actor','Actress','Music Director');
        if(in_array($type[$id],$cast)){
            return 'CAST';
        }
        return $type[$id];
    }
    public static function groupLabel()
    {
        return array(3=>'Producer',4=>'Director',5=>'Actor',6=>'Actress',7=>'Music Director');
    }
    public static function groupSlug()
    {
        return array(3=>'producer',4=>'director',5=>'actor',6=>'actress',7=>'musicdirector');
    }


    public static function getNameByID($id)
    {
        if(empty($id)){
            return null;
        }
        $user=User::find($id);
        return empty($user)?null:$user->firstname.' '.$user->lastname.' ('.$user->id.') ';
    }

    public static function generateRandomPassword($length = 10) {
        // Define characters to include in the password
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_+{}|:<>?';
        
        // Generate random password
        $password = '';
        $charsLength = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[rand(0, $charsLength - 1)];
        }
        return $password;
    }


    public static function userRequestLoginToken($user,$request=''){        

        $createToken=$user->createToken('user:'.$user->id); 

        $tokenEncryted=$createToken->plainTextToken; 
        $tokenId=$createToken->accessToken->id; 
        Session::put('setTokenEncryted', $tokenEncryted);//$accessToken=Session::get('setTokenEncryted');

        $device=[
            'user_id' => $user->id,
            'token_id' => $tokenId,
            'token' => md5($tokenEncryted),
            'ip_address' => Request::ip()
        ];
        if(!empty($request->device))
            $device['device']=urlencode($request->device);
        
        UsersDevice::create($device);

        //Old Device Logout start 
        /*$deviceCount=1;
        if(!empty($user->plan) && !empty($user->plan_expiry)){
            $deviceCount=Subscription::userGetDeviceCount($user);
            $deviceCount=empty($deviceCount)?1:$deviceCount;
        }
        $tokens=$user->tokens()->latest()->get();
        $tokens->slice($deviceCount)->each->delete(); //Delete Old Device Login Tokens.
        */ 
        //Old Device Logout end

        //Session::put('setTokenEncryted', $tokenEncryted); //not working for api login

        return [
            'user' => new UserResource($user),
            //'deviceCount' => $deviceCount,
            'token' => $tokenEncryted
        ];

    }

}
