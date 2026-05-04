<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserEpisodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'webseries_id' => $this->webseries_id,
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
