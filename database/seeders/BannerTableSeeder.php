<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Banner;
use Lib;
use App\Models\Media;

class BannerTableSeeder extends Seeder
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
            $img='image';$$img[$m] = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='thumbnail';$$img[$m] = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='portrait';$$img[$m] = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='portraitsmall';$$img[$m] = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='logo';$$img[$m] = Media::create(['filename'=>$img.'.jpg','title'=>$img,
                        'urlkey'=>'img/sample/banner-logo.png'
            ]);
        }

        $sort=0;shuffle($movies);
        foreach($movies as $title){$sort++; //."-".time()
            $m=strtolower($title);$title='Home '.$title; 
            $blocks=Banner::create(['urlkey'=>Lib::slug($title),'title'=>$title,'movies_id'=> 1,'page_id'=> 1,'status'=> 1,'sortorder'=> $sort,'image_id'=> $image[$m]->id,'thumbnail_id'=>$thumbnail[$m]->id,'logo_id'=>$logo[$m]->id,'portrait_id'=>$portrait[$m]->id,'portraitsmall_id'=>$portraitsmall[$m]->id]);
            if($sort==3){   break;  }
        }

        $sort=0;shuffle($movies);
        foreach($movies as $title){$sort++;
            $m=strtolower($title);$title='Movies '.$title;
            $blocks=Banner::create(['urlkey'=>Lib::slug($title)."-".time(),'title'=>$title,'movies_id'=> 2,'page_id'=> 2,'status'=> 1,'sortorder'=> $sort,'image_id'=> $image[$m]->id,'thumbnail_id'=>$thumbnail[$m]->id,'logo_id'=>$logo[$m]->id,'portrait_id'=>$portrait[$m]->id,'portraitsmall_id'=>$portraitsmall[$m]->id]);
            if($sort==1){   break;  }
        }


        $sort=0;shuffle($movies);
        foreach($movies as $title){$sort++;
            $m=strtolower($title);$title='Popular '.$title;
            $blocks=Banner::create(['urlkey'=>Lib::slug($title)."-".time(),'title'=>$title,'movies_id'=> 3,'page_id'=> 4,'status'=> 1,'sortorder'=> $sort,'image_id'=> $image[$m]->id,'thumbnail_id'=>$thumbnail[$m]->id,'logo_id'=>$logo[$m]->id,'portrait_id'=>$portrait[$m]->id,'portraitsmall_id'=>$portraitsmall[$m]->id]);
            if($sort==1){   break;  }
        }

    }
}
