<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use App\User;
use App\Rules\Checkdate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Auth;
use Field;
use URL;
use Lib;
use DB;
use Email;
use App\Models\Meta;
use App\Models\Notification;
use App\Models\UsersProfile;
use App\Models\Profileicon;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function list(Request $request,$type=0)
    {
        if ($request->ajax()) {
            $data = User::latest()->where('type',$type)->where('phone','!=','')->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->editColumn('roles', function($data) {
                    $html='NA';
                    if(!empty($data->roles)){
                        $html='';
                        foreach($data->roles as $role){
                            $html.='<span class="badge bg-secondary text-light">'.$role->name.'</span> ';
                        }
                    }
                    return $html;
                })
                ->editColumn('created_at', function($data) {
                    return date("d/m/y h:i a",strtotime($data->created_at));
                })
                ->addColumn('action', 'admin.user.action')
                ->escapeColumns([])
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function searchcastbyname($key='ZXP')
    {
        if(empty($key)){ return null;}
        $data = User::select("id","name as text")->where('name','like',"%".urldecode($key)."%")->where('type','!=',0)->where('type','!=',1)->where('type','!=',2)->where('type','!=',100)->latest()->take(20)->get();
        return $data;
    }

    public function index()
    {
        return view('admin.user.index', ['title'=>'Users','type'=>0]);
    }
    public function editor()
    {
        return view('admin.user.index', ['title'=>'Editor','type'=>2]);
    }
    public function producer()
    {
        return view('admin.user.index', ['title'=>'Producer','type'=>3]);
    }
    public function director()
    {
        return view('admin.user.index', ['title'=>'Director','type'=>4]);
    }
    public function actor()
    {
        return view('admin.user.index', ['title'=>'Actor','type'=>5]);
    }
    public function actress()
    {
        return view('admin.user.index', ['title'=>'Actress','type'=>6]);
    }
    public function musicdirector()
    {
        return view('admin.user.index', ['title'=>'Music Director','type'=>7]);
    }
    public function admin()
    {
        return view('admin.user.index', ['title'=>'Admin','type'=>1]);
    }
    public function view($id)
    {
        return view('admin.user.index');
    }

    public function create()
    {
        return view('admin.user.create');
    }


    public function createsave(Request $request)
    {

        $rule['firstname']=['required'];
        $rule['email']=['required', 'email', 'max:255', 'unique:users'];
        //$rule['password']=['required', 'min:8', 'confirmed'];

        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid :attribute.',
            'unique' => 'Email address already exist.',
            'max' => 'Maximum limit for :attribute reached.',
            // 'password.min' => 'Password required atleast 8 character.',
            // 'password.different' => 'Incorrect Old Password.',
            // 'confirmed' => 'Your confirmation password not same.'
        ];

        $request->validate($rule,$messages);

        
        $requestData = $request->all();

        $user=User::create([
            'email' => $requestData['email'],
            'password' => Hash::make(User::generateRandomPassword()),
            'name' => $requestData['firstname']." ".$requestData['lastname'],
            'firstname' => $requestData['firstname'],
            'lastname' => $requestData['lastname'],
            'type' => $requestData['type']
        ]);


        if(empty($requestData['phone'])){
            $user->phone=1000000000+$user->id;
            $user->save();
        }


        if(Auth::user()->isAdmin() || Auth::user()->isSubadmin()){
            $type=empty($requestData['type'])?'User':User::getTypeRole($requestData['type']);
            $userRole = config('roles.models.role')::where('name', '=', $type)->first();
            $user->attachRole($userRole);
        }

        if(!empty($user->id)){
            $profileicon=Profileicon::first();
            UsersProfile::create([
                'user_id'  => $user->id,
                'name'     => $user->firstname,
                'profileicon_id'    => $profileicon->id
            ]);
        }

        // $user=User::find($user->id);
        // $user->firstname=$requestData['firstname'];
        // $user->lastname=$requestData['lastname'];
        // $user->save();

        //Email::newaccount(array('mailbody'=>'','user'=>$user));

        return redirect()->route('admin.user.edit', $user->id);
    }


    public function edit($id)
    {
        $user = User::find($id);
        if(empty($user)){
            return redirect()->route('admin.user.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        if($user->isAdmin() && !Auth::user()->isAdmin()){
            return view('errors.404');
        }
        $meta = Meta::user($id,true);
        return view('admin.user.edit', ['model'=>$user,'meta'=>$meta]);
    }
    public function editsave($id,Request $request)
    {
        $requestData = $request->all();
        $user=User::find($id);

        if($user->isAdmin() && !Auth::user()->isAdmin()){
            return view('errors.404');
        }

        if(empty($user)){
            return redirect()->route('admin.user.index')->with('error', 'Invalid Profile. ID:'.$id);
        }
        $rule['firstname']=['required'];
        $rule['email']=['required', 'email', 'max:255', 'unique:users,email,'.$id];
        $rule['phone']='digits_between:10,10|unique:users,phone,'.$id;
        if(!empty($requestData['password']) || !empty($requestData['password_confirmation'])){
            $rule['password']=['required', 'min:8', 'confirmed'];
        }
        $rule['dob']=[new Checkdate()];
        
        $messages = [
            'required' => 'The :attribute field is required.',
            'email' => 'Please enter a valid :attribute.',
            'email.unique' => 'Email address is already linked to another account.',
            'phone' => 'Please enter a valid :attribute.',
            'phone.unique' => 'Mobile number is already linked to another account.',
            'max' => 'Maximum limit for :attribute reached.',
            'phone.digits_between' => 'Please enter the 10 digit mobile number.',
            'password.min' => 'Password required atleast 8 character.',
            'password.different' => 'Incorrect Old Password.',
            'confirmed' => 'Your confirmation password not same.'
        ];

        $request->validate($rule,$messages);

        $user->email=$requestData['email'];
        $user->title=$requestData['title'];
        $user->firstname=$requestData['firstname'];
        $user->lastname=$requestData['lastname'];
        $user->name=$requestData['firstname']." ".$requestData['lastname'];
        $user->dob=Lib::dateFormat($requestData['dob'],'d/m/Y','Y-m-d');
        $user->gender=$requestData['gender'];
        $user->phone=$requestData['phone'];
        $user->refferer=$requestData['refferer'];
        $user->referal_code=empty($requestData['referal_code'])?User::getReferralCode($user->id):$requestData['referal_code'];
        $user->credits_used=empty($requestData['credits_used'])?0:$requestData['credits_used'];
        $user->subscribe=empty($requestData['subscribe'])?0:1;
        $user->email_verified_at=empty($requestData['email_verify'])?null:date("Y-m-d h:i:s");
        $user->phone_verified_at=empty($requestData['phone_verify'])?null:date("Y-m-d h:i:s");

        $user->plan=$requestData['plan'];
        if($requestData['plan_expiry']!=$user->plan_expiry)
            $user->plan_expiry=Lib::dateFormat($requestData['plan_expiry'],'d/m/Y','Y-m-d 23:59:59');

        $user->phone_verified_at=empty($requestData['phone_verify'])?null:date("Y-m-d h:i:s");
        if(!empty($requestData['password'])){
            $user->password=Hash::make($requestData['password']);

            $notify_content='User '.$user->email.' Login password changed.';
            Notification::addAdmin('Password change alert!',$notify_content,['model'=>'User','relation_id'=>$user->id]);
        }

                
        if(isset($requestData['type'])){    
            if(Auth::user()->isAdmin()){
                $user->type=$requestData['type']; 

                $notify_content='User '.$user->email.' Role type changed ('.User::getType($requestData['type']).').';
                Notification::addAdmin('Role change alert!',$notify_content,['mark_read'=>1,'model'=>'User','relation_id'=>$user->id]);
            }
        }


        if(!empty($request->photo_file)){
            $filepath="media/profile/".$user->type."/".$user->id."/";
            $uploadfn=Lib::SavePublicMedia($request,'photo',$filepath);
            if(!empty($uploadfn)){$user->photo=$uploadfn;}
        }

        $user->save();

        if(isset($requestData['type'])){
            if(Auth::user()->isAdmin()){
                $type=empty($requestData['type'])?'User':User::getTypeRole($requestData['type']);
                $userRole = config('roles.models.role')::where('name', '=', $type)->first();
                $user->detachAllRoles();
                $user->attachRole($userRole);
            }
        }


        if(!empty($requestData['meta']) && count($requestData['meta'])){
            foreach($requestData['meta'] as $path=>$value){
                //$meta = Meta::where('meta_id',$id)->where('path',$path)->where('type',1)->first();
                $meta = Meta::page($id)->where('path',$path)->first();
                if(empty($meta->id)){
                    $meta = new Meta();
                    $meta->user_id = Auth::user()->id;
                    $meta->meta_id = $user->id;
                    $meta->path = $path;
                    $meta->type = 3; //3 - user
                }
                $meta->value = $value;
                $meta->save();
            }
        }

        return redirect()->route('admin.user.edit',$id)->with('success', 'Updated Successfully');
    }

    public function delete($id)
    {
        $model = User::find($id);
        if(!empty($model)){
            $model->delete();
        }
        return redirect()->route('admin.user.index')->with('success', 'Deleted Successfully');
    }


}
