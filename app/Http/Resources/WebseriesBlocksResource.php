<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebseriesBlocksResource extends JsonResource
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
            'title' => $this->title,
            //'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            //'movies' => (string)$this->movies
            'movies' => BlocksWebseriesResource::collection($this->webseries),
        ];
    }
}
