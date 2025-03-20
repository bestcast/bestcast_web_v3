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
class Otp {


	public static function registration($arr) {
		echo 'registration';die();
		// $user=empty($arr['user'])?'':$arr['user'];
		// $phone=empty($user->phone)?'':$user->phone;
		// if(empty($phone)){
		// 	return null;
		// }

		// $contactnumber='7777788888';
		// $name=$user->firstname.' '.$user->lastname;
        // $templateid='1007777788888';
        // $sender='ALERTS';
        // $key='a3c0d56a0349e4fe9cf1178c6000acce';
        // $route='4';
        // $sms=urlencode('Greetings, Mrs. '.$name.' message  '.$contactnumber.' immediately.');
        // $otpapiurl='http://site.ping4sms.com/api/smsapi?key='.$key.'&route='.$route.'&sender='.$sender.'&number='.$phone.'&sms='.$sms.'&templateid='.$templateid;
        // $otpresponse=file_get_contents($otpapiurl);

        return $otpresponse;

	}

	public static function otpverify($phone,$otpnumber) {
		if(empty($phone) || empty($phone)){
			return null;
		}

        $templateid='1707171195592259541';
        $sender='BesMet';
        $key='a3c0d56a0349e4fe9cf1178c6000acce';
        $route='2';
        $sms=urlencode('To Verify your Mobile number for Bestcast OTT, OTP is :'.$otpnumber.'-Bestcast Metaverse Limited.');
        $otpapiurl='http://site.ping4sms.com/api/smsapi?key='.$key.'&route='.$route.'&sender='.$sender.'&number='.$phone.'&sms='.$sms.'&templateid='.$templateid;

        //http://site.ping4sms.com/api/smsapi?key=a3c0d56a0349e4fe9cf1178c6000acce&route=2&sender=BesMet&number=7871917804&sms=To%20Verify%20your%20Mobile%20number%20for%20Bestcast%20OTT%2C%20OTP%20is%20%3A1234-Bestcast%20Metaverse%20Limited.&templateid=1707171195592259541

        $otpresponse=file_get_contents($otpapiurl);

        return $otpresponse;

	}

}

