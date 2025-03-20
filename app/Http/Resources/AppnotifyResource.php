<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;

class AppnotifyResource extends JsonResource
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
            'is_read' => isset($this->is_read)?$this->is_read:'',
            'title' => $this->title,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'created_at' => date("Y-m-d h:i:s",strtotime($this->created_at)),
            'created_text' => Lib::datediff(date("Y-m-d h:i:s",strtotime($this->created_at))),
            'movie' => !empty($this->movie)?new MoviesListResource($this->movie):''
        ];
    }
}