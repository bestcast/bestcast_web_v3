<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\UrlGenerator;
use HelpLib;
use App\User;
use App\Models\CoreConfig;
use App\Models\Appointment;
use App\Models\Subscription;
use Illuminate\Support\Str;
use URL;
use Illuminate\Support\Facades\Mail;
class Email {

    public static function adminemail($core){
        $adminemail=empty($core['email_notify_admin'])?'noreply@'.env('APP_HOST'):$core['email_notify_admin'];
        $adminemail=explode(",",$adminemail);
        $adminemail=array_unique(array_filter($adminemail));
        return $adminemail;
    }

    public static function supportemail($core){
        $adminemail=empty($core['email_support_admin'])?'noreply@'.env('APP_HOST'):$core['email_support_admin'];
        return $adminemail;
    }

	public static function test($arr) {
        $core = CoreConfig::list();

	   /* $mailbody='Now that you’ve signed up with '.env('APP_NAME').', we’ll send you monthly emails over the next month to help you get hands on.';

	    $to_name = env('APP_NAME');
	    $to_email ='exdomain0@gmail.com';
	    $subject ='Welcome to '.env('APP_NAME').'!';
	    $maildata = array(
	        'title'=>'Welcome to '.env('APP_NAME'),
	        'subtitle'=>'Thank you',
	        'shortdesc'=>'Non-emergent medical, nursing, rehab care  on a daily basis.<br>We brings the doctor’s office right to your home. ',
	        'body' => $mailbody
	    );          
	    Mail::send('mail.newaccount',["maildata"=>$maildata],function($message) use ($to_name, $to_email,$subject,$core) {

            $adminemail=Email::adminemail($core);
                
	        $message->to($to_email, $to_name);
            foreach($adminemail as $bccemail){
                $message->bcc($bccemail,env('APP_NAME'));
            }
	        $message->subject($subject);
	        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
	    });*/

	}

	public static function contact($arr) {
        $core = CoreConfig::list();
        $adminemail=Email::adminemail($core);
		$arr['mailbody']=empty($arr['mailbody'])?'':$arr['mailbody'];
		
        $to_name = env('APP_NAME');
        $to_email ='hello@moviesapp.com';
        $subject =env('APP_NAME').' Contact Form Submission';
        $maildata = array(
            'title'=>'Contact Us',
            'subtitle'=>'Form Submission as Follows:',
            'shortdesc'=>'Description goes here.',
            'body' => $arr['mailbody']
        );          
        Mail::send('mail.contact',["maildata"=>$maildata],function($message) use ($to_name, $to_email,$subject,$core) {
            $adminemail=Email::adminemail($core);
            $message->to($to_email, $to_name)->subject($subject);
            foreach($adminemail as $bccemail){
                $message->bcc($bccemail,env('APP_NAME'));
            }
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
	}



	public static function newaccount($arr) {
        $user=empty($arr['user'])?'':$arr['user'];
        $usertoken=empty($arr['usertoken'])?'':$arr['usertoken'];
		if(empty($user->email) || empty($usertoken)){
			return null;
		}
        $core = CoreConfig::list();
        $adminemail=Email::adminemail($core);

        $mailbody='Welcome! We’re glad to have you. Finish signing up to watch today’s latest films.<br>';
		$mailbody=empty($arr['mailbody'])?$mailbody:$arr['mailbody'];

        $mailbody.='<br><br><div style="width:100%;text-align: center;"><a href="'.url('/verifyemail').'?utkn='.md5($user->id).'&verification='.$usertoken.'" target="_blank" style="width:200px;padding:10px;background:#cc1e24;color:#FFF;display: inline-block; text-align: center; text-decoration: none; text-transform: uppercase;">Finish Signing Up</a></div><br><br>';
        //$mailbody.='<br><b>Name:</b> '.$user->title." ".$user->firstname." ".$user->middlename." ".$user->lastname;
        //$mailbody.='<br><b>Email:</b> '.$user->email;
        //if(!empty($user->phone)){$mailbody.='<br><b>Phone:</b> '.$user->phone;}

        $to_name = env('APP_NAME');
        $to_email =$user->email;
        $subject ='Welcome to '.env('APP_NAME').'!';
        $maildata = array(
            'title'=>'Welcome',
            'subtitle'=>'Finish signing up to watch',
            'shortdesc'=>'',
            'body' => $mailbody
        );         
        $x=Mail::send('mail.newaccount',["maildata"=>$maildata],function($message) use ($to_name, $to_email,$subject,$core) {
            $adminemail=Email::adminemail($core);
            $message->to($to_email, $to_name);
            foreach($adminemail as $bccemail){
                $message->bcc($bccemail,env('APP_NAME'));
            }
            $message->subject($subject);
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
	}


    public static function otp($arr) {
        $user=empty($arr['user'])?'':$arr['user'];
        $otp=empty($arr['otp'])?'':$arr['otp'];
        if(empty($user->email) || empty($otp)){
            return null;
        }
        $core = CoreConfig::list();
        $adminemail=Email::adminemail($core);
        $supportemail=Email::supportemail($core);

        $mailbody='';
        $mailbody=empty($arr['mailbody'])?$mailbody:$arr['mailbody'];

        $mailbody.='Dear '.$user->firstname.'<br><br>Your One Time Password(OTP) is :<br><br><div style="width:100%;text-align: center;"><div style="width:200px;padding:10px;background:#cc1e24;color:#FFF;display: inline-block; text-align: center; text-decoration: none; text-transform: uppercase;font-size:20px;">'.$otp.'</div></div><br><br>';
        $mailbody.='Your OTP will expire in 15 mins. <br><br>Do not share your OTP with anyone.<br> For any OTP related query please email us at '.$supportemail.'.';

        $to_name = env('APP_NAME');
        $to_email =$user->email;
        $subject ='Transaction OTP';
        $maildata = array(
            'title'=>'',
            'subtitle'=>'',
            'shortdesc'=>'',
            'body' => $mailbody
        );         
        Mail::send('mail.otp',["maildata"=>$maildata],function($message) use ($to_name, $to_email,$subject,$core) {
            $adminemail=Email::adminemail($core);
            $message->to($to_email, $to_name);
            foreach($adminemail as $bccemail){
                $message->bcc($bccemail,env('APP_NAME'));
            }
            $message->subject($subject);
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        });
    }


	public static function newsletter($arr) {
		$email=empty($arr['email'])?'':$arr['email'];
		if(empty($email)){
			return null;
		}
        $core = CoreConfig::list();

        $mailbody='We look forward to you becoming a customer.<br>';

        $to_name = env('APP_NAME');
        $to_email =$email;
        $subject ='Welcome to '.env('APP_NAME').'!';
        $maildata = array(
            'title'=>'Welcome to '.env('APP_NAME'),
            'subtitle'=>'Thanks for your Newsletter Subscription.',
            'shortdesc'=>'Description goes here.',
            'body' => $mailbody
        );          
        // Mail::send('mail.newsletter',["maildata"=>$maildata],function($message) use ($to_name, $to_email,$subject,$core) {
        //     $adminemail=Email::adminemail($core);
        //     $message->to($to_email, $to_name);
        //     foreach($adminemail as $bccemail){
        //         $message->bcc($bccemail,env('APP_NAME'));
        //     }
        //     $message->subject($subject);
        //     $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
        // });
	}



}

