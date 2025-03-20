<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;
use App\Models\Subscription;

class MoviesTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user=auth()->user();
        $plan=Subscription::getPlan();
        $video_url='';
        if(empty($plan->video_quality)){
            $video_url=$this->video_url_480p;
            $video_url=empty($video_url)?$this->video_url_720p:$video_url;
            $video_url=empty($video_url)?$this->video_url_1080p:$video_url;
            $video_url=empty($video_url)?$this->video_url:$video_url;
        }else{
            if($plan->video_quality==1){
                $video_url=$this->video_url_720p;
                $video_url=empty($video_url)?$this->video_url_1080p:$video_url;
                $video_url=empty($video_url)?$this->video_url:$video_url;
            }elseif($plan->video_quality==2){
                $video_url=$this->video_url_1080p;
                $video_url=empty($video_url)?$this->video_url:$video_url;
            }else{
                $video_url=empty($video_url)?$this->video_url:$video_url;
            }
        }
        $video_url=empty($video_url)?$this->video_url:$video_url;
        $moviesource=$this->moviesource;
        $video_url_1080p=empty($this->video_url_1080p)?$video_url:$this->video_url_1080p;
        $video_url_720p=empty($this->video_url_720p)?$video_url_1080p:$this->video_url_720p;
        $video_url_480p=empty($this->video_url_480p)?$video_url_720p:$this->video_url_480p;

        $jsonData = '{
            "id": "0",
            "movie_id": '.(string)$this->id.',
            "mylist": 0,
            "likes": 0,
            "watch_time": "0",
            "watching": 0,
            "watched": 0,
            "watched_percent": 0,
            "viewed": 0
        }';
        $arrayData = json_decode($jsonData, true);


        $usermoviesdetails=(!empty($this->usermovies) && count($this->usermovies))?new UsermoviesResource($this->usermovies[0]):$arrayData;
        if(!empty($user) && !empty($this->movie_access)){

        }else{
            if(empty($user) || $user->plan==0 ){
                $video_url='';
                $moviesource='';
                $video_url_480p='';
                $video_url_720p='';
                $video_url_1080p='';
                $usermoviesdetails=$arrayData;
            }
        }

        return [
            'id' => (string)$this->id,
            'video_url' => $video_url,
            'moviesource' => $moviesource,
            'video_url_480p' => $video_url_480p,
            'video_url_720p' => $video_url_720p,
            'video_url_1080p' => $video_url_1080p,
            'usermovies' => $usermoviesdetails
        ];
    }
}

