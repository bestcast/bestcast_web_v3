<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
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
            'title' => $this->title,
            'image' => empty($this->image)?'':$this->image->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'logo' => empty($this->logo)?'':$this->logo->urlkey,
            'movies' => new MoviesResource($this->movies),
        ];
    }
}