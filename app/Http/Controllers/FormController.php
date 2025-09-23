<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Form;
use App\Models\Newsletter;
use Field;
use Lib;
use Redirect;
use Auth;
use Shortcode;
use Email;

class FormController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function contact(Request $request)
    {
        $rules = [
            'fullname' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ];
        $messages = [
            'fullname.required' => 'Please enter a valid name.',
            'email.required' => 'Please enter a valid email.',
            'email.email' => 'Entered invalid email. Please enter a valid email.',
            'subject.required' => 'Please enter a valid subject.',
            'message.required' => 'Please enter a valid message.'
        ];
        $validatedData = $request->validate($rules,$messages);


        $userid=empty(Auth::user()->id)?0:Auth::user()->id;

        $requestData = $request->all();


        $spamcheck=Shortcode::spamcheck();
        $postspamcheck=empty($requestData['spamcheck'])?'':$requestData['spamcheck'];
        if($postspamcheck!=$spamcheck){
            return Redirect::back()->with('error','Captcha failed. Please reload page and try again.');
        }

        $model = new Form();
        $model->fill($requestData);
        if(!empty($requestData['rate'])){
            $model->form ='review';
        }else{
            $model->form ='contact';
        }
        $model->user_id = $userid;
        $model->created_by = $userid;
        $model->save();

        if(!empty($requestData['rate'])){
            $sucessmsg="Review under moderation! Thanks for sharing your rating. ";
        }else{

            $mailbody='';
            $mailbody.='<b>Name:</b> '.$requestData['fullname'].'<br>';
            $mailbody.='<b>Email:</b> '.$requestData['email'].'<br>';
            $mailbody.='<b>Subject:</b> '.$requestData['subject'].'<br>';
            $mailbody.='<b>Message:</b> <br>'.$requestData['message'].'<br>';
            Email::contact(array('mailbody'=>$mailbody));

            $sucessmsg="Thank you for you submission! We will contact you soon.";
        }

        return Redirect::back()->with('success',$sucessmsg);
    }
    public function spamcheck(Request $request)
    {
        $spamcheck=Shortcode::spamcheck();
        echo $spamcheck;die();
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function newsletter(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        $messages = [
            'email.required' => 'Please enter a valid email.',
            'email.email' => 'Entered invalid email. Please enter a valid email.'
        ];
        $validatedData = $request->validate($rules,$messages);


        $userid=empty(Auth::user()->id)?0:Auth::user()->id;

        $requestData = $request->all();


        $spamcheck=Shortcode::newsletterSpamcheck();
        $postspamcheck=empty($requestData['spamcheck'])?'':$requestData['spamcheck'];
        if($postspamcheck!=$spamcheck){
            return Redirect::back()->with('error','Captcha failed. Please reload page and try again.');
        }

        $exist=Newsletter::where('email',$requestData['email'])->count();
        if($exist){
            $sucessmsg="Email already subscribed.";
            return Redirect::back()->with('error',$sucessmsg);
        }
        $model = new Newsletter();
        $model->fill($requestData);
        $model->user_id = $userid;
        $model->created_by = $userid;
        $model->save();
        $sucessmsg="Thank you for your subscription.";
        Email::newsletter(array('email'=>$requestData['email']));

        return Redirect::back()->with('success',$sucessmsg);
    }
    public function newsletterspamcheck(Request $request)
    {
        $spamcheck=Shortcode::newsletterSpamcheck();
        echo $spamcheck;die();
    }

}
