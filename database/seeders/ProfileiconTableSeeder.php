<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profileicon;
use App\Models\Media;

class ProfileiconTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for($i=1;$i<=10;$i++){
            //Image addd
            $img='thumbnail';$$img = Media::create(['filename'=>$img.'.jpg','title'=>$img,
                        'urlkey'=>'img/sample/profile-'.$i.'.jpg'
            ]);

            //Profile Icon add
            $Item=['urlkey'=>'profile-icon-'.$i, 'title'=>'Profile '.$i, 'thumbnail_id'=>$thumbnail->id];
            $movies = Profileicon::create($Item);
        }

    }
}
