<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shows;

class ShowsTableSeeder extends Seeder
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
        // $Items=[];
        // for($i=100;$i<=200;$i++){
        // $Items[] =
        //     [
        //         'urlkey'        => 'shows-'.$i,
        //         'title'        => 'Shows '.$i,
        //         'status'        => ($i%2)
        //     ];
        // }

        // /*
        //  * Add Role Items
        //  *
        //  */
        // foreach ($Items as $Item) {
        //     $newItem = Shows::where('urlkey', '=', $Item['urlkey'])->first();
        //     if ($newItem === null) {
        //         $newItem = Shows::create([
        //             'urlkey'      => $Item['urlkey'],
        //             'title'       => $Item['title'],
        //             'status'      => $Item['status']                
        //         ]);
        //     }
        // }
    }
}
