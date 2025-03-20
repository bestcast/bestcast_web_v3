<?php

namespace App\Http\Controllers\Movies;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Post;
use Field;
use Lib;
use Auth;
use App\Traits\HttpResponses;
use App\User;
use App\Models\Genres;
use App\Models\Languages;
use App\Models\Movies;
use App\Models\MoviesGenres;
use App\Models\MoviesLanguages;
use App\Models\MoviesRelated;
use App\Models\MoviesUsers;
use App\Models\Meta;
use App\Models\Banner;
use App\Models\Blocks;
use App\Models\Menu;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Http\Resources\MenuResource;
use App\Http\Resources\GenresResource;
use App\Http\Resources\LanguagesResource;
use App\Http\Resources\MoviesResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlocksResource;
use App\Http\Resources\MoviesListResource;
use App\Models\UsersMovies;
use App\Models\UsersDevice;
use Email;
use Redirect;
use Illuminate\Support\Str;
use App\Models\Appnotify;
use App\Http\Resources\AppnotifyResource;

class GuestController extends Controller
{
    use HttpResponses;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $post = Post::where('urlkey','home')->first();
        $meta=$post->meta->pluck('value','path');

        return view('movies.public', ['post'=>$post,'meta'=>$meta]);
    }
    public function search()
    {
        return view('movies.guestsearch');
    }

    public function urlkey($urlkey='',$model='',$id=0)
    {
        if(empty($urlkey)){ $urlkey='home'; }
        $post=Post::where('urlkey',$urlkey)->first();
        $ispublic=Post::ispublic($post);

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
            return view('movies.public', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey,'genre'=>$genre,'language'=>$language]);       
        }

        return view('page.index', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey]);
    }


    public function menulist(Request $request)
    {
        $data=Menu::getApiList();
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return MenuResource::collection($data);
    }


    public function genrelist(Request $request)
    {
        $data=Genres::getApiList();
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return GenresResource::collection($data);
    }

    public function languagelist(Request $request)
    {
        $data=Languages::getApiList();
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return LanguagesResource::collection($data);
    }


    public function movieslist(Request $request)
    {
        $data=Movies::getApiList(0);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return MoviesResource::collection($data);
    }

    public function bannerlist(Request $request)
    {
        $data=Banner::getApiList(0);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        $list=$request->get('list');
        if($list){
            return BannerResource::collection($data);
        }else{
            return new BannerResource($data);
        }
    }

    public function blockslist(Request $request)
    {
        $data=Blocks::getApiList(0);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return BlocksResource::collection($data);
    }

    public function latestlist(Request $request)
    {
        $data=Movies::getApiList(0);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return MoviesListResource::collection($data);
    }

    public function getusermovie($movieid,Request $request)
    {

        $data=Movies::where('status',1)->where('id',$movieid)->first();

        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return new MoviesResource($data);
        //return new UsermoviesResource($data);
    }

    public function generateqrcode(Request $request)
    {        
        return $this->success([
            'qrcode' => (string) Str::uuid()."-".date("Ymdhis")
        ]);
    }

    public function appnotifylist($id,Request $request) //profileid
    { 
        $data=Appnotify::getApiList(0);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return AppnotifyResource::collection($data);
    }
}
