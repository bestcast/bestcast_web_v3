<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MoviesSubtitleResource extends JsonResource
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
            'active' => $this->is_active,
            'label' => $this->label,
            'url' => $this->url
        ];
    }
}