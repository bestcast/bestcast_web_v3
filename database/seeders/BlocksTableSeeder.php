<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blocks;
use App\Models\BlocksMovies;
use Lib;

class BlocksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $titles=['New Releases','Popular Movies','Trending Now','Comedy Movies'];
        $sort=0;
        foreach($titles as $title){$sort++; //."-".time()
            $blocks=Blocks::create(['urlkey'=>Lib::slug($title),'title'=>$title,'page_id'=> 1,'status'=> 1,'sortorder'=> $sort]);
            for($i=1;$i<=4;$i++){
                BlocksMovies::create(['movies_id'=>rand(1,4),'blocks_id'=>$blocks->id]);
            }
        }

        $titles=['Tamil Movies','Hindi all time watch','New Releases','Thiller Movies'];
        $sort=0;
        foreach($titles as $title){$sort++;
            $blocks=Blocks::create(['urlkey'=>Lib::slug($title)."-".time(),'title'=>$title,'page_id'=> 2,'status'=> 1,'sortorder'=> $sort]);
            for($i=1;$i<=4;$i++){
                BlocksMovies::create(['movies_id'=>rand(1,4),'blocks_id'=>$blocks->id]);
            }
        }


        $titles=['Coming Next Week','Worth the wait','Coming this week'];
        $sort=0;
        foreach($titles as $title){$sort++;
            $blocks=Blocks::create(['urlkey'=>Lib::slug($title)."-".time(),'title'=>$title,'page_id'=> 3,'status'=> 1,'sortorder'=> $sort]);
            for($i=1;$i<=4;$i++){
                BlocksMovies::create(['movies_id'=>rand(1,4),'blocks_id'=>$blocks->id]);
            }
        }

    }
}
