<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;

class WebseriesContinueWatchingResource extends JsonResource
{
    public function toArray($request)
    {
        $webseries = $this->season->webseries;
        $userEpisode = $this->userepisodes->first();

        return [
            'id'              => $webseries->id,          // for "More Info" / details popup
            'episode_id'      => $this->id,                // for resuming playback
            'title'           => $webseries->title,
            'image'           => optional($webseries->thumbnail)->urlkey,
            'trailer'         => $webseries->trailer ?? null,
            'certificate'     => $webseries->certificate ?? null,
            'topten'          => $webseries->topten ?? 0,
            'watched_percent' => $userEpisode->watched_percent ?? 0,
            'usermovies'      => [
                'mylist' => $userEpisode->mylist ?? 0,
                'likes'  => $userEpisode->likes ?? 0,
            ],
            'watch_url'       => url('/webserieswatchepisode/' . $this->id),
        ];
    }
}
