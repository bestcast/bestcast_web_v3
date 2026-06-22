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
//use App\Http\Resources\WebseriesResource;
//use App\Http\Resources\WebseriesBannerResource;
use App\Http\Resources\BlocksResource;
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
        $post = Post::where('urlkey','home')->first();
        $meta=$post->meta->pluck('value','path');

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

        // Get webseries_id from episode's season
        $webseries_id = $episode->season->webseries_id;
        $nextEpisode = Episode::where('season_id', $episode->season_id)
            ->where('id', '>', $episode_id)
            ->orderBy('id', 'asc')
            ->first();

        // If no next episode in same season, check next season
        if (!$nextEpisode) {
            $nextSeason = Season::where('webseries_id', $webseries_id)
                ->where('id', '>', $episode->season_id)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextSeason) {
                $nextEpisode = Episode::where('season_id', $nextSeason->id)
                    ->orderBy('id', 'asc')
                    ->first();
            }
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
            'nextEpisode'  => $nextEpisode,
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

        if (!$webseries) {
            abort(404);
        }
        return view('webseries.webserieswatch', [
            'webseries'  => $webseries,
            'episode'    => $latestEpisode,       // current episode
            'season'     => $latestSeason, // current season
        ]);
    }
    public function webseriesblockslist(Request $request)
    {
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
    public function getwebseriesdetail($webseriesId, Request $request)
    {
        $user      = Auth::user();
        $profileId = $request->profile_id ?? 0;
     
        // ── Load webseries with image + genres ───────────────────────────
        $webseries = \App\Models\Webseries::with([
            'image',
            'thumbnail',
            'medium',
            'genres',
            'languages',
        ])->find($webseriesId);
     
        if (!$webseries) {
            return response()->json(['data' => null, 'message' => 'Not found'], 404);
        }

        // Get unique cast members for this webseries (group 3-7 = crew/cast)
        $castGroups = [
            //3 => 'Producer',
            4 => 'Director', 
            5 => 'Actor',
            6 => 'Actress',
            7 => 'Music Director',
        ];

        // Get episode IDs for this webseries
        $wsEpisodeIds = \App\Models\Episode::whereHas('season', function($q) use ($webseriesId) {
            $q->where('webseries_id', $webseriesId);
        })->pluck('id');

        // Get cast from episode_users where group is 3-7
        $castMembers = \App\Models\EpisodeUsers::whereIn('episode_id', $wsEpisodeIds)
            ->whereIn('group', array_keys($castGroups))
            ->with('user') // load the user relation
            ->get()
            ->unique('user_id') // deduplicate by person
            ->map(function($eu) use ($castGroups) {
                return [
                    'name'       => optional($eu->user)->name ?? '',
                    //'photo'      => optional($eu->user)->profile_photo ?? '',
                    'group'      => $eu->group,
                    'group_name' => $castGroups[$eu->group] ?? '',
                ];
            })
            ->values();
        // ── Find last watched episode (resume logic) ──────────────────────
        $resumeEpisodeId    = null;
        $resumeDate         = null;
        $firstEpisodeId     = null;
     
        if ($user) {
            // Get all episode IDs that belong to this webseries
            $episodeIds = \App\Models\Episode::whereHas('season', function ($q) use ($webseriesId) {
                $q->where('webseries_id', $webseriesId);
            })->pluck('id');
     
            $lastWatched = \App\Models\EpisodeUsers::where('user_id', $user->id)
                //->where('profile_id', $profileId)
                ->whereIn('episode_id', $episodeIds)
                //->orderBy('updated_at', 'desc')
                ->first();
     
            if ($lastWatched) {
                $resumeEpisodeId = $lastWatched->episode_id;
                $resumeDate      = optional($lastWatched->updated_at)->format('j M');
            }
            // Find episode number within its season for display
            $episodeNumber = null;
            if ($resumeEpisodeId) {
                $resumeEp = \App\Models\Episode::find($resumeEpisodeId);
                if ($resumeEp) {
                    $episodeNumber = \App\Models\Episode::where('season_id', $resumeEp->season_id)
                        ->where('id', '<=', $resumeEpisodeId)
                        ->count();
                }
            }
        }
     
        // ── First episode of first season (fallback) ──────────────────────
        $firstSeason = \App\Models\Season::where('webseries_id', $webseriesId)
            ->orderBy('id', 'asc')->first();
     
        if ($firstSeason) {
            $firstEpisode   = \App\Models\Episode::where('season_id', $firstSeason->id)
                ->orderBy('id', 'asc')->first();
            $firstEpisodeId = optional($firstEpisode)->id;
        }
     
        // ── Build seasons + episodes with watch progress ──────────────────
        $seasons = \App\Models\Season::where('webseries_id', $webseriesId)
            ->orderBy('id', 'asc')
            ->with(['episodes' => function ($q) {
                $q->orderBy('id', 'asc')
                  ->with(['thumbnail', 'image']);
            }])
            ->get()
            ->map(function ($season) use ($user, $profileId) {
                return [
                    'id'       => $season->id,
                    'title'    => $season->title,
                    'episodes' => $season->episodes->map(function ($ep) use ($user, $profileId) {
     
                        $watchedPct = 0;
                        if ($user) {
                            $userEp = \App\Models\UsersEpisodes::where('user_id', $user->id)
                                ->where('profile_id', $profileId)
                                ->where('episode_id', $ep->id)
                                ->first();
                            $watchedPct = $userEp ? ($userEp->watched_percent ?? 0) : 0;
                        }
     
                        return [
                            'id'              => $ep->id,
                            'title'           => $ep->title,
                            'content'         => strip_tags($ep->content ?? ''),
                            'duration_text'   => \Lib::formatSecondsToHoursMinutes($ep->duration),
                            'duration'        => $ep->duration,
                            'release_date'    => $ep->release_date,
                            // thumbnail path: the JS prepends _homeUrl + '/'
                            'thumbnail'       => optional($ep->thumbnail)->urlkey
                                             ?? optional($ep->image)->urlkey
                                             ?? '',
                            'watched_percent' => $watchedPct,
                        ];
                    })->values(),
                ];
            });
     
        // ── Webseries-level info (for banner hero section) ────────────────
        // tag_text comes from genres, published_date = year
        $tagText = $webseries->genres->pluck('title')->implode(', ');
     
        return response()->json([
            'data' => [
                // Webseries info (for hero/banner)
                'id'                   => $webseries->id,
                'title'                => $webseries->title,
                'content'              => strip_tags($webseries->content ?? ''),
                'published_date'       => optional($webseries->published_date)->format('Y') ?? '',
                'certificate'          => $webseries->certificate ?? '',
                'tag_text'             => $tagText,
                // Image paths (JS prepends _homeUrl + '/')
                'thumbnail'            => optional($webseries->thumbnail)->urlkey
                                       ?? optional($webseries->medium)->urlkey
                                       ?? optional($webseries->image)->urlkey
                                       ?? '',
                'image'                => optional($webseries->image)->urlkey ?? '',
     
                // Resume / play
                'resume_episode_id'    => $resumeEpisodeId,
                'resume_date'          => $resumeDate,
                'first_episode_id'     => $firstEpisodeId,
     
                // Seasons + episodes
                'seasons'              => $seasons,
                'episode_number' => $episodeNumber,
                'casts' => $castMembers,
            ]
        ]);
    }

}
