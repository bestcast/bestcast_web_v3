<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use Auth;
use URL;
use Lib;
use App\User;
use App\Rules\Checkdate;
use Illuminate\Support\Facades\Hash;
use DB;
use App\Models\UsersDevice;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\Meta;
use App\Models\Movies;
use Email;
use Paymentgateway;
use App\Traits\HttpResponses;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\Notification;

class MyaccountController extends Controller
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

    public function index()
    {

        $user=auth()->user();
        //check payment and update to user plan start
        if(!empty($user)){
            $trans=Transaction::getActive($user);
            if(!empty($trans->razorpay_subscription_id)){
                $razorResponse=Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
            }
        }
        //check payment and update to user plan end
        
        if(!empty($_GET['reload'])){
            return redirect(url('/my-account'));
        }

        $user=User::find(Auth::user()->id);
        return view('myaccount.index',['user'=>$user]);
    }
    public function membership()
    {
        $user=Auth::user();
        return view('myaccount.membership',['user'=>$user]);
    }
    public function devices()
    {
        $user=Auth::user();
        // Email::newaccount(array('mailbody'=>'','user'=>$user,'usertoken'=>1111));
        // die();
        $device=UsersDevice::getApiList($user);

        $message='';
        $plan=Subscription::getPlan();
        if(!empty($device) && !empty($plan->device_limit)){
            if(count($device)>$plan->device_limit){
                $message='Device Limit Reached! Maximum allowed: '.$plan->device_limit.'. Signout existing devices any to continue!';
            }
        }

        return view('myaccount.devices',['user'=>$user,'device'=>$device,'message'=>$message]);
    }
    public function profiles()
    {
        $user=Auth::user();
        return view('myaccount.profiles',['user'=>$user]);
    }
    public function referfriend()
    {
        $user=Auth::user();

        if(!empty($user->id) && empty($user->referal_code)){
           $user->referal_code=User::getReferralCode($user->id);
           $user->save();
        }

        $refer=Meta::refer(1,true);
        return view('myaccount.referfriend',['user'=>$user,'refer'=>$refer]);
    }
    public function profile()
    {
        $user=User::find(Auth::user()->id);
        return view('myaccount.profile',['user'=>$user]);
    }
    public function producer()
    {
        $user=Auth::user();
        $movies=Movies::getProducerList($user->id);
        return view('myaccount.producer',['user'=>$user,'movies'=>$movies]);
    }
    public function logoutuser($id)
    {
        $user=Auth::user();
        if (empty($user))
            return $this->error('', $id,200);

        $accessToken=PersonalAccessToken::where('id', $id)->where('tokenable_id', $user->id)->first();
        if(!empty($accessToken->id))
            $accessToken->delete();

        return $this->success($id);
    }
    public function cancelmembership(Request $request)
    {
        $user=Auth::user();
        $trans=Transaction::getActive($user);
        if(!empty($trans->status) && !empty($trans->razorpay_subscription_id) && $trans->status==2){
            $api=Paymentgateway::razorpay();
            $fetech=$api->subscription->fetch($trans->razorpay_subscription_id);
            //dd($fetech);
            if($fetech->status=='active'){
                $fetech=$fetech->cancel(array('cancel_at_cycle_end'=>0)); //0-immediate, 1-end of cycle
            }
            //writeemail here
            if(!empty($fetech->status) && $fetech->status=='cancelled'){
                $trans->status=3;
                $trans->save();

                $Item=[
                    'user_id'       => $user->id,
                    'type'          => 'admin',
                    'title'         => 'Subscription Cancelled!',
                    'content'       => 'User account '.$user->email.' have cancelled subscription.',
                    'mark_read'     => 1,
                    'model'         => 'User',
                    'visibility'    => 0,
                    'relation_id'   => $user->id,
                    'icon'          => 1
                ];
                $Notification = Notification::create($Item);
            }
        }
        return redirect(url('/my-account'));
    }
    public function profileSave(Request $request)
    {

        $requestData = $request->all();
        $user=$tempuser=User::find(Auth::user()->id);
        $rule['firstname']=['required'];
        $rule['gender']=['required'];
        if(!empty($requestData['oldpassword']) || !empty($requestData['password']) || !empty($requestData['password_confirmation'])){
            $rule['password']=['required', 'min:8', 'confirmed'];
        }
        $rule['dob']=['required',new Checkdate()];
        $rule['email']=['required', 'email', 'max:255', 'unique:users,email,'.$user->id];
        $rule['phone']='digits_between:10,10|unique:users,phone,'.$user->id;

        if(!empty($requestData['oldpassword']) && !empty($requestData['password'])){
            if(!Hash::check($requestData['oldpassword'], $user->password)){
                $rule['password']=['different:password'];
            }
        }

        $messages = [
            'required' => 'The :attribute field is required.',
            'password.min' => 'Password required atleast 8 character.',
            'password.different' => 'Incorrect Old Password.',
            'confirmed' => 'Your confirmation password not same.',
            'email' => 'Please enter a valid email address.',
            'email.unique' => 'Email address is already linked to another account.',
            'phone' => 'Please enter a valid mobile number.',
            'phone.unique' => 'Mobile number is already linked to another account.',
            'phone.digits_between' => 'Please enter the 10 digit mobile number.'
        ];

        $request->validate($rule,$messages);

        if($requestData['email']!=$tempuser->email){
            $user->email_verified_at=null;
        }
        if($requestData['phone']!=$tempuser->phone){
            $user->phone_verified_at=null;
        }
        
        $user->title=$requestData['title'];
        $user->firstname=$requestData['firstname'];
        $user->email=$requestData['email'];
        $user->phone=$requestData['phone']; // should need to check for already exist or not.
        //$user->middlename=$requestData['middlename'];


        $user->lastname=$requestData['lastname'];
        $user->dob=Lib::dateFormat($requestData['dob'],'d/m/Y','Y-m-d');
        $user->gender=$requestData['gender'];
        $user->subscribe=empty($requestData['subscribe'])?0:1;

        if(!empty($requestData['oldpassword']) && !empty($requestData['password'])){
            if(Hash::check($requestData['oldpassword'], $user->password)){
                $user->password=Hash::make($requestData['password']);
            }
        }

        $user->save();
        if ($user->isAdmin()){
            return redirect()->route('admin.myaccount.profile')->with('success', 'Updated Successfully');
        }else{
            return redirect()->route('user.myaccount.profile')->with('success', 'Updated Successfully');
        }
    }
}
