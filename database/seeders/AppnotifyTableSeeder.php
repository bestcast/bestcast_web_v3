<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Appnotify;

class AppnotifyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $movies=array('AG','Battery','Grandma','Pichuvakathi');
        foreach($movies as $movie){  $m=strtolower($movie);
            $img='thumbnail';$$img = Media::create(['filename'=>$img.'.jpg','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
        
            $data=[
                'title'                 => 'Notification '.$movie,
                'movie_id'              => rand(1,4),
                'thumbnail_id'          => $thumbnail->id,
            ];
            $movies = Appnotify::create($data);
        }

    }
}
