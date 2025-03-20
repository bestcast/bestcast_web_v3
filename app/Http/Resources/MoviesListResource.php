<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;

class MoviesListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'movie_access' => $this->movie_access,
            'title' => $this->title,
            'topten' => $this->topten,
            'trailer' => empty($this->trailer_url_480p)?$this->trailer_url:$this->trailer_url_480p,
            'certificate' => $this->certificate,
            'duration' => Lib::formatSecondsToHoursMinutes($this->duration),
            'tag_text' => empty($this->tag_text)?'':str_replace(", ",'<i></i>',$this->tag_text),
            'published_date' => $this->published_date,
            'release_date' => $this->release_date,
            'userlist' => 0,
            'userlike' => 0,
            'image' => empty($this->image)?'':$this->image->urlkey,
            'medium' => empty($this->medium)?'':$this->medium->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'portraitsmall' => empty($this->portraitsmall)?'':$this->portraitsmall->urlkey,
            'portrait' => empty($this->portrait)?'':$this->portrait->urlkey,
            'usermovies' => (!empty($this->usermovies) && count($this->usermovies))?new UsermoviesResource($this->usermovies[0]):(object)[]
        ];
    }
}