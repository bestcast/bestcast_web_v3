<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Subscription;
use Auth;
use Field;
use Lib;
use Paymentgateway;


class SubscriptionController extends Controller
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

    public function list(Request $request)
    {
        $data = Subscription::getList();
        return $data;
    }

    public function index()
    {
        $data = Subscription::getList();
        return view('admin.subscription.index', ['model'=>$data]);
    }

    public function create()
    {
        return view('admin.subscription.create');
    }

    public function createsave(Request $request)
    {
        $request['urlkey']=Lib::slug($request['title']."-".time());
        $validatedData = $request->validate(Subscription::$rules,Subscription::$messages);
        
        $requestData = $request->all();
        $model = new Subscription();
        $requestData['status']=empty($requestData['status'])?0:1;
        $model->fill($requestData);
        $model->created_by = Auth::user()->id;
        $model->save();
        return redirect()->route('admin.subscription.edit', $model->id)->with('success', 'Created Successfully');
    }

    public function edit($id)
    {
        $data = Subscription::find($id);
        if(empty($data)){
            return redirect()->route('admin.subscription.index')->with('error', 'Record does not exist! ID:'.$id);
        }
        return view('admin.subscription.edit', ['model'=>$data]);
    }


    public function editsave($id,Request $request)
    {
        if($id){
            $model = Subscription::find($id);
                $rules = Subscription::$rules;$rules['urlkey'] = $rules['urlkey'] . ','.$id.",id";
                $request['urlkey']=Lib::slug($request['urlkey']);
                $requestData = $request->all();
                $validatedData = $request->validate($rules,Subscription::$messages);

            $requestData['status']=empty($requestData['status'])?0:1;
            $requestData['duration']=empty($requestData['duration'])?1:$requestData['duration'];
            $model->fill($requestData);
            $model->updated_by = Auth::user()->id;
            $model->save();

            //dd($model);

            if(!empty($requestData['razorpay_generate']) && $requestData['razorpay_generate']=='YES'){
                //Create Razor Plan
                $api = Paymentgateway::razorpay();$api_plan='';
                if(!empty($api)){

                    $period='daily';  
                    if($model->duration_type==1){                   $period='monthly';      }
                    else if ($model->duration_type==2) {            $period='yearly';       }
                    else if ($model->duration_type==3) {            $period='weekly';       }
                    else if ($model->duration_type==4) {            $period='quarterly';    }

                    $api_plan=$api->plan->create(
                            array(
                                'period' => $period, 
                                'interval' => $model->duration, 
                                'item' => array(
                                    'name' => $model->title, 
                                    'description' => $model->title.' '.$period, 
                                    'amount' => $model->price*100, 
                                    'currency' => 'INR'
                                ),
                                'notes'=> array('plan'=>$model->id)
                            )
                        );
                }

                if(!empty($api_plan->id)){
                    $model = Subscription::find($id);
                    $model->razorpay_id = $api_plan->id;
                    $model->save();
                }
            }


            if(!empty($requestData['razorpay_generate']) && $requestData['razorpay_generate']=='CLEAR'){
                $model = Subscription::find($id);
                $model->razorpay_id = '';
                $model->save();
            }

            return redirect()->route('admin.subscription.edit',$id)->with('success', 'Updated Successfully');
        }
        return redirect()->route('admin.subscription.edit',$id)->with('error', 'Failed to Update! Please try again!');
    }

    public function delete($id)
    {
        if($id){
            $model = Subscription::find($id);
            if(!empty($model)){
                $model->delete();
                return redirect()->route('admin.subscription.index')->with('success', 'Deleted Successfully');
            }
        }
        return redirect()->route('admin.subscription.index')->with('error', 'Deletion Failed! Please try again!');
    }


}
