<?php

namespace App\Http\Controllers\Webseries;

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
use App\Models\Webseries;
use App\Models\Episode;
use App\Models\EpisodeGenres;
use App\Models\EpisodeLanguages;
use App\Models\EpisodeRelated;
use App\Models\EpisodeUsers;
use App\Models\Meta;
use App\Models\Banner;
use App\Models\Blocks;
use App\Models\Menu;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Http\Resources\MenuResource;
use App\Http\Resources\GenresResource;
use App\Http\Resources\LanguagesResource;
use App\Http\Resources\WebseriesResource;
use App\Http\Resources\WebseriesBannerResource;
use App\Http\Resources\BlocksResource;
use App\Http\Resources\WebseriesListResource;
use App\Models\UsersMovies;
use App\Models\UsersDevice;
use Email;
use Redirect;
use App\Models\Season;
use App\Http\Resources\WebseriesBlocksResource;
use App\Http\Resources\WebseriesBannerWatchResource;
use App\Http\Resources\WebseriesWatchDetailResource;
use App\Models\UsersEpisodes;

class WebseriesController extends Controller
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
    
    public function index(Request $request, $id)  // ← renamed from $episode_id to $id
    {
        $user = auth()->user();
        if ($user->hasRole(['producer'])) {
            return redirect()->route('user.myaccount.producer');
        }

        if (empty($user->phone_verified_at)) {
            return redirect()->route('otp.verification', ['send' => 1, 'phone' => 1]);
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


        return view('webseries.index', ['post'=>$post,'meta'=>$meta]);
    }
    
    public function episodewebserieswatch(Request $request, $episode_id)
    {
        $user = Auth::user();

        if (empty($user->phone_verified_at)) {
            return redirect()->route('otp.verification', ['send' => 1, 'phone' => 1]);
        }

        $episode = Episode::with(['season.webseries', 'image', 'thumbnail', 'subtitle'])
            ->find($episode_id);

        if (!$episode) {
            abort(404);
        }

        if (empty($episode->movie_access)) {
            $plan = Subscription::getPlan();
            if (empty($plan)) {
                return redirect(url('/pricing'));
            }
        }

        $profileToken = Session::get('profileToken');

        $userEpisode = UsersEpisodes::where('user_id', $user->id)
            ->where('episode_id', $episode_id)
            ->first();

        if (!$userEpisode) {
            $userEpisode = new UsersEpisodes();
            $userEpisode->user_id    = $user->id;
            $userEpisode->profile_id = $profileToken ?? 0;
            $userEpisode->episode_id = $episode_id;
            $userEpisode->viewed     = 1;
            $userEpisode->save();
        }

        $webseries_id = $episode->season->webseries_id;

        return view('webseries.episodewebserieswatch', [
            'episode'      => $episode,
            'userEpisode'  => $userEpisode,
            'webseries_id' => $webseries_id,
            'profileToken' => $profileToken ?? '',
        ]);
    }
    public function webserieswatchepisode(Request $request, $webseries_id, $episode_id)
    {   
        header('Access-Control-Allow-Origin: *');

        $user = Auth::user();

        if (empty($user->phone_verified_at)) {
            return redirect()->route('otp.verification', ['send' => 1, 'phone' => 1]);
        }

        // Find the episode
        $episode = Episode::with([
            'season.webseries',
            'image', 'thumbnail', 'subtitle'
        ])->find($episode_id);

        if (!$episode || $episode->season->webseries_id != $webseries_id) {
            abort(404);
        }

        // Check plan if episode is not free
        if (empty($episode->movie_access)) {
            $plan = Subscription::getPlan();
            if (empty($plan)) {
                return redirect(url('/pricing'));
            }
        }

        // Device limit check
        $plan = Subscription::getPlan();
        $device = UsersDevice::getApiList($user);
        if (!empty($device) && !empty($plan->device_limit)) {
            if (count($device) > $plan->device_limit) {
                return redirect(url('/account/devices'));
            }
        }

        // Track user episode view
        $profileToken = Session::get('profileToken');
        // If session is empty, create/find episode record without profile
        $userEpisode = UsersEpisodes::where('user_id', $user->id)
            ->where('episode_id', $episode_id)
            ->when($profileToken, function ($q) use ($profileToken) {
                $q->where('profile_id', $profileToken);
            })
            ->first();

        // Get user episode (with watch_time, watched_percent)
        /*$userEpisode = UsersEpisodes::where('user_id', $user->id)
            ->where('profile_id', $profileToken)
            ->where('episode_id', $episode_id)
            ->first();*/

        /*if (empty($userEpisode)) {
            $post = Post::where('urlkey', 'page-not-found')->first();
            $meta = $post->meta->pluck('value', 'path');
            return view('errors.lost', ['post' => $post, 'meta' => $meta]);
        }*/
        if (empty($userEpisode)) {
            $userEpisode = new UsersEpisodes();
            $userEpisode->user_id    = $user->id;
            $userEpisode->profile_id = $profileToken ?? 0; // fallback to 0 if null
            $userEpisode->episode_id = $episode_id;
            $userEpisode->viewed     = 1;
            $userEpisode->save();
        }                          
        return view('webseries.webserieswatchepisode', [
            'episode'      => $episode,
            'userEpisode'  => $userEpisode,
            'webseries_id' => $webseries_id,
            'profileToken' => $profileToken,
        ]);
    }
    public function webserieswatch(Request $request, $id)
    {

        header('Access-Control-Allow-Origin: *');
        $user = Auth::user();
        // $id is webseries_id
        $webseries = Webseries::with([
            'seasons' => function ($q) { $q->orderBy('id', 'asc'); },
            'seasons.episodes' => function ($q) { $q->orderBy('id', 'asc'); },
            'seasons.episodes.episode_users' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
        ])->findOrFail($id);
        $latestSeason  = $webseries->seasons->last();
        $latestEpisode = optional($latestSeason)->episodes->last();
        /*$episode = Episode::with([
            'season.webseries',
            'season.webseries.seasons.episodes'
        ])->find($episode_id);*/

        if (!$webseries) {
            abort(404);
        }

        //$webseries = $episode->season->webseries;

        // Use NEW function (all seasons + episodes)
        //$banner = $this->seasonepisodebannerlist($request, $webseries->id);

        // Blocks (optional)
        //$blocks = Blocks::getWebseriesWatchApiList($user->id ?? 0, $webseries->id);
        return view('webseries.webserieswatch', [
            'webseries'  => $webseries,
            'episode'    => $latestEpisode,       // current episode
            'season'     => $latestSeason, // current season
        ]);
    }

    /*public function watchApi($episode_id)
    {
        $user = Auth::user();

        $episode = Episode::with([
            'season.webseries',
            'season.webseries.seasons.episodes'
        ])->find($episode_id);

        if (!$episode) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $webseries = $episode->season->webseries;

        $banner = Banner::getWebseriesWatchApiList($user->id, $webseries->id);
        $blocks = Blocks::getwebseriesWatchApiList($user->id, $webseries->id);

        return response()->json([
            'banner' => new WebseriesBannerResource($banner),
            'blocks' => WebseriesBlocksResource::collection($blocks),
            'webseries_id' => $webseries->id
        ]);
    }*/
    public function webseriesbannerlist(Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        $data=Banner::getWebseriesApiList($user->id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        $list=$request->get('list');
        if($list){
            return WebseriesBannerResource::collection($data);
        }else{
            return new WebseriesBannerResource($data);
        }
            /* Multi Banner */
            /*$user = Auth::user();
            $data = Banner::getApiList($user->id);

            if (empty($data) || $data->isEmpty()) {
                return $this->error('', "No Records Found!", 200);
            }

            return BannerResource::collection($data);*/
    }
    public function webseriesblockslist(Request $request)
    {
        //Force user to buy plan start
        // $plan=Subscription::getPlan();
        // if(empty($plan))
        //     return $this->error('', "Plan expired", 200);
        //Force user to buy plan end

        $user=Auth::user();
        $data=Blocks::getwebseriesApiList($user->id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);

        return WebseriesBlocksResource::collection($data);
    }
    public function seasonepisodebannerlist(Request $request, $webseries_id)
    {
        $user = Auth::user();
        $data=Banner::getWebseriesWatchApiList($user->id, $webseries_id);
        if(empty($data))
            return $this->error('', "No Records Found!", 200);
        return new WebseriesBannerWatchResource($data);
        /*$list=$request->get('list');
        if($list){
            return WebseriesBannerWatchResource::collection($data);
        }else{
            return new WebseriesBannerWatchResource($data);
        }*/

        /*$data = Banner::getWebseriesWatchApiList($user->id, $webseries_id);

        if (empty($data)) {
            return $this->error('', "No Records Found!", 200);
        }

        return new WebseriesBannerWatchResource($data);*/
    }
    public function webserieswatchdetail(Request $request, $webseries_id)
    {
        $user = Auth::user();

        $webseries = Webseries::with([
            'seasons' => function ($q) { $q->orderBy('id', 'asc'); },
            'seasons.episodes' => function ($q) { $q->orderBy('id', 'asc'); },
            'seasons.episodes.image',
            'seasons.episodes.medium',
            'seasons.episodes.thumbnail',
            'seasons.episodes.episode_users' => function ($q) use ($user) {
                $q->where('user_id', $user->id);
            },
        ])->findOrFail($webseries_id);

        return new WebseriesWatchDetailResource($webseries);
    }
    public function webseriesWatchBlocks(Request $request, $webseries_id)
    {
        $user = Auth::user();

        $data = Blocks::getwebseriesWatchApiList($user->id, $webseries_id);

        if (empty($data)) {
            return $this->error('', "No Records Found!", 200);
        }

        return WebseriesBlocksResource::collection($data);
    }
    /*public function show($id)
    {
        $series = Webseries::with([
            'thumbnail:id,urlkey',
            'image:id,urlkey',
            'seasons.episodes.thumbnail:id,urlkey',
            'seasons.episodes.image:id,urlkey'
        ])->findOrFail($id);

        return view('webseries.show', compact('series'));
    }*/
}
