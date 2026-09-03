<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlocksResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $isWebseries = str_contains(strtolower($this->title ?? ''), 'webseries');
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            //'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            //'movies' => (string)$this->movies
            //'movies' => BlocksMoviesResource::collection($this->movies),
            'movies' => $isWebseries
                ? BlocksWebseriesResource::collection($this->movies) 
                : BlocksMoviesResource::collection($this->movies),   
        ];
    }
}