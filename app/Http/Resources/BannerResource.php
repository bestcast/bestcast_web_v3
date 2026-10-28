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
        $isWebseries = (int) $this->type === 2;

        return [
            'id'           => (string) $this->id,
            'title'        => $this->title,
            'image'        => empty($this->image) ? '' : $this->image->urlkey,
            'thumbnail'    => empty($this->thumbnail) ? '' : $this->thumbnail->urlkey,
            'logo'         => empty($this->logo) ? '' : $this->logo->urlkey,
            'portraitsmall' => empty($this->portraitsmall)?'':$this->portraitsmall->urlkey,
            'portrait' => empty($this->portrait)?'':$this->portrait->urlkey,
            'is_webseries' => $isWebseries ? 1 : 0,
            'movies'       => $isWebseries
                ? $this->webseriesPayload()
                : new MoviesResource($this->movies),
        ];
    }

    private function webseriesPayload(): array
    {
        $webseries = $this->webseries;
        if (!$webseries) {
            return ['id' => '0', 'trailer' => '', 'is_webseries' => 1];
        }

        $season  = $webseries->seasons->first();
        $episode = $season ? $season->episodes->first() : null;

        // latest episode trailer first, then fall back to the webseries trailer
        $trailer = ($episode && !empty($episode->trailer_url))
            ? $episode->trailer_url
            : ($webseries->trailer_url ?? '');

        return [
            'id'           => (string) $webseries->id,
            'title'        => $webseries->title,
            'content'      => $webseries->content,
            'certificate'  => $webseries->certificate ?? '',
            'trailer'      => $trailer,
            'image'        => ($episode && $episode->image) ? $episode->image->urlkey : '',
            'is_webseries' => 1,
            'usermovies'   => ['mylist' => 0, 'likes' => 0, 'watched_percent' => 0],
        ];
    }
    /*public function toArray($request)
    {
        return [
            'id' => (string)$this->id,
            'title' => $this->title,
            'image' => empty($this->image)?'':$this->image->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            'logo' => empty($this->logo)?'':$this->logo->urlkey,
            'movies' => new MoviesResource($this->movies),
        ];
    }*/
}