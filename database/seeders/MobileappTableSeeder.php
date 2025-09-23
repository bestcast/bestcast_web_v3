<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Media;

class MobileappTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'appid',
            'value'=>'123456789'
        ]);

        $i=1;
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_title_'.$i,
            'value'=>'Unlimited entertainment, one low price'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_subtitle_'.$i,
            'value'=>'All of Bestcast starting at just 249'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_image_'.$i.'_urlkey',
            'value'=>'img/sample/app/front_image_'.$i.'.png'
        ]);

        $i=2;
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_title_'.$i,
            'value'=>'Cancel online at any time'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_subtitle_'.$i,
            'value'=>'Join today, no reason to wait.'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_image_'.$i.'_urlkey',
            'value'=>'img/sample/app/front_image_'.$i.'.png'
        ]);


        $i=3;
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_title_'.$i,
            'value'=>'Cancel online at any time'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_subtitle_'.$i,
            'value'=>'Join today, no reason to wait.'
        ]);
        $meta = Meta::create(['meta_id' => 1,'type'=>4,'path'=>'front_image_'.$i.'_urlkey',
            'value'=>'img/sample/app/front_image_'.$i.'.png'
        ]);
    }
}
