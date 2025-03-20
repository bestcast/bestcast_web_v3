<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;
use App\Models\Meta;
use App\Models\PostContent;
use App\Models\Media;

class ReferTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meta = Meta::create(['meta_id' => 1,'type'=>6,'path'=>'refer_title',
            'value'=>'Share Referral URL with your friends and earn credits'
        ]);

        $meta = Meta::create(['meta_id' => 1,'type'=>6,'path'=>'refer_credits',
            'value'=>'100'
        ]);

        $meta = Meta::create(['meta_id' => 1,'type'=>6,'path'=>'refer_banner_urlkey',
            'value'=>'img/sample/refer.jpg'
        ]);

        $meta = Meta::create(['meta_id' => 1,'type'=>6,'path'=>'refer_content',
            'value'=>'<p>Every time a referred friend registers, we send you a gift worth 100 credits as a token of our appreciation. Once your referred friend makes their registration &amp; once made the subscription, you will get a credit points.</p>'
        ]);


        $meta = Meta::create(['meta_id' => 1,'type'=>6,'path'=>'refer_instruction',
            'value'=>'<h3>How does it work?</h3> <ul> <li>Every time a referred friend registers, we send you a gift worth 100 credits as a token of our appreciation.</li> <li>Once your referred friend makes their registration &amp; once made the subscription, you will get a credit points.</li> </ul>'
        ]);

    }
}
