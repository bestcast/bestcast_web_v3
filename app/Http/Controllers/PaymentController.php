<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Post;
use Field;
use Lib;
use Auth;
use App\Traits\HttpResponses;
use App\User;
use App\Models\Subscription;
use App\Models\Transaction;
use Email;
use Redirect;
use Razorpay\Api\Api;
use Paymentgateway;
use App\Models\Notification;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\TransactionResource;

class PaymentController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return true;
    }

    public function updatetransaction(Request $request)
    {        
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "Login session expired.", 200);

        if(empty($request->razorpay_subscription_id) && empty($request->razorpay_order_id)){
            return $this->error('', "Invalid Payment! Please try again", 200);
        }
            $razorpay_payment_id=$request->razorpay_payment_id;
            $razorpay_signature=$request->razorpay_signature;

        if(!empty($request->razorpay_order_id)){
            $razorpay_order_id=$request->razorpay_order_id;
            $trans=Transaction::getByRazorpayOrderId($user,$razorpay_order_id); 
            if(empty($trans->id)){
                return $this->error('', "Invalid Payment! Please try again code:".$request->razorpay_order_id, 200);
            }
        }
        if(!empty($request->razorpay_subscription_id)){
            $razorpay_subscription_id=$request->razorpay_subscription_id;
            $trans=Transaction::getByRazorpaySubscriptionId($user,$razorpay_subscription_id); 
            if(empty($trans->id)){
                return $this->error('', "Invalid Payment! Please try again code:".$request->razorpay_subscription_id, 200);
            }
        }

        $trans->razorpay_payment_id=$razorpay_payment_id;
        $trans->razorpay_signature=$razorpay_signature;
        $trans->status=1;
        $trans->save();

        return $this->success("Payment inprogress...");
    }


    public function test()
    {
        $razorpay = Paymentgateway::razorpay();$api_plan='';
        $api=$razorpay->order->create(
            array(
                'receipt' => '123', 
                'amount' => 100, 
                'currency' => 'INR', 
                'notes'=> array('key1'=> 'value3','key2'=> 'value2')
            )
        );
        ?>
        <form action="https://stagging.bestcast.co/payment/test" method="POST">
            <script
               src="https://checkout.razorpay.com/v1/checkout.js"
                data-key="<?php echo Paymentgateway::razorpay_value('key');?>"
                data-amount="100"
                data-currency="INR"
                data-order_id="<?php echo $api->id ?>"
                data-buttontext="Pay with Razorpay"
                data-name="Bestcast"
                data-description="Test content"
                data-image="https://stagging.bestcast.co/img/logo.png"
                data-prefill.name="Harikaran"
                data-prefill.contact="7878787878"
                data-theme.color="#F37254"
            ></script>
            <input type="hidden" custom="Hidden Element" name="hidden"/>
        </form>
        <?php
        echo "dd";die();
    }


    public function buyplan($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return redirect(url('/register'));

        $plan=Subscription::getById($id);
        if(empty($plan->id))
            return redirect(url('/pricing'));


        if(empty($plan->razorpay_id)){ //Order

        }else{ //Subscription
            $activeSubscrtion=(Subscription::getPlan());
            if(!empty($activeSubscrtion)){
                $trans=Transaction::getActive($user);
                if(!empty($trans->status)){
                    return redirect(url('/my-account'));
                }
            }
        }




        $razorpay = Paymentgateway::razorpay();$api_plan='';
        if(!empty($razorpay)){

            if(empty($plan->razorpay_id)){ //Order

                Transaction::latest()->where('user_id',$user->id)->where('subscription_id',$id)->where('status',0)->delete();

                $trans=new Transaction();
                $trans->user_id=$user->id;
                $trans->subscription_id=$id;
                $trans->status=0;
                $trans->title=$plan->title;
                $trans->price=$plan->price;
                $trans->save();

                $api=$razorpay->order->create(
                    array(
                        'receipt' => 'BESTCAST'.($trans->id+1000000000), 
                        'amount' => $plan->price*100,
                        'currency' => 'INR', 
                        'notes'=> array('transaction'=>$trans->id)
                    )
                );
                if(!empty($api->id)){
                    $trans->razorpay_order_id=$api->id;
                    $trans->razorpay_receipt=$api->receipt;
                    $trans->razorpay_currency=$api->currency;
                    $trans->razorpay_entity=$api->entity;
                    $trans->price=$api->amount/100;
                    //$trans->razorpay_data=json_decode($api);
                    $trans->save();
                }


            }else{ //Subscription

                $trans=Transaction::getPending($user,$id);
                if(empty($trans)):
                    $trans=new Transaction();
                    $trans->user_id=$user->id;
                    $trans->subscription_id=$id;
                    $trans->status=0;
                    $trans->title=$plan->title;
                    $trans->razorpay_plan_id=$plan->razorpay_id;
                    $trans->price=$plan->price;
                    $trans->save();

                    if(!empty($trans->id)){

                        $total_count=Subscription::getPlanTotalCount($plan);
                        $api=$razorpay->subscription->create(
                            array(
                                'plan_id' => $plan->razorpay_id, 
                                'customer_notify' => 1,'quantity'=>1,'total_count' =>$total_count,
                                'notes'=> array('transaction'=>$trans->id)
                            )
                        );
                        if(!empty($api->id)){
                            $trans->razorpay_subscription_id=$api->id;
                            $trans->save();
                        }
                    }
                endif;
            } //Subscription end
        }
        $trans=Transaction::getPending($user,$id);


               //dd(Subscription::getPlanTotalCount($plan));

        if(empty($trans->id))
            return redirect(url('/pricing?invalid'));

        return view('page.buyplan',['plan'=>$plan,'trans'=>$trans]);;
    }


    public function paymentstatus(Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return redirect(url('/register'));

        if(!empty($request->oid)){ //Order
            $trans=Transaction::verifyOrderPayment($user,$request->oid);
        }else if(!empty($request->sid)){ // Subscription
                $trans=Transaction::verifyPayment($user,$request->sid);
        }else{
            $trans=Transaction::getProcessing($user);
            if(!empty($trans->razorpay_subscription_id)){
                $trans=Transaction::verifyPayment($user,$trans->razorpay_subscription_id);
            }
            if(!empty($trans->razorpay_order_id)){
                $trans=Transaction::verifyOrderPayment($user,$trans->razorpay_order_id);
            }
        }
        $trans=Transaction::getActive($user);


        if(empty($plan->razorpay_id)){ //Order
        }else{ //Subscription
            $activeSubscrtion=(Subscription::getPlan());
            if(!empty($activeSubscrtion)){
                if(!empty($trans->status)){
                    return redirect(url('/my-account?reload=1'));
                }
            }
        }

        if(!empty($trans->razorpay_order_id)){ //Order
            $razorResponse=Transaction::updateOrderToUser($user,$trans->razorpay_order_id);
            if(empty($trans->notified)){
                // $trans->notified=1;
                // $trans->save();
                //writeemail here

                $Item=[
                    'user_id'       => $user->id,
                    'type'          => 'admin',
                    'title'         => 'Subscription order Submission!',
                    'content'       => 'User account '.$user->phone.' payment completed.',
                    'mark_read'     => 1,
                    'model'         => 'User',
                    'visibility'    => 0,
                    'relation_id'   => $user->id,
                    'icon'          => 1
                ];
                $Notification = Notification::create($Item);
            }
        }

        if(!empty($trans->razorpay_subscription_id)){ // Subscription
            $razorResponse=Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
            if(empty($trans->notified)){
                // $trans->notified=1;
                // $trans->save();
                //writeemail here


                $Item=[
                    'user_id'       => $user->id,
                    'type'          => 'admin',
                    'title'         => 'Subscription Submission!',
                    'content'       => 'User account '.$user->email.' payment completed.',
                    'mark_read'     => 1,
                    'model'         => 'User',
                    'visibility'    => 0,
                    'relation_id'   => $user->id,
                    'icon'          => 1
                ];
                $Notification = Notification::create($Item);
            }
        }
        return view('page.paymentstatus',['trans'=>$trans]);;
    }




    //Mobile API
    public function paymentgatewayinfo(Request $request)
    {        
        $gateway=array();
        $rpay=Paymentgateway::razorpay('core');
        $key=empty($rpay['razorpay_mode'])?$rpay['razorpay_test_key']:$rpay['razorpay_live_key'];
        $gateway['razorpay']=array(
            'enabled'=>empty($rpay['razorpay_enable'])?0:1,
            'key'=>$key,
            'title'=>$rpay['buyplan_title'],
            'content'=>$rpay['buyplan_content'],
            'logo'=>empty($rpay['razorpay_logo_urlkey'])?'':Lib::img($rpay['razorpay_logo_urlkey'])
        );
        return $this->success($gateway);
    }

    //Mobile API
    public function subscriptionlist(Request $request)
    {
        $data=Subscription::getApiList();
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return SubscriptionResource::collection($data);
    }

    //Mobile API
    public function createsubscription($id,Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "Invalid User!", 200);

        $plan=Subscription::getById($id);
        if(empty($plan->id))
            return $this->error('', "Subscription plan not available! "."#".$id, 200);


        if(empty($plan->razorpay_id)){ //Order

        }else{ //Subscription
            $activeSubscrtion=(Subscription::getPlan());
            if(!empty($activeSubscrtion)){
                $trans=Transaction::getActive($user);
                if(!empty($trans->status)){
                    return $this->error('', "Already have active subscription! "."#".$trans->id, 200);
                }
            }
        }


        $razorpay = Paymentgateway::razorpay();$api_plan='';
        if(!empty($razorpay)){

            if(empty($plan->razorpay_id)){ //Order

                Transaction::latest()->where('user_id',$user->id)->where('subscription_id',$id)->where('status',0)->delete();

                $trans=new Transaction();
                $trans->user_id=$user->id;
                $trans->subscription_id=$id;
                $trans->status=0;
                $trans->title=$plan->title;
                $trans->price=$plan->price;
                $trans->save();

                $api=$razorpay->order->create(
                    array(
                        'receipt' => 'BESTCAST'.($trans->id+1000000000), 
                        'amount' => $plan->price*100,
                        'currency' => 'INR', 
                        'notes'=> array('transaction'=>$trans->id)
                    )
                );
                if(!empty($api->id)){
                    $trans->razorpay_order_id=$api->id;
                    $trans->razorpay_receipt=$api->receipt;
                    $trans->razorpay_currency=$api->currency;
                    $trans->razorpay_entity=$api->entity;
                    $trans->price=$api->amount/100;
                    //$trans->razorpay_data=json_decode($api);
                    $trans->save();
                }


            }else{ //Subscription

                $trans=Transaction::getPending($user,$id);
                if(empty($trans)):
                    $trans=new Transaction();
                    $trans->user_id=$user->id;
                    $trans->subscription_id=$id;
                    $trans->status=0;
                    $trans->title=$plan->title;
                    $trans->razorpay_plan_id=$plan->razorpay_id;
                    $trans->price=$plan->price;
                    $trans->save();

                    if(!empty($trans->id)){

                        $total_count=Subscription::getPlanTotalCount($plan);
                        $api=$razorpay->subscription->create(
                            array(
                                'plan_id' => $plan->razorpay_id, 
                                'customer_notify' => 1,'quantity'=>1,'total_count' =>$total_count,
                                'notes'=> array('transaction'=>$trans->id)
                            )
                        );
                        if(!empty($api->id)){
                            $trans->razorpay_subscription_id=$api->id;
                            $trans->save();
                        }
                    }
                endif;
            } //Subscription end
        }
        $trans=Transaction::getPending($user,$id);


               //dd(Subscription::getPlanTotalCount($plan));

        if(empty($trans->id))
            return $this->error('', "Invalid transaction. Please try again! ", 200);

        return $this->success(new TransactionResource($trans));
    }



    //Mobile API
    public function verifypaymentstatus(Request $request)
    {



        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "Invalid User!", 200);



        if(!empty($request->oid)){ //Order
            $trans=Transaction::verifyOrderPayment($user,$request->oid);
        }else if(!empty($request->sid)){ // Subscription
                $trans=Transaction::verifyPayment($user,$request->sid);
        }else{
            $trans=Transaction::getProcessing($user);
            if(!empty($trans->razorpay_subscription_id)){
                $trans=Transaction::verifyPayment($user,$trans->razorpay_subscription_id);
            }
            if(!empty($trans->razorpay_order_id)){
                $trans=Transaction::verifyOrderPayment($user,$trans->razorpay_order_id);
            }
        }
        $trans=Transaction::getActive($user);


