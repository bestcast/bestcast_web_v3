<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Languages;

class LanguagesTableSeeder extends Seeder
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
        $Items = [
            [
                'urlkey'        => 'tamil',
                'title'        => 'Tamil',
                'status'        => 1
            ],
            [
                'urlkey'        => 'hindi',
                'title'        => 'Hindi',
                'status'        => 1
            ],
            [
                'urlkey'        => 'telugu',
                'title'        => 'Telugu',
                'status'        => 1
            ],
            [
                'urlkey'        => 'malayalam',
                'title'        => 'Malayalam',
                'status'        => 1
            ],
            [
                'urlkey'        => 'english',
                'title'        => 'English',
                'status'        => 1
            ],
            [
                'urlkey'        => 'korean',
                'title'        => 'Korean',
                'status'        => 0
            ],
            [
                'urlkey'        => 'chinese',
                'title'        => 'Chinese',
                'status'        => 1
            ],
            [
                'urlkey'        => 'international',
                'title'        => 'International',
                'status'        => 1
            ]
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($Items as $Item) {
            $newItem = Languages::where('urlkey', '=', $Item['urlkey'])->first();
            if ($newItem === null) {
                $newItem = Languages::create([
                    'urlkey'      => $Item['urlkey'],
                    'title'       => $Item['title'],
                    'status'      => $Item['status']                
                ]);
            }
        }
    }
}
