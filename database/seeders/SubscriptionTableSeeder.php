<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Subscription;

class SubscriptionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
         * Role Types
         *
         */
        $Items = [
            [
                'status'                => 1,
                'urlkey'                => 'mobile',
                'title'                 => 'Mobile',
                'price'                 => 99,
                'content'               => '<ul>
                                            <li>Video Quality : Fair</li>
                                            <li>Video Resolution : 480p</li>
                                            <li>Supported devices : Mobile and tablet</li>
                                            <li>Devices to watch limit : 1</li>
                                            <li>Ads free movies and shows</li>
                                            </ul>',
                'video_quality'         => 0,
                'video_resolution'      => 0,
                'video_device'          => 0,
                'video_sametime'        => 1,
                'device_limit'          => 1,
                'duration'              => 1,
                'duration_type'         => 1,
                'sortorder'             => 4,
                //'razorpay_id'           => 'plan_Ny988GGRnXTJL0',
                'razorpay_id'           => 'plan_Ny9j30KE8qHb39'
            ],
            [
                'status'                => 1,
                'urlkey'                => 'basic',
                'title'                 => 'Basic',
                'price'                 => 199,
                'content'               => '<ul>
                                            <li>Video Quality : Good</li>
                                            <li>Video Resolution : 720p (HD)</li>
                                            <li>Supported devices <br> TV, Computer, Mobile, Tablet</li>
                                            <li>Devices to watch limit : 1</li>
                                            <li>Ads free movies and shows</li>
                                            </ul>',
                'video_quality'         => 1,
                'video_resolution'      => 1,
                'video_device'          => 1,
                'video_sametime'        => 1,
                'device_limit'          => 1,
                'duration'              => 1,
                'duration_type'         => 1,
                'tagtext'               => 'Most Popular',
                'sortorder'             => 3,
                //'razorpay_id'           => 'plan_Ny98V8PbZIwtZd',
                'razorpay_id'           => 'plan_Ny9jbJwIyILPbT'
            ],
            [
                'status'                => 1,
                'urlkey'                => 'standard',
                'title'                 => 'Standard',
                'price'                 => 399,
                'content'               => '<ul>
                                            <li>Video Quality : Great</li>
                                            <li>Video Resolution : 1080p (Full HD)</li>
                                            <li>Supported devices <br> TV, Computer, Mobile, Tablet</li>
                                            <li>Devices to watch limit : 2</li>
                                            <li>Ads free movies and shows</li>
                                            </ul>',
                'video_quality'         => 2,
                'video_resolution'      => 2,
                'video_device'          => 2,
                'video_sametime'        => 2,
                'device_limit'          => 2,
                'duration'              => 1,
                'duration_type'         => 1,
                'tagtext'               => 'Most Exclusive',
                'sortorder'             => 2,
                //'razorpay_id'           => 'plan_Ny98oNfxTTDa9V',
                'razorpay_id'           => 'plan_Ny9jzVPLn82eDR'
            ],
            [
                'status'                => 1,
                'urlkey'                => '1',
                'title'                 => 'Premium',
                'price'                 => 499,
                'content'               => '<ul>
                                            <li>Video Quality : Best</li>
                                            <li>Video Resolution : 4k (Ultra HD)</li>
                                            <li>Supported devices <br> TV, Computer, Mobile, Tablet</li>
                                            <li>Devices to watch limit : 6</li>
                                            <li>Ads free movies and shows</li>
                                            </ul>',
                'video_quality'         => 3,
                'video_resolution'      => 3,
                'video_device'          => 3,
                'video_sametime'        => 6,
                'device_limit'          => 6,
                'duration'              => 1,
                'duration_type'         => 1,
                'sortorder'             => 1,
                //'razorpay_id'           => 'plan_Ny99Er5dCwUrEq',
                'razorpay_id'           => 'plan_Ny9kKjlBndsvmO'
            ],
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($Items as $Item) {
            $newItem = Subscription::where('urlkey', '=', $Item['urlkey'])->first();
            if ($newItem === null) {
                $newItem = Subscription::create([
                    'status'                => $Item['status'],
                    'urlkey'                => $Item['urlkey'],
                    'title'                 => $Item['title'],
                    'price'                 => $Item['price'],
                    'content'               => $Item['content'],
                    'video_quality'         => $Item['video_quality'],
                    'video_resolution'      => $Item['video_resolution'] ,
                    'video_device'          => $Item['video_device'] ,
                    'video_sametime'        => $Item['video_sametime'] ,
                    'device_limit'          => $Item['device_limit'] ,
                    'duration'              => $Item['duration'] ,
                    'duration_type'         => $Item['duration_type'],
                    'sortorder'              => $Item['sortorder'],
                    'razorpay_id'              => $Item['razorpay_id'],
                    'tagtext'             => empty($Item['tagtext'])?'':$Item['tagtext']
                ]);
            }
        }
    }
}
