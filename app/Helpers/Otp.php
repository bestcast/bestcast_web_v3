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
use Http;
use Log;

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

	public static function otpverify($phone,$otpnumber,$otp_message_type,$country_code) {
		if(empty($phone) || empty($phone)){
			return null;
		}
		if($otp_message_type == "whatsapp"){
			$postData = ['messaging_product' => 'whatsapp',
							"recipient_type" => "individual",
							'to' => $country_code.$phone,
							'type' => 'template',
							'template' => ['name' => 'login',
			    				'language' => ['code' => 'en'],
							    'components' => [
							    	[
								    	'type' => 'body',
								    	'parameters' => [[
								    		'type' => 'text',
								    		'text' => $otpnumber
								    	]]
								    ],
								    [
								    	'type' => 'button',
								    	'sub_type' => 'url',
								    	'index' => '0',
								    	'parameters' => [[
								    		'type' => 'text',
								    		'text' => $otpnumber
								    	]]
								    ]
							    ]
							]
						];
			$response = Http::withHeaders([
						'Authorization' => 'Bearer '.config('keys.WHATSAPP_TOKEN'),
						'Content-Type' => 'application/json'
			])->post('https://graph.facebook.com/v22.0/'.config('keys.WHATSAPP_PHONE_ID').'/messages', $postData);
			if($response->status() == 200){
				$message = ['status_code' => 200, 'message' => 'User SIGNIN Sucessfully'];
			}else{
				Log::info($response);
				$message = ['status_code' => $response->status(), 'message' => 'There is some issue to generate OTP. Plese wait'];
			}
			return $message;
		}
		else{
			$templateid=config('keys.SMS_TEMPLATEID');
        $sender='BesMet';
	        $key=config('keys.SMS_KEY');
	        $route=config('keys.SMS_ROUTE');
        $sms=urlencode('To Verify your Mobile number for Bestcast OTT, OTP is :'.$otpnumber.'-Bestcast Metaverse Limited.');
        $otpapiurl='http://site.ping4sms.com/api/smsapi?key='.$key.'&route='.$route.'&sender='.$sender.'&number='.$phone.'&sms='.$sms.'&templateid='.$templateid;
        	$otpresponse=file_get_contents($otpapiurl);
        	return $otpresponse;
		}

        //http://site.ping4sms.com/api/smsapi?key=a3c0d56a0349e4fe9cf1178c6000acce&route=2&sender=BesMet&number=7871917804&sms=To%20Verify%20your%20Mobile%20number%20for%20Bestcast%20OTT%2C%20OTP%20is%20%3A1234-Bestcast%20Metaverse%20Limited.&templateid=1707171195592259541
	}

}

