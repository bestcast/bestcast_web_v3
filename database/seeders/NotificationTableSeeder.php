<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;

class NotificationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $Items = [
            [
                'user_id'       => 0,
                'type'          => 'admin',
                'title'         => 'Password change alert!',
                'content'       => 'User admin@user.com Login password changed.',
                'mark_read'     => 0,
                'icon'          => 1
            ]
            // [
            //     'user_id'       => 6,
            //     'type'          => 'user',
            //     'title'         => 'Password change alert!',
            //     'content'       => 'You have successfully changed your SBI Netbanking Login password.',
            //     'mark_read'     => 0,
            //     'visibility'    => 1,
            //     'model'         => 'User',
            //     'relation_id'   => 6,
            //     'icon'          => 1
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Subscription Submission : Standard Plan',
            //     'content'       => 'Payment under verification. we will notify you again via email.',
            //     'mark_read'     => 0,
            //     'icon'          => 2
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Subscription Submission : Premium Plan',
            //     'content'       => 'Payment under verification. we will notify you again via email.',
            //     'mark_read'     => 0,
            //     'icon'          => 2
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Subscription Submission : Basic Plan',
            //     'content'       => 'Payment under verification. we will notify you again via email.',
            //     'mark_read'     => 0,
            //     'icon'          => 2
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Admin Logged in : admin@admin.com',
            //     'content'       => 'Admin logged in notificaiton.',
            //     'mark_read'     => 0,
            //     'icon'          => 1
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Admin Logged in : admin@user.com',
            //     'content'       => 'Admin logged in notificaiton.',
            //     'mark_read'     => 0,
            //     'icon'          => 1
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Subscription plan expired.',
            //     'content'       => 'Admin logged in notificaiton. User password@passwor.com',
            //     'mark_read'     => 1,
            //     'icon'          => 2
            // ],
            // [
            //     'user_id'       => 0,
            //     'type'          => 'admin',
            //     'title'         => 'Subscription plan expired.',
            //     'content'       => 'Admin logged in notificaiton. User user@passwor.com',
            //     'mark_read'     => 0,
            //     'icon'          => 2
            // ],
        ];

        foreach ($Items as $Item) {
            $newItem = Notification::create($Item);
        }
    }
}
