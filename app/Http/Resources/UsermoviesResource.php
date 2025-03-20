<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UsermoviesResource extends JsonResource
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
            'movie_id' => $this->movie_id,
            'mylist' => $this->mylist,
            'likes' => $this->likes,
            'watch_time' => $this->watch_time,
            'watching' => $this->watching,
            'watched' => $this->watched,
            'watched_percent' => $this->watched_percent,
            'viewed' => $this->viewed,
            //'movies' => new MoviesResource($this->movies),
        ];
    }
}