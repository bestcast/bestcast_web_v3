<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WebseriesWatchBannerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'image' => empty($this->image)?'':$this->image->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'logo' => empty($this->logo)?'':$this->logo->urlkey,
            //'movies' => new WebseriesResource($this->webseries),
            'webseries_id' => $this->webseries->id ?? null,
						'episode_id' => optional($this->webseries->firstSeason->firstEpisode)->id,
        ];
    }
}
