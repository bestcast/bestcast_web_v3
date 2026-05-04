<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;
use App\Models\Subscription;

class WebseriesWatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
   public function toArray($request)
    {
        $user = auth()->user();
        $plan = Subscription::getPlan();

        // Video Logic
        $video_url = '';
        /*$season = $this->firstSeason ?? null;
        $episode = optional($season)->firstEpisode;*/
        $season  = $this->seasons->sortByDesc('id')->first();            // latest season
        $episode = optional($season)->episodes->sortByDesc('id')->first(); // latest episode of that season
        $episodeUser = optional($episode)->episode_users->first();

        if ($episode) {
            //dd($episode);exit;
            if (empty($plan->video_quality)) {
                $video_url = $episode->video_url_480p;
                $video_url = empty($video_url) ? $episode->video_url_720p : $video_url;
                $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
                $video_url = empty($video_url) ? $episode->video_url : $video_url;
            } else {
                if ($plan->video_quality == 1) {
                    $video_url = $episode->video_url_720p;
                    $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
                    $video_url = empty($video_url) ? $episode->video_url : $video_url;
                } elseif ($plan->video_quality == 2) {
                    $video_url = $episode->video_url_1080p;
                    $video_url = empty($video_url) ? $episode->video_url : $video_url;
                } else {
                    $video_url = $episode->video_url;
                }
            }
        }
        $video_url=empty($video_url)?$episode->video_url:$video_url;
        $moviesource=$this->moviesource;
        $video_url_1080p = $episode->video_url_1080p ?? $video_url;
        $video_url_720p  = $episode->video_url_720p ?? $video_url_1080p;
        $video_url_480p  = $episode->video_url_480p ?? $video_url_720p;

        $arrayData=(object)[];
        $jsonData = '{
            "id": "0",
            "webseries_id": '.(string)$this->id.',
            "mylist": 0,
            "likes": 0,
            "watch_time": "0",
            "watching": 0,
            "watched": 0,
            "watched_percent": 0,
            "viewed": 0
        }';
        $arrayData = json_decode($jsonData, true);
        
        /*$usermoviesdetails=(!empty($this->usermovies) && count($this->usermovies))?new UsermoviesResource($this->usermovies[0]):$arrayData;*/
        $episodeUserDetails = $episodeUser ? [
                                    'id' => $episodeUser->id ?? 0,
                                    'episode_id' => $episodeUser->episode_id ?? 0,
                                    'mylist' => $episodeUser->mylist ?? 0,
                                    'likes' => $episodeUser->likes ?? 0,
                                    'watch_time' => $episodeUser->watch_time ?? "0",
                                    'watching' => $episodeUser->watching ?? 0,
                                    'watched' => $episodeUser->watched ?? 0,
                                    'watched_percent' => $episodeUser->watched_percent ?? 0,
                                    'viewed' => $episodeUser->viewed ?? 0,
                                ] : [
                                    'id' => 0,
                                    'episode_id' => $episode ? $episode->id : 0,
                                    'mylist' => 0,
                                    'likes' => 0,
                                    'watch_time' => "0",
                                    'watching' => 0,
                                    'watched' => 0,
                                    'watched_percent' => 0,
                                    'viewed' => 0
                                ];
        if(!empty($user) && !empty($this->movie_access)){

        }else{
            if(empty($user) || $user->plan==0 ){
                $video_url='';
                $moviesource='';
                $video_url_480p='';
                $video_url_720p='';
                $video_url_1080p='';
                /*$usermoviesdetails=$arrayData;*/
                $episodeUserDetails = $episodeUserDetails;
            }
        }
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'image'  => $episode ? (empty($episode->image) ? '' : $episode->image->urlkey) : '',
            'trailer'=> $episode->trailer_url ?? '',       // ← JS reads data.movies.trailer
            'seasons' => !empty($this->seasons)
                ? SeasonResource::collection($this->seasons)
                : [],
            /*'seasons' => !empty($this->seasons)
                ? SeasonResource::collection($this->seasons)
                : []*/
        ];
    }
}

