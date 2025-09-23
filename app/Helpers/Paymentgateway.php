<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\UrlGenerator;
use HelpLib;
use App\User;
use App\Models\Meta;
use App\Models\CoreConfig;
use Illuminate\Support\Str;
use URL;
use Illuminate\Support\Facades\Mail;
use Razorpay\Api\Api;
class Paymentgateway {

    public static function razorpay($get='') {$api='';
        $razormeta = Meta::paymentgateway(1,true);
        if($get=='core'){
            return $razormeta;
        }
        if(!empty($razormeta) && !empty($razormeta['razorpay_enable'])){

            if(empty($razormeta['razorpay_mode'])){ //test
                $api_key=$razormeta['razorpay_test_key'];
                $api_secret=$razormeta['razorpay_test_secret'];
            }else{ //live
                $api_key=$razormeta['razorpay_live_key'];
                $api_secret=$razormeta['razorpay_live_secret'];
            }
            if(!empty($api_key) && !empty($api_secret)){
                $api = new Api($api_key, $api_secret);
            }
        }
        return $api;
    }

    //Paymentgateway::razorpay_value('secret');
    public static function razorpay_value($get='') {$api='';
        $razormeta = Meta::paymentgateway(1,true);
        $api_key=$api_secret='';
        if(!empty($razormeta) && !empty($razormeta['razorpay_enable'])){
            if(empty($razormeta['razorpay_mode'])){ //test
                $api_key=$razormeta['razorpay_test_key'];
                $api_secret=$razormeta['razorpay_test_secret'];
            }else{ //live
                $api_key=$razormeta['razorpay_live_key'];
                $api_secret=$razormeta['razorpay_live_secret'];
            }
        }
        if($get=='secret'){
            return $api_secret;
        }
        if($get=='key'){
            return $api_key;
        }
        if($get=='razorpay_auto_subscription'){
            return $razormeta['razorpay_auto_subscription'];
        }
        return false;
    }
}

