<?php
namespace App\Http\Controllers\Api;

use App\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Lib;
use Redirect;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use App\Models\Movies;
use App\Models\Appnotify;
use App\Models\Profileicon;
use App\Models\UsersProfile;
use App\Models\UsersDevice;
use App\Models\UsersMovies;
use App\Models\Subscription;
use App\Http\Resources\ProfileiconResource;
use App\Http\Resources\UserprofileResource;
use App\Http\Resources\UserdeviceResource;
use App\Http\Resources\UsermoviesResource;
use App\Http\Resources\MoviesResource;
use App\Http\Resources\MoviesTimeResource;
use App\Http\Resources\MoviesListResource;
use App\Http\Resources\AppnotifyResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\QrcodeResource;
use App\Http\Requests\LoginOtpUserRequest;
use Email;
use Otp;

use Laravel\Sanctum\PersonalAccessToken;
class UserController extends Controller
{
    use HttpResponses;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return $this->success([
            'user' => new UserResource(Auth::user())
        ]);
    }


    public function deleteuseraccount(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return redirect()->route('user.myaccount.profile')->with('error', 'Account not Records Found!');

        if($user->hasRole('user')==false)
            return redirect()->route('user.myaccount.profile')->with('error', 'Invalid Role, User account only allowed to delete.');

        Session::forget('profileToken');
        Auth::guard('web')->logout();

        $user=User::find($user->id);
        $user->delete();


        $accessToken = PersonalAccessToken::where('tokenable_id', $user->id)->get();
        if(!empty($accessToken) && count($accessToken)){
           foreach($accessToken as $token){
                $token->delete();
           }
        }

        return redirect()->route('login')->with('error', 'Account information Deleted Successfully.');
    }

    public function deleteuser(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "Account Records Found!", 200);

        if($user->hasRole('user')==false)
            return $this->error('', "Invalid Role, User account only allowed to delete.", 200);

        Session::forget('profileToken');
        Auth::guard('web')->logout();

        $user=User::find($user->id);
        $user->delete();


        $accessToken = PersonalAccessToken::where('tokenable_id', $user->id)->get();
        if(!empty($accessToken) && count($accessToken)){
           foreach($accessToken as $token){
                $token->delete();
           }
        }

        return $this->success('', "Account information Deleted Successfully", 200);
    }

    public function updateuser(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        $user=User::find($user->id);
        $user->firstname=empty($request->firstname)?$user->firstname:$request->firstname;
        $user->lastname=empty($request->lastname)?$user->lastname:$request->lastname;
        $user->name=$user->firstname." ".$user->lastname;

        if(!empty($user->phone) && (is_numeric($request->phone) && strlen($request->phone)==10)){
            if($user->phone!=$request->phone){
                $user->phone_verified_at=null;
            }
        }
        $user->phone=(is_numeric($request->phone) && strlen($request->phone)==10)?$request->phone:$user->phone;
        $user->save();


        $usersProfile=UsersProfile::getApiList($user)->first();
        if(!empty($usersProfile->id)){
            if(empty($usersProfile->name) || $usersProfile->name=='My Profile'){
                $usersProfile->name=$user->firstname;
                $usersProfile->save();
            }
        }else{
            $usersProfile=new UsersProfile();
            $usersProfile->user_id=$user->id;
            $usersProfile->profileicon_id=1;
            $usersProfile->name=empty($user->name)?'My Profile':$user->name;
            $usersProfile->save();
        }

        return new UserResource($user);
    }


    public function userprofilelist(Request $request)
    {        
        $user=Auth::user();
        $data=UsersProfile::getApiList($user);

        if(empty($data) || !count($data)){
            $usersProfile=new UsersProfile();
            $usersProfile->user_id=$user->id;
            $usersProfile->profileicon_id=1;
            $usersProfile->name=empty($user->name)?'My Profile':$user->name;
            $usersProfile->save();
            $data=UsersProfile::getApiList($user);
        }

        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return UserprofileResource::collection($data);
    }

    public function getuserprofile($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);

        if(empty($usersProfile))
            return $this->error('', "No Records Found!", 200);

        return new UserprofileResource($usersProfile);

    }

    public function deleteuserprofile($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);
        if(empty($usersProfile))
            return $this->error('', "No Records Found!", 200);

        $total=UsersProfile::where('user_id',$user->id)->count();
        if($total>1){
            $usersProfile->delete();
        }

        return $this->success('', "Deleted Successfully", 200);

    }

    public function userprofilepinverify($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id) || empty($request->pin))
            return $this->error('', "Please enter a valid PIN.", 200);

        $usersProfile=UsersProfile::where('user_id',$user->id)->where('pin',$request->pin)->find($id);
        if(empty($usersProfile))
            return $this->error('', "Please enter a valid PIN.", 200);

        return new UserprofileResource($usersProfile);
    }


    public function userprofileresetpin($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id) || empty($request->password) || empty($request->pin))
            return $this->error('', "Invalid credential! #ERR001", 200);

        $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);
        if(empty($usersProfile))
            return $this->error('', "Invalid Profile. Please reload the page.", 200);

        if (Hash::check($request->password, $user->password)){
            $usersProfile->pin=$request->pin;
            $usersProfile->save();
            return new UserprofileResource($usersProfile);
        }else{
            return $this->error('', "Invalid credential! #ERR002", 200);
        }
        
    }

    public function setuserprofile($id=0,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        if(empty($id)){
            $usersProfile=new UsersProfile();
            $usersProfile->user_id=$user->id;
            $usersProfile->profileicon_id=empty($request->profileicon_id)?1:$request->profileicon_id;
            $usersProfile->name=empty($request->name)?$user->firstname:$request->name;
            $usersProfile->save();
        }else{
            $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);
        }
        
        if(empty($usersProfile))
            return $this->error('', "No Records Found!", 200);

        $usersProfile->name=isset($request->name)?$request->name:$usersProfile->name;
        $usersProfile->language=isset($request->language)?$request->language:$usersProfile->language;
        $usersProfile->autoplay=isset($request->autoplay)?$request->autoplay:$usersProfile->autoplay;
        $usersProfile->is_child=isset($request->is_child)?$request->is_child:$usersProfile->is_child;
        $usersProfile->pin=isset($request->pin)?$request->pin:$usersProfile->pin;
        $usersProfile->profileicon_id=isset($request->profileicon_id)?$request->profileicon_id:$usersProfile->profileicon_id;
        $usersProfile->appnotify=empty($usersProfile->appnotify)?date("Y-m-d h:i:s", strtotime('+12 hours')):$usersProfile->appnotify;
        $usersProfile->appnotify=isset($request->appnotify)?date("Y-m-d h:i:s", strtotime('+12 hours')):$usersProfile->appnotify;
        $usersProfile->last_login=date("Y-m-d h:i:s");
        $usersProfile->save();
        return new UserprofileResource($usersProfile);
    }

    public function userdevicelist(Request $request)
    {
        $user=Auth::user();
        $data=UsersDevice::getApiList($user);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return UserdeviceResource::collection($data);
    }
    public function setuserdevice(Request $request)
    {
        $user=Auth::user();
        $token = $request->bearerToken();
        if (empty($token))
            $token = app('request')->input('gettoken'); 
        
        if(empty($token))
            return $this->error('', "Invalid Token ID", 200);

        $token=explode("|",$token);
        if(empty($token[0]) || !is_numeric($token[0]))
            return $this->error('', "Invalid Token ID", 200);

        $usersDevice=UsersDevice::find($token[0]);
        if(empty($usersDevice))
            return $this->error('', "No Records Found!", 200);

        $usersDevice->profile_id=isset($request->profile_id)?$request->profile_id:$usersDevice->profile_id;
        $usersDevice->device=isset($request->device)?$request->device:$usersDevice->device;
        $usersDevice->ip_address=isset($request->ip_address)?$request->ip_address:$usersDevice->ip_address;
        $usersDevice->last_login=date("Y-m-d h:i:s");
        $usersDevice->save();

        return new UserdeviceResource($usersDevice);
    }

    public function profileiconlist(Request $request)
    {
        $data=Profileicon::getApiList();
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return ProfileiconResource::collection($data);
    }


    public function usermovieslist(Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        if(empty($user->id) || empty($request->profile_id))
            return $this->error('', "No Records Found!", 200);

        $data=UsersMovies::getApiList($user->id,$request->profile_id); 
        if(empty($data))
            return $this->error('', "No Records Found!", 200);
        return MoviesListResource::collection($data);

        // $data=UsersMovies::getApiList($user,$request->profile_id); //chng001
        // if(empty($data))
        //     return $this->error('', "No Records Found!", 200);

        // return UsermoviesResource::collection($data);
    }

    public function getusermovie($movieid,Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        if(empty($user->id) || empty($movieid) || empty($request->profile_id))
            return $this->error('', "No Records Found!", 200);

        $data=UsersMovies::getMovie($user->id,$request->profile_id,$movieid);
        if(empty($data)){
            $usersMovies=new UsersMovies();
            $usersMovies->user_id=$user->id;
            $usersMovies->profile_id=$request->profile_id;
            $usersMovies->movie_id=$movieid;
            $usersMovies->viewed=1;
            $usersMovies->save();
            $data=UsersMovies::getMovie($user->id,$request->profile_id,$movieid);
        }
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return new MoviesResource($data);
        //return new UsermoviesResource($data);
    }

    public function setusermovie($movieid,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id) || empty($request->profile_id))
            return $this->error('', "No Records Found!", 200);

        $usersMovies=UsersMovies::getUsersMovies($user->id,$request->profile_id,$movieid);
        if(empty($usersMovies)){
            $usersMovies=new UsersMovies();
            $usersMovies->user_id=$user->id;
            $usersMovies->profile_id=$request->profile_id;
            $usersMovies->movie_id=$movieid;

            $usersMovies->mylist=0;
            $usersMovies->likes=0;
            $usersMovies->watch_time=0;
            $usersMovies->watching=0;
            $usersMovies->watched_percent=0;
            $usersMovies->watched=0;
            $usersMovies->viewed=0;
        }

        $usersMovies->mylist=isset($request->mylist)?$request->mylist:$usersMovies->mylist;
        $usersMovies->likes=isset($request->likes)?$request->likes:$usersMovies->likes;
        $usersMovies->watch_time=isset($request->watch_time)?$request->watch_time:$usersMovies->watch_time;
        $usersMovies->watching=isset($request->watching)?$request->watching:$usersMovies->watching;
        $usersMovies->watched_percent=isset($request->watched_percent)?$request->watched_percent:$usersMovies->watched_percent;
        $usersMovies->watched=isset($request->watched)?$request->watched:$usersMovies->watched;
        $usersMovies->viewed=isset($request->viewed)?$request->viewed:$usersMovies->viewed;
        $usersMovies->save();
        
        $usersMovies=UsersMovies::getMovie($user->id,$request->profile_id,$movieid);
        return new MoviesListResource($usersMovies);
        //return new UsermoviesResource($usersMovies);
    }

    public function getusermovietime($movieid,Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        if(empty($user->id) || empty($movieid) || empty($request->profile_id))
            return $this->error('', "No Records Found!", 200);

        $data=UsersMovies::getMovie($user->id,$request->profile_id,$movieid);
        if(empty($data)){
            $usersMovies=new UsersMovies();
            $usersMovies->user_id=$user->id;
            $usersMovies->profile_id=$request->profile_id;
            $usersMovies->movie_id=$movieid;
            $usersMovies->viewed=1;
            $usersMovies->save();
            $data=UsersMovies::getMovie($user->id,$request->profile_id,$movieid);
        }
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return new MoviesTimeResource($data);
        //return new UsermoviesResource($data);
    }

    public function appnotifylist($id,Request $request) //profileid
    { 
        $user=Auth::user();
        $usersProfile=UsersProfile::where('user_id',$user->id)->find($id);
        if(empty($user->id) || empty($usersProfile->id))
            return $this->error('', "No Records Found!", 200);

        $data=Appnotify::getApiList($usersProfile);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return AppnotifyResource::collection($data);
    }

    
    public function lostlogin($id)
    {
        $accessToken = PersonalAccessToken::where('id', $id)->first();
        if(!empty($accessToken->id) && !empty($accessToken->tokenable_id)){
            $user = User::find($accessToken->tokenable_id);
            if(empty($user->id)){
                Auth::guard('web')->logout();
                Session::forget('profileToken');
            }
        }else{
            Auth::guard('web')->logout();
            Session::forget('profileToken');
        }
        return view('errors.lostlogin');
    }

    public function tokenexist(Request $request)
    {
        $token = $request->bearerToken(); 
        if (empty($token))
            $token = app('request')->input('gettoken'); 

            //return $this->error('', $accessToken, 200);

        //If token exist will proceed to login for web application
        if(!empty($token)){
             $token=explode("|",$token);
             if(!empty($token[0]) && is_numeric($token[0])){
                 $accessToken = PersonalAccessToken::where('id', $token[0])->first();
                 if(!empty($accessToken->id) && !empty($accessToken->tokenable_id)){
                    $user = User::find($accessToken->tokenable_id);
                    if(!empty($user->id)){
                        if(!Session::has('profileToken')){
                          $profile=UsersProfile::getApiList($user)->first();
                          if(!empty($profile->id)){
                             Session::put('profileToken',$profile->id);
                          }
                        }
                    }
                    return $this->success('', "Token is valid",200);
                 }
             }
        }

        Auth::guard('web')->logout();
        if(!Session::has('profileToken')){
            Session::forget('profileToken');
        }
        return $this->error('', "Token not provided!", 200);
    }



    public function getqrcode(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        $user=User::find($user->id);
        return new QrcodeResource($user);
    }
    public function setqrcode(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "No Records Found!", 200);

        $user=User::find($user->id);
        $user->tvcode=empty($request->qrcode)?'':$request->qrcode;
        $user->tvcode_status=empty($request->status)?0:$request->status;
        $user->save();

        $user=User::find($user->id);
        return new QrcodeResource($user);
    }

    

    public function sendappotp(Request $request)
    {
        $user=Auth::user();
        $user = User::find($user->id);
        $otp=$user->otp=rand(1000,9999);
        $user->save();
        Otp::otpverify($user->phone,$otp);
        return $this->success($user);
    }

    public function verifyappotp(Request $request)
    {
        if(empty($request->otp)){
            return $this->error('', 'Otp empty!', 200);
        }
        $user=Auth::user();
        $user = User::find($user->id);

        if($request->otp==$user->otp){
            $user->phone_verified_at=date("Y-m-d H:i:s");
            $user->otp='';
            $user->save();
            return $this->success($user);
        }else{
            return $this->error('', 'Please enter a valid OTP.', 200);
        }
    }


    public function loginWithOtp()
    {
        if(isset($_GET['send'])){
            $user=Auth::user();
            $user = User::find($user->id);
            $otp=$user->otp=rand(1000,9999);
            $user->save();

            if(isset($_GET['phone'])){
                Otp::otpverify($user->phone,$otp);
                sleep(1);
                return redirect()->route('otp.verification', ['phone' => 1]);
            }else{
                Email::otp(array('mailbody'=>'','user'=>$user,'otp'=>$otp));
                return redirect()->route('otp.verification');
            }
        }

        return view('auth.loginotp');
    }


    public function postloginWithOtp(LoginOtpUserRequest $request)
    {
        try {
            // if(!empty($request->email))
            //     Session::put('formEmail', $request->email);

            $request->validated($request->only(['email', 'otp']));


            //Verify user 
            if(is_numeric($request->email)){
                $user = User::where('phone', $request->email)->where('otp', $request->otp)->first();//->whereNotNull('phone_verified_at')
            }else{
                $user = User::where('email', $request->email)->where('otp', $request->otp)->first();
            }

            if(empty($user->id))
                return $this->error('', 'Incorrect code. Please try again.', 200);

            if(empty($user->status))
                return $this->error('', 'Account Blocked! Please contact our admin.', 200);

            if(empty($user->updated_at) || ($user->updated_at->addMinutes(15) < now()))
                return $this->error('', 'OTP expired! Please try Resend again.', 200);

            
            //Reset OTP to null
            $user->otp=null;
            if(is_numeric($request->email)){
                if(empty($user->phone_verified_at)){
                    $user->phone_verified_at=date("Y-m-d H:i:s");
                }
            }else{
                if(empty($user->email_verified_at)){
                    $user->email_verified_at=date("Y-m-d H:i:s");
                }
            }
            $user->save();

            return $this->success('updatedotp');

            
        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }

}

