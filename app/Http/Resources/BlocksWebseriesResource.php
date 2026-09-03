<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lib;


class BlocksWebseriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    
    public function toArray($request)
    {
        $webseries = $this->webseries;

        if (!$webseries) {
            return [];
        }

        // Latest season → latest episode
        $latestSeason  = $webseries->seasons->first();  // already desc ordered
        $latestEpisode = optional($latestSeason)->episodes->first(); // already desc ordered

        if (!$latestEpisode) {
            return [
                'id'          => (string) $webseries->id,
                'title'       => $webseries->title,
                'trailer'     => '',
                'image'       => '',
                'thumbnail'   => '',
                'movie_access'=> 0,
                'seasons'     => [],
                'is_webseries' => true,
            ];
        }

        return [
            // webseries id for click → redirect to /webserieswatch/{webseries_id}
            'id'           => (string) $webseries->id,
            'title'        => $webseries->title,

            // Latest episode trailer for block autoplay preview
            'trailer'      => empty($latestEpisode->trailer_url_480p)
                                ? ($latestEpisode->trailer_url ?? '')
                                : $latestEpisode->trailer_url_480p,
            'image'        => empty($latestEpisode->image) ? '' : $latestEpisode->image->urlkey,
            'medium'       => empty($latestEpisode->medium) ? '' : $latestEpisode->medium->urlkey,
            'thumbnail'    => empty($latestEpisode->thumbnail) ? '' : $latestEpisode->thumbnail->urlkey,
            'movie_access' => $latestEpisode->movie_access,
            'certificate'  => $latestEpisode->certificate ?? '',
            'duration'     => Lib::formatSecondsToHoursMinutes($latestEpisode->duration),
            'tag_text'     => empty($latestEpisode->tag_text) ? '' : str_replace(", ", '<i></i>', $latestEpisode->tag_text),
            'topten'       => $latestEpisode->topten ?? 0,

            // All seasons + episodes for the detail/watch popup
            'seasons'      => $webseries->seasons->map(function ($season) {
                return [
                    'id'       => (string) $season->id,
                    'title'    => $season->title,
                    'episodes' => $season->episodes->map(function ($ep) {
                        return [
                            'id'           => (string) $ep->id,
                            'title'        => $ep->title,
                            'urlkey'       => $ep->urlkey,
                            'trailer'      => empty($ep->trailer_url_480p) ? ($ep->trailer_url ?? '') : $ep->trailer_url_480p,
                            'image'        => empty($ep->image) ? '' : $ep->image->urlkey,
                            'thumbnail'    => empty($ep->thumbnail) ? '' : $ep->thumbnail->urlkey,
                            'duration'     => Lib::formatSecondsToHoursMinutes($ep->duration),
                            'movie_access' => $ep->movie_access,
                            'episode_user' => [
                                'watch_time'      => optional($ep->episode_users->first())->watch_time ?? "0",
                                'watched_percent' => optional($ep->episode_users->first())->watched_percent ?? 0,
                            ],
                        ];
                    })->values(),
                ];
            })->values(),
            'is_webseries' => true,
        ];
    }
}
