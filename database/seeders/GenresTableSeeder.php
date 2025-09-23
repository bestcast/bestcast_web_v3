<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Genres;

class GenresTableSeeder extends Seeder
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
                'urlkey'        => 'action',
                'title'        => 'Action',
                'status'        => 1
            ],
            [
                'urlkey'        => 'romance',
                'title'        => 'Romance',
                'status'        => 1
            ],
            [
                'urlkey'        => 'dramas',
                'title'        => 'Dramas',
                'status'        => 1
            ],
            [
                'urlkey'        => 'thriller',
                'title'        => 'Thriller',
                'status'        => 1
            ],
            [
                'urlkey'        => 'horror',
                'title'        => 'Horror',
                'status'        => 1
            ],
            [
                'urlkey'        => 'sci-fi',
                'title'        => 'Sci-Fi',
                'status'        => 0
            ],
            [
                'urlkey'        => 'crime',
                'title'        => 'Crime',
                'status'        => 1
            ],
            [
                'urlkey'        => 'fantasy',
                'title'        => 'Fantasy',
                'status'        => 1
            ],
            [
                'urlkey'        => 'bollywood',
                'title'        => 'Bollywood',
                'status'        => 1
            ],
            [
                'urlkey'        => 'kollywood',
                'title'        => 'Kollywood',
                'status'        => 1
            ],
            [
                'urlkey'        => 'tollywood',
                'title'        => 'Tollywood',
                'status'        => 1
            ],
            [
                'urlkey'        => 'hollywood',
                'title'        => 'Hollywood',
                'status'        => 1
            ],
            [
                'urlkey'        => 'animation',
                'title'        => 'Animation',
                'status'        => 1
            ],
            [
                'urlkey'        => 'short-flim',
                'title'        => 'Short Flim',
                'status'        => 1
            ],
            [
                'urlkey'        => 'documentaries',
                'title'        => 'Documentaries',
                'status'        => 1
            ]
        ];

        /*
         * Add Role Items
         *
         */
        foreach ($Items as $Item) {
            $newItem = Genres::where('urlkey', '=', $Item['urlkey'])->first();
            if ($newItem === null) {
                $newItem = Genres::create([
                    'urlkey'      => $Item['urlkey'],
                    'title'       => $Item['title'],
                    'status'      => $Item['status']                
                ]);
            }
        }
    }
}
