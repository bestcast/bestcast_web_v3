<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\User;
use App\Models\UsersProfile;
use App\Models\Profileicon;

class UsersProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();
        foreach($users as $user){
            if(!empty($user->id)){
                $profileicon=Profileicon::first();
                UsersProfile::create([
                    'user_id'           => $user->id,
                    'name'              => $user->firstname,
                    'profileicon_id'    => $profileicon->id
                ]);
            }
        }
    }
}
