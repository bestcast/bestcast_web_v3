<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movies;
use App\Models\Media;
use App\Models\MoviesGenres;
use App\Models\MoviesLanguages;
use App\Models\MoviesUsers;
use App\Models\MoviesSubtitle;
use App\Models\MoviesRelated;

class MoviesTableSeeder extends Seeder
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

        $Items=[];

        $movies=array('AG','Battery','Grandma','Pichuvakathi');
        foreach($movies as $movie){  $m=strtolower($movie);
            $img='image';$$img = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='medium';$$img = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='thumbnail';$$img = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='portrait';$$img = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);
            $img='portraitsmall';$$img = Media::create(['filename'=>$img.'.png','title'=>$img,
                        'urlkey'=>'img/sample/movies/'.$m.'/'.$img.'.png'
            ]);


            $domain='https://d2vkl8k9v6nxyr.cloudfront.net/Movies/';

            if($movie=='AG'){
                //https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Clip_Encoded/Clip.m3u8
                //https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Clip/480/Clip_4.m3u8
                //https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Clip/1080/Clip.m3u8
                //https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Anbulla_Ghilli_Encoded/Anbulla_Ghilli.m3u8
                $trailer_url=$domain.'Anbulla_Ghilli_Encoded/Clip_Encoded/Clip.m3u8';
                $trailer_url_480p=$domain.'Anbulla_Ghilli_Encoded/Clip/480/Clip_4.m3u8';
                $video_url=$video_url_480p=$video_url_720p=$video_url_1080p=$domain.'Anbulla_Ghilli_Encoded/Anbulla_Ghilli.m3u8';
            }elseif($movie=='Battery'){
                $trailer_url=$trailer_url_480p=$domain.'Testing/lord_perumal_song/lord_perumal_song.m3u8';
                $video_url=$video_url_480p=$video_url_720p=$video_url_1080p=$domain.'BatteryEncoded/BATTERY_UHD.m3u8';
            }elseif($movie=='Grandma'){
                $trailer_url=$trailer_url_480p=$domain.'Testing/lord_perumal_song/lord_perumal_song.m3u8';
                $video_url=$video_url_480p=$video_url_720p=$video_url_1080p=$domain.'Grandma_Encoded/GRANDMA.m3u8';
            }elseif($movie=='Pichuvakathi'){
                $trailer_url=$trailer_url_480p=$domain.'Testing/lord_perumal_song/lord_perumal_song.m3u8';
                $video_url=$video_url_480p=$video_url_720p=$video_url_1080p=$domain.'Pichuvakathi_Encoded/PICHUVAKATHI_UHD.m3u8';
            }

            $time='8640';

            //

            // $trailer_url=$video_url=$video_url_480p=$video_url_720p=$video_url_1080p="https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Testing/Encoded/1080/trailer%201080p.m3u8";
            // $trailer_url_480p="https://d2vkl8k9v6nxyr.cloudfront.net/Movies/Testing/Encoded/360/360.m3u8";
            // $time='150';

            //for($i=100;$i<=125;$i++){
                $Items[] =[
                    'urlkey'                => $m,
                    'title'                 => $movie,
                    'status'                => 1,
                    'content'               => 'A recently-retired sniper faces off with a younger, stronger, cloned version of himself that a covert government agency has engineered to kill him.',
                    'published_date'        => date('2020-10-30 h:i:s'),
                    'release_date'          => date('2020-10-30 h:i:s'),
                    'image_id'              => $image->id,
                    'medium_id'             => $medium->id,
                    'thumbnail_id'          => $thumbnail->id,
                    'thumbnail_id'          => $thumbnail->id,
                    'portrait_id'           => $portrait->id,
                    'portraitsmall_id'      => $portraitsmall->id,
                    'duration'              => $time, //must be in seconds
                    'age_restriction'       => 13,
                    'certificate'           => 'U/A 13+',
                    'certificate_text'      => 'tobacoo, substances, violence',
                    'tag_text'              => 'Voilent, Action, Thriller',
                    'is_upcoming'           => 0,
                    'topten'                => 0,
                    'trailer_url'           => $trailer_url,
                    'trailer_url_480p'      => $trailer_url_480p,
                    'video_url'             => $video_url,
                    'video_url_480p'        => $video_url_480p,
                    'video_url_720p'        => $video_url_720p,
                    'video_url_1080p'       => $video_url_1080p,
                    'subtitle_status'       => 1,
                ];
            //}

        }


        shuffle($Items);



        /*
         * Add Role Items
         *
         */
        foreach ($Items as $Item) {
            $newItem = Movies::where('urlkey', '=', $Item['urlkey'])->first();
            if ($newItem === null) {
                $movies = Movies::create($Item);
                if($movies->id==1){
                    $movies->topten=1;
                    $movies->is_upcoming=1;
                    $movies->save();
                }
                MoviesGenres::create(['movie_id'=>$movies->id,'genre_id'=>rand(0,15)]);
                MoviesGenres::create(['movie_id'=>$movies->id,'genre_id'=>rand(0,15)]);
                MoviesGenres::create(['movie_id'=>$movies->id,'genre_id'=>rand(0,15)]);
                MoviesLanguages::create(['movie_id'=>$movies->id,'language_id'=>rand(0,8)]);
                MoviesLanguages::create(['movie_id'=>$movies->id,'language_id'=>rand(0,8)]);
                //MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>4,'group'=>3]);
                // if($movies->id==1){
                //     MoviesSubtitle::create(['movie_id'=>$movies->id,'is_active'=>1,'label'=>'English',
                //         'url'=>$domain.'Testing/AG_FirstHalf.srt']);
                //     MoviesSubtitle::create(['movie_id'=>$movies->id,'label'=>'German',
                //         'url'=>$domain.'Testing/AG_FirstHalf.srt']);
                //     MoviesSubtitle::create(['movie_id'=>$movies->id,'label'=>'Spanish',
                //         'url'=>$domain.'Testing/AG_FirstHalf.srt']);

                //     //3=>'producer',4=>'director',5=>'actor',6=>'actress',7=>'musicdirector'
                //     MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>5,'group'=>4]);
                //     MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>4,'group'=>5]);
                //     MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>5,'group'=>5]);
                //     MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>5,'group'=>6]);
                //     MoviesUsers::create(['movie_id'=>$movies->id,'user_id'=>5,'group'=>7]);

                //     for($ii=1;$ii<=10;$ii++){
                //         MoviesRelated::create(['movie_id'=>$movies->id,'related_id'=>$ii]);
                //     }

                // }
            }
        }
    }
}
