<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Media;

class PaymentgatewayTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Razorpay
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_enable',
            'value'=>1
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_merchantid',
            'value'=>'Kmx2Q9bp7N9e3L'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_mode',
            'value'=>1
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_test_key',
            'value'=>'rzp_test_502LUj76iVogJX'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_test_secret',
            'value'=>'8JOtFZ6Hz8RVCrfdsYkfoOXo'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_live_key',
            'value'=>'rzp_live_TLSPwcc0Da68PW'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_live_secret',
            'value'=>'IhgO9I0qUiGBmOtRjJQtoNGo'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_company_name',
            'value'=>env('APP_NAME')
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_logo_urlkey',
            'value'=>'img/payment-logo.png'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'razorpay_colorcode',
            'value'=>'#cc1e24'
        ]);


        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'buyplan_title',
            'value'=>'Finalize your payment'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'buyplan_content',
            'value'=>'Your payment is encrypted and you can change your payment method at anytime.<br>Secure for peace of mind. <br> Cancel easily online.'
        ]);

        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'paymentstatus_title',
            'value'=>'Thank you!'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'paymentstatus_content',
            'value'=>'Your payment is encrypted and you can change your payment method at anytime.<br>Secure for peace of mind. <br> Cancel easily online. <br> The page will automatically redirect to the homepage in 5 seconds.'
        ]);

        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'paymentstatus_warning',
            'value'=>'Payment status under verification.'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'paymentstatus_success',
            'value'=>'Payment Success.'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>5,'path'=>'paymentstatus_error',
            'value'=>'Payment status Failed! Please contact our help support.'
        ]);
    }
}
