<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeasonResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,

            'episodes' => !empty($this->episodes)
                ? EpisodeResource::collection($this->episodes)
                : []
        ];
    }
}