//return $this->success(['trans'=>$trans]);

        if(empty($plan->razorpay_id)){ //Order

        }else{ //Subscription
            $activeSubscrtion=(Subscription::getPlan());
            if(!empty($activeSubscrtion)){
                if(!empty($trans->status)){
                    //return $trans;
                    return $this->success(new TransactionResource($trans));
                }
            }
            if(empty($trans->status)){
                return $this->error('', "Invalid transaction on Verification. Please try again! ", 200);
            }
        }


        if(!empty($trans->razorpay_order_id)){ //Order
            $razorResponse=Transaction::updateOrderToUser($user,$trans->razorpay_order_id);
            if(empty($trans->notified)){
                // $trans->notified=1;
                // $trans->save();
                //writeemail here

                $Item=[
                    'user_id'       => $user->id,
                    'type'          => 'admin',
                    'title'         => 'Subscription order Submission!',
                    'content'       => 'User account '.$user->phone.' payment completed.',
                    'mark_read'     => 1,
                    'model'         => 'User',
                    'visibility'    => 0,
                    'relation_id'   => $user->id,
                    'icon'          => 1
                ];
                $Notification = Notification::create($Item);
            }
        }

        if(!empty($trans->razorpay_subscription_id)){ // Subscription
            $razorResponse=Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
            if(empty($trans->notified)){
                // $trans->notified=1;
                // $trans->save();
                //writeemail here


                $Item=[
                    'user_id'       => $user->id,
                    'type'          => 'admin',
                    'title'         => 'Subscription Submission!',
                    'content'       => 'User account '.$user->email.' payment completed.',
                    'mark_read'     => 1,
                    'model'         => 'User',
                    'visibility'    => 0,
                    'relation_id'   => $user->id,
                    'icon'          => 1
                ];
                $Notification = Notification::create($Item);
            }
        }


        return $this->success(new TransactionResource($trans));
        //return $this->success(['trans'=>$trans]);;
    }

    //Mobile API
    public function cancelmembership(Request $request)
    {
        $user=Auth::user();
        if(empty($user->id))
            return $this->error('', "Invalid User!", 200);

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
            return $this->success("Subscription Cancelled.");
        }
        return $this->error('', "No active subscription.", 200);
    }
    

}
