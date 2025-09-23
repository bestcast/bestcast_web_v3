<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;
use App\Models\Subscription;

class MoviesResource extends JsonResource
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


        $arrayData=(object)[];
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
            'urlkey' => $this->urlkey,
            'title' => $this->title,
            'movie_access' => $this->movie_access,
            'content' => $this->content,
            'content_plain' => strip_tags($this->content),
            'published_date' => $this->published_date,
            'release_date' => $this->release_date,
            'image' => empty($this->image)?'':$this->image->urlkey,
            'medium' => empty($this->medium)?'':$this->medium->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'portraitsmall' => empty($this->portraitsmall)?'':$this->portraitsmall->urlkey,
            'portrait' => empty($this->portrait)?'':$this->portrait->urlkey,
            'duration' => $this->duration,
            'duration_text' => Lib::formatSecondsToHoursMinutes($this->duration),
            'certificate' => $this->certificate,
            'certificate_text' => $this->certificate_text,
            'tag_text' => empty($this->tag_text)?'':str_replace(", ",'<i></i>',$this->tag_text),
            'topten' => $this->topten,
            'trailer' => $this->trailer_url,
            'trailer_480p' => $this->trailer_url_480p,
            'trailer' => $this->trailer_url,
            'video_url' => $video_url,
            'moviesource' => $moviesource,
            'video_url_480p' => $video_url_480p,
            'video_url_720p' => $video_url_720p,
            'video_url_1080p' => $video_url_1080p,
            'subtitle_status' => $this->subtitle_status,
            'meta' => (!empty($this->meta) && count($this->meta))?$this->meta:'',
            'subtitle' => (!empty($this->subtitle) && count($this->subtitle))?MoviesSubtitleResource::collection($this->subtitle):'',
            'genres' => (!empty($this->genres) && count($this->genres))?MoviesGenresResource::collection($this->genres):'',
            'languages' => (!empty($this->languages) && count($this->languages))?MoviesLanguagesResource::collection($this->languages):'',
            'casts' => (!empty($this->users) && count($this->users))?MoviesUsersResource::collection($this->users):'',
            'related' => (!empty($this->related) && count($this->related))?MoviesRelatedResource::collection($this->related):'',
            'usermovies' => $usermoviesdetails
        ];
    }
}

