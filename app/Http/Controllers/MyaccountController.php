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
use App\Models\UsersMovies;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovieReportExport;

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
    public function movieReport(Request $request, $movieId)
    {
        $query = DB::table('users_movies as um')
            ->join('movies as m', 'm.id', '=', 'um.movie_id')
            ->where('um.movie_id', $movieId)
            ->whereRaw('CAST(um.watch_time AS UNSIGNED) >= 60')

            ->selectRaw("
                um.user_id,
                um.watch_time,
                um.created_at,
                um.updated_at,

                CAST(m.duration AS UNSIGNED) as duration_seconds,

                CASE
                    WHEN CAST(um.watch_time AS UNSIGNED) >= CAST(m.duration AS UNSIGNED)
                    THEN 100

                    ELSE ROUND(
                        (
                            CAST(um.watch_time AS UNSIGNED)
                            / CAST(m.duration AS UNSIGNED)
                        ) * 100,
                        2
                    )
                END as watch_percentage,

                CASE
                    -- Movie >= 1 hour
                    WHEN CAST(m.duration AS UNSIGNED) >= 3600
                         AND CAST(um.watch_time AS UNSIGNED) >= 3600
                    THEN 1

                    -- Movie < 1 hour (90% rule)
                    WHEN CAST(m.duration AS UNSIGNED) < 3600
                         AND (
                            (CAST(um.watch_time AS UNSIGNED) / CAST(m.duration AS UNSIGNED)) * 100
                         ) >= 90
                    THEN 1

                    ELSE 0
                END as is_view
            ");

        // Filter using updated_at
        if ($request->from_date) {
            $query->whereDate('um.created_at', '>=', $request->from_date);
        }

        if ($request->to_date) {
            $query->whereDate('um.updated_at', '<=', $request->to_date);
        }

        // Pagination
        $data = $query->orderByDesc('um.updated_at')->paginate(15);

        // Get stats
        $stats = \App\Models\UsersMovies::getProducerMovieCount($movieId);

        return response()->json([
            'data' => $data->items(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'per_page' => $data->perPage(),
            'views_count' => $stats['views_count']
        ]);
    }
    public function downloadReport(Request $request, $movieId)
    {
        $stats = UsersMovies::getProducerMovieCount($movieId);

        return Excel::download(
            new MovieReportExport($movieId, $request->from_date, $request->to_date),
            'movie_report_' . now()->timestamp . '.xlsx'
        );
    }
}