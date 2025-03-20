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
use App\Models\Post;
use App\Models\Meta;
use App\Models\UsersProfile;
use DB;
use Email;
use Otp;
use Illuminate\Support\Str;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\LoginOtpUserRequest;
use App\Http\Requests\OtpUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\EmailverifyUserRequest;
use App\Http\Resources\UserResource;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Notification;
class AuthController extends Controller
{
    use HttpResponses;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'accountlogin','sendOtp']);
        //$this->middleware('auth');
    }


    public function tokenexist(Request $request)
    {
        $token = $request->bearerToken(); 
        if (empty($token))
            $token = app('request')->input('gettoken');             

        //If token exist will proceed to login for web application
        if(!empty($token)){
             $token=explode("|",$token);
             if(!empty($token[0]) && is_numeric($token[0])){
                 $accessToken = PersonalAccessToken::where('id', $token[0])->first();
                 if(!empty($accessToken->id) && !empty($accessToken->tokenable_id)){
                    $user = User::find($accessToken->tokenable_id);
                    if(!empty($user->id)){
                       Auth::login($user);
                       //  if(!Session::has('profileToken')){
                       //    $profile=UsersProfile::getApiList($user)->first();
                       //    if(!empty($profile->id)){
                       //       Session::put('profileToken',$profile->id);
                       //    }
                       //  }
                    }
                    return $this->success('', "Token is valid",200);
                 }
             }
        }

        return $this->error('', "Token not provided!", 200);
    }


    //eg: https://domain.com/accountlogin/{tokenpasshere}
    // https://domain.com/accountlogin/79|WzKNJ7BPgPnjpZmQfLkLt3HtMMRD0NlWDH4lyIgo51c43539
    public function accountlogin($getToken,Request $request)
    {
        if(!empty($getToken)){
            $getToken=urldecode($getToken);
             $token=explode("|",$getToken);
             if(!empty($token[0]) && is_numeric($token[0])){
                 $accessToken = PersonalAccessToken::where('id', $token[0])->first();
                 if(!empty($accessToken->id) && !empty($accessToken->tokenable_id)){
                    $user = User::find($accessToken->tokenable_id);
                    if(!empty($user->id)){
                       Auth::login($user);

                       $profile=UsersProfile::getApiList($user)->first();
                       $profileId=empty($profile->id)?'':$profile->id;
                       if(!Session::has('profileToken')){
                          if(!empty($profile->id)){
                             Session::put('profileToken',$profile->id);
                          }
                       }else{
                           $profileId=Session::has('profileToken');
                       }
                       return view('myaccount.accountlogin',['token'=>$getToken,'profileid'=>$profileId,'user'=>$user]);;
                    }
                 }
             }
        }

        $post=Post::where('urlkey','page-not-found')->first();
        $meta=$post->meta->pluck('value','path');
        return view('errors.lost',['post'=>$post,'meta'=>$meta,'title'=>'Account Login expired.']);        
    }

    public function verifyemail(Request $request)
    {
        $getToken=app('request')->input('verification'); //token
        $utkn=app('request')->input('utkn'); //id
        $userToken = User::where(DB::raw('MD5(id)'), $utkn)->first();
        $lost=0;
        if(empty($userToken) || empty($getToken)){
            $lost=0;
        }else{
             $token=explode("|",$getToken);
             if(!empty($token[0]) && is_numeric($token[0])){
                 $accessToken = PersonalAccessToken::where('id', $token[0])->first();
                 if(!empty($accessToken->id) && !empty($accessToken->tokenable_id)){
                    $user = User::find($accessToken->tokenable_id);
                    if(!empty($user->id) && ($userToken->id==$user->id)){
                       Auth::login($user);
                       $user->email_verified_at=date("Y-m-d h:i:s");
                       $user->save();
                       $lost=1;
                    }
                 }
             }
        }
        if(!$lost){
            $post=Post::where('urlkey','page-not-found')->first();
            $meta=$post->meta->pluck('value','path');
            return view('errors.lost',['post'=>$post,'meta'=>$meta,'title'=>'Verification URL expired.']);
        }

        return view('myaccount.verifyemail',['token'=>$getToken,'user'=>$user]);;
    }

    public function login(LoginUserRequest $request)
    {



        try {
            // if(!empty($request->email))
            //     Session::put('formEmail', $request->email);

            $request->validated($request->only(['email', 'password']));

            $authAttempt=0;
            if(Auth::attempt($request->only(['email', 'password'])) || Auth::attempt(['phone' => $request->email, 'password' => $request->password]))
                $authAttempt=1;

            if(!$authAttempt)
                return $this->error('', "Sorry, we can't find an account with this email or phone. Please try again or create a new account.", 200);

            //Verify user data
            if(is_numeric($request->email)){
                $user = User::where('phone', $request->email)->whereNotNull('phone_verified_at')->first();
            }else{
                $user = User::where('email', $request->email)->first();
            }

            if(empty($user->id))
                return $this->error('', "Sorry, we can't find an account with this email or phone.", 200);


            if(empty($user->status))
                return $this->error('', 'Account Blocked! Please contact our admin.', 200);

            //Authendicate
            Auth::login($user);

            //Request Token
            $response=User::userRequestLoginToken($user,$request);
            
            return $this->success($response);

        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }


    public function loginbyqrcode(Request $request)
    {
        try {

            //Verify user data
            if(!empty($request->qrcode)){
                $user = User::where('tvcode', $request->qrcode)->first();
            }else{
                return $this->error('', "Sorry, we can't find an account.", 200);
            }

            if(empty($user->id))
                return $this->error('', "Sorry, we can't find an account.", 200);


            if(empty($user->status))
                return $this->error('', 'Account Blocked! Please contact our admin.', 200);

            //Authendicate
            Auth::login($user);

            $request->device='tv';

            //Request Token
            $response=User::userRequestLoginToken($user,$request);
            
            return $this->success($response);

        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }




    public function sendOtp(OtpUserRequest $request)
    {
        try {
            // if(!empty($request->email))
            //     Session::put('formEmail', $request->email);

            $request->validated($request->only(['email']));

            //Verify user 
            if(is_numeric($request->email)){
                $user = User::where('phone',$request->email)->first();
            }else{
                $user = User::where('email', $request->email)->first();
            }

            if(empty($user->id))
                return $this->error('', ''.$request->email.' not found. Please create a new account.', 200);//We could not send a sign-in code to xxx. Please use your password or try again.

            if(empty($user->status))
                return $this->error('', 'Account Blocked! Please contact our admin.', 200);
            
            //Generate OTP
            $otp=$user->otp=rand(1000,9999);
            $user->updated_at=date("Y-m-d H:i:s");
            $user->save();

            if(is_numeric($request->email)){
                //send otp via message
                Otp::otpverify($request->email,$otp);
            }else{

                //Email::otp(array('mailbody'=>'','user'=>$user,'otp'=>$otp));
                //send otp via email
            }


            $Item=[
                'user_id'       => $user->id,
                'type'          => 'admin',
                'title'         => 'OTP request',
                'content'       => 'User account '.$request->email.' requested OTP ',
                'mark_read'     => 1,
                'model'         => 'User',
                'visibility'    => 0,
                'relation_id'   => $user->id,
                'icon'          => 1
            ];
            $Notification = ($user->id==1)?'':Notification::create($Item);

            return $this->success('','Otp sent to associated Email or Phone.'); //pro_edit
        } catch (Exception $error) {
            return $this->error('Error occured while sending otp.',$error,500);
        }
    }

    public function loginWithOtp(LoginOtpUserRequest $request)
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

            //Authendicate
            Auth::login($user);
            
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

            //Request Token
            $response=User::userRequestLoginToken($user,$request);
            return $this->success($response);

            
        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }
    
    public function emailverify(EmailverifyUserRequest $request)
    {
        try {
            $request->validated($request->only(['phone']));
            //$request->validated($request->only(['email']));
            return $this->success(['message' => 'newaccount']);
        } catch (Exception $error) {
            return $this->error('Error occured while create account.',$error,500);
        }
    }

    public function register(RegisterUserRequest $request)
    {
        try {
            // if(!empty($request->email))
            //     Session::put('formEmail', $request->email);


            $rule['name']=['required', 'max:255'];
            $rule['phone']='digits_between:10,10|unique:users,phone';
            //$rule['email']=['required', 'email', 'max:255', 'unique:users,email'];
            //$rule['password']=['required', 'min:8'];

            $messages = [
                'name' => 'Please enter a valid name.',
                'password.min' => 'Password required atleast 8 character.',
                'email' => 'Please enter a valid email address.',
                'email.unique' => 'Email address is already linked to another account.',
                'phone' => 'Please enter a valid mobile number.',
                'phone.unique' => 'Mobile number is already linked to another account.',
                'phone.digits_between' => 'Please enter the 10 digit mobile number.'
            ];

            $request->validate($rule,$messages);

            //$request->validated($request->only(['email', 'password'])); //password_confirmation

            $user = User::create([
                'name' => empty($request->name)?'User':$request->name,
                'firstname' => empty($request->name)?'':$request->name,
                'email' => $request->phone."_".date("YmdHis")."@bestcast.co",//$request->email,
                'phone' => empty($request->phone)?'':$request->phone,
                'password' => Hash::make( Str::random(20)),//Hash::make($request->password),
            ]);

            if(!empty($request->refferer) && (strlen($request->refferer)<=15)){
                $user->refferer=$request->refferer;
            }
            $user->referal_code=User::getReferralCode($user->id);
            $user->save();

            if(empty($user->id))
                return $this->error('', 'Email or phone not exist!', 200);

            $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
            $user->attachRole($userRole);

            //Authendicate
            Auth::login($user);

            //Request Token
            $response=User::userRequestLoginToken($user,$request);


            if(!empty($request->device)){
                if($request->device=='mobile' || $request->device=='tv' || $request->device=='postman'){
                    $otp=$user->otp=rand(1000,9999);
                    $user->updated_at=date("Y-m-d H:i:s");
                    $user->save();
                    if(is_numeric($user->phone)){
                        Otp::otpverify($request->phone,$otp);
                    }
                }
            }
            

            $Item=[
                'user_id'       => $user->id,
                'type'          => 'admin',
                'title'         => 'New Account',
                'content'       => 'User account '.$request->email.' have registered succesfully.',
                'mark_read'     => 0,
                'model'         => 'User',
                'visibility'    => 0,
                'relation_id'   => $user->id,
                'icon'          => 1
            ];
            $Notification = ($user->id==1)?'':Notification::create($Item);


            try{
                if(!empty($response['token'])){
                   // Email::newaccount(array('mailbody'=>'','user'=>$user,'usertoken'=>$response['token']));
                }
            } catch (Exception $error) {
                
            }

            return $this->success($response);
        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }

    public function logout(Request $request)
    {
        try {
            $user = Auth::user();

            if(!empty($user->id)){
                $accessToken = $user->currentAccessToken();
                if (!empty($accessToken->id)) {
                    $token = $user->tokens()->find($accessToken->id);
                    if ($token) {
                        
                    }else{
                         $user->currentAccessToken()->delete();
                    }
                    //return $this->success('dd'.($token).'dd');
                }
            }

            $token = $request->bearerToken(); 
            if (empty($token))
                $token = app('request')->input('gettoken');

             if(!empty($token)){
                 $token=explode("|",$token);
                 if(!empty($token[0]) && is_numeric($token[0])){
                     $accessToken = PersonalAccessToken::where('id', $token[0])->first();
                     if(!empty($accessToken->id))
                        $accessToken->delete();
                 }
             }

            //return $this->success('dd'.$token.'dd');


            //Session::forget('setTokenEncryted');
            Session::forget('profileToken');

            if(!empty($user->id)){
                Auth::guard('web')->logout();
            }

            return $this->success([
                'message' => 'You have succesfully been logged out.'
            ]);
        } catch (Exception $error) {
            return $this->error('Error occured while loggin in.',$error,500);
        }
    }

    public function mobileapp($id)
    {
        $id=empty($id)?1:$id;
        return Meta::mobileapp($id,true);
    }
    public function getversion()
    {
        $ver=array("number"=>"1001");
        return (object)$ver;
    }

}



        //$createToken=$user->createToken('frontend:user')->plainTextToken;
        // return $this->success([
        //     'user' => $user,
        //     'token' => $createToken
        // ]);

        //Authendicate
        //Auth::login($user);



        // return $this->success([
        //     'message' => Auth::user()
        // ]);