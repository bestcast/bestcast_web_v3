<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuTableSeeder extends Seeder
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
        $Items=[
            [
                'name'          => 'Home',
                'url'           => url('/home'),
                'status'        => 1,
            ],
            [
                'name'        => 'Movies',
                'url'        => url('/movies'),
                'status'        => 1,
            ],
            [
                'name'        => 'New',
                'url'        => url('/new'),
                'status'        => 1,
            ],
            [
                'name'        => 'Popular',
                'url'        => url('/popular'),
                'status'        => 1,
            ],
            [
                'name'        => 'My List',
                'url'        => url('/my-list'),
                'status'        => 1,
            ],
            // [
            //     'name'        => 'Browse by Languages',
            //     'url'        => url('/browse-by-languages'),
            //     'status'        => 1,
            // ]
        ];
        /*
         * Add Role Items
         *
         */
        foreach ($Items as $Item) {
            $newItem = Menu::create($Item);
        }
    }
}
