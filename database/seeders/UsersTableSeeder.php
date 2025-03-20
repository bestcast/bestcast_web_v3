<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRole = config('roles.models.role')::where('name', '=', 'Admin')->first();
        $subadminRole = config('roles.models.role')::where('name', '=', 'Subadmin')->first();
        $editorRole = config('roles.models.role')::where('name', '=', 'Editor')->first();
        $producerRole = config('roles.models.role')::where('name', '=', 'Producer')->first();
        $castRole = config('roles.models.role')::where('name', '=', 'Cast')->first();
        $userRole = config('roles.models.role')::where('name', '=', 'User')->first();
        $permissions = config('roles.models.permission')::all();

        /*
         * Add Users
         *
         */

        // $type=[0=>'User',1=>'Admin',2=>'Editor',3=>'Producer',4=>'Director',5=>'Actor',6=>'Actress',7=>'Music Director'];


        if (config('roles.models.defaultUser')::where('email', '=', 'admin@admin.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'          => 'Super Admin',
                'firstname'     => 'Super',
                'lastname'      => 'Admin',
                'email'         => 'littletakegroup@gmail.com',
                'type'          => 100,
                'plan'          => 4,
                'plan_expiry'   => '2094-08-16 22:13:44',
                'password'      => bcrypt('#Admin1!!Super@Admin'),
            ]);

            $newUser->referal_code=User::getReferralCode($newUser->id);
            $newUser->save();


            $newUser->attachRole($adminRole);
            foreach ($permissions as $permission) {
                $newUser->attachPermission($permission);
            }
        }


        if (config('roles.models.defaultUser')::where('email', '=', 'admin@user.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'          => 'Admin',
                'firstname'     => 'Admin',
                'email'    => 'support@bestcast.co',
                'type'    => 1,
                'plan'          => 3,
                'plan_expiry'   => '2094-08-16 22:13:44',
                'password' => bcrypt('password'),
            ]);

            $newUser->referal_code=User::getReferralCode($newUser->id);
            $newUser->save();

            $newUser->attachRole($subadminRole);
        }

        // if (config('roles.models.defaultUser')::where('email', '=', 'editor@editor.com')->first() === null) {
        //     $newUser = config('roles.models.defaultUser')::create([
        //         'name'     => 'Editor',
        //         'firstname'     => 'Editor',
        //         'email'    => 'editor@editor.com',
        //         'type'    => 2,
        //         'password' => bcrypt('password'),
        //     ]);


        //     $newUser->referal_code=User::getReferralCode($newUser->id);
        //     $newUser->save();

        //     $newUser->attachRole($editorRole);
        // }


        if (config('roles.models.defaultUser')::where('email', '=', 'producer@producer.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'Producer',
                'firstname'     => 'Producer',
                'email'    => 'producer@bestcast.co',
                'type'    => 3,
                'password' => bcrypt('password'),
            ]);


            $newUser->referal_code=User::getReferralCode($newUser->id);
            $newUser->save();

            $newUser->attachRole($producerRole);
        }

        if (config('roles.models.defaultUser')::where('email', '=', 'cast@cast.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'Cast',
                'firstname'     => 'Cast',
                'email'    => 'cast@bestcast.co',
                'type'    => 5,
                'password' => bcrypt('password'),
            ]);


            $newUser->referal_code=User::getReferralCode($newUser->id);
            $newUser->save();

            $newUser->attachRole($castRole);
        }

        if (config('roles.models.defaultUser')::where('email', '=', 'user@user.com')->first() === null) {
            $newUser = config('roles.models.defaultUser')::create([
                'name'     => 'User',
                'firstname'=> 'User',
                'email'    => 'user@bestcast.co',
                'plan'          => 2,
                'plan_expiry'   => '2094-08-16 22:13:44',
                'password' => bcrypt('password'),
            ]);

            $newUser->referal_code=User::getReferralCode($newUser->id);
            $newUser->save();

            $newUser->attachRole($userRole);
        }
    }
}
