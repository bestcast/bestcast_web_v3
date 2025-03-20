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

class BrowseController extends Controller
{
    use HttpResponses;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=auth()->user();

        if($user->hasRole(['producer'])){
            return redirect()->route('user.myaccount.producer');
        }
        
        if(empty($user->phone_verified_at)){
            return redirect()->route('otp.verification', ['send' => 1,'phone' => 1]);
        }

        //check payment and update to user plan start
        if(!empty($user) && $user->plan==0){
            $trans=Transaction::getActive($user);
            if(!empty($trans->razorpay_subscription_id)){
                $razorResponse=Transaction::updatePlanToUser($user,$trans->razorpay_subscription_id);
            }
        }
        //check payment and update to user plan end

        //Force user to buy plan start
        // if($user->plan==0)
        //     return redirect(url('/pricing'));

        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return redirect(url('/my-account?reload=1'));
        //Force user to buy plan end

        $post = Post::where('urlkey','home')->first();
        $meta=$post->meta->pluck('value','path');
        // if(isset($_GET['testplayer'])){
        // return view('movies.indextestplayer', ['post'=>$post,'meta'=>$meta]);
        // }
        if(!empty($_GET['p'])){
            Session::forget('profileToken');
            sleep(1);
            Session::put('profileToken',$_GET['p']);
            return redirect(url('/browse'));
        }


        return view('movies.index', ['post'=>$post,'meta'=>$meta]);
    }
    public function search()
    {
        $user=auth()->user();

        if(empty($user->phone_verified_at)){
            return redirect()->route('otp.verification', ['send' => 1,'phone' => 1]);
        }
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return redirect(url('/pricing'));
        //Force user to buy plan end

        return view('movies.search');
    }
    public function watch($id)
    {
        header('Access-Control-Allow-Origin: *');
        //Force user to buy plan start
        $movie = Movies::find($id);
        if(empty($movie->movie_access)){
            $plan=Subscription::getPlan();
            if(empty($plan))
                return redirect(url('/pricing'));
        }
        //Force user to buy plan end

        $user=Auth::user();

        if(empty($user->phone_verified_at)){
            return redirect()->route('otp.verification', ['send' => 1,'phone' => 1]);
        }
        
        $device=UsersDevice::getApiList($user);
        if(!empty($device) && !empty($plan->device_limit)){
            if(count($device)>$plan->device_limit){
                return redirect(url('/account/devices'));
            }
        }


        $profileToken=Session::get('profileToken');
        if(!empty($profileToken)){
            $data=UsersMovies::getMovie($user->id,$profileToken,$id);
            if(empty($data)){
                $usersMovies=new UsersMovies();
                $usersMovies->user_id=$user->id;
                $usersMovies->profile_id=$profileToken;
                $usersMovies->movie_id=$id;
                $usersMovies->viewed=1;
                $usersMovies->save();
            }
        }

        $movie = UsersMovies::getMovie($user->id,$profileToken,$id);
        //dd($movie);
        if(empty($movie->id)){
            $post=Post::where('urlkey','page-not-found')->first();
            $meta=$post->meta->pluck('value','path');
            return view('errors.lost',['post'=>$post,'meta'=>$meta]);
        }
        return view('movies.watch', ['movie'=>$movie,'profileToken'=>$profileToken]);
    }
    public function mylist()
    {
        $user=auth()->user();

        if(empty($user->phone_verified_at)){
            return redirect()->route('otp.verification', ['send' => 1,'phone' => 1]);
        }
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return redirect(url('/pricing'));
        //Force user to buy plan end

        return view('movies.mylist');
    }

    /*
    it will call only
    Route::get('/{urlkey}/{model}/{id}', 'Movies\BrowseController@urlkey')->name('movies.genre');
    */
    public function urlkey($urlkey='',$model='',$id=0)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return redirect(url('/pricing'));
        //Force user to buy plan end

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
            return view('movies.index', ['post'=>$post,'meta'=>$meta,'urlkey'=>$urlkey,'genre'=>$genre,'language'=>$language]);       
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
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        $data=Movies::getApiList($user->id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return MoviesResource::collection($data);
    }

    public function bannerlist(Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        $data=Banner::getApiList($user->id);
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
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        $data=Blocks::getApiList($user->id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return BlocksResource::collection($data);
    }

    public function latestlist(Request $request)
    {
        $user=Auth::user();
        $data=Movies::getApiList($user->id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return MoviesListResource::collection($data);
    }

    public function menuapicode(Request $r){
        $user=Auth::user();Lib::send($r);
        return view('auth.contact');
    }
    public function setprofiletoken($id)
    {
        Session::put('profileToken',$id);
        //Session::forget('profileToken');
        return response()->json(['id' => $id]);
    }




}
