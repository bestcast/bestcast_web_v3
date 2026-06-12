<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Lib;

class WebseriesWatchDetailResource extends JsonResource
{
    public function toArray($request)
    {
        // Find latest season + episode for Resume button
        $latestSeason  = $this->seasons->last();
        $latestEpisode = optional($latestSeason)->episodes->last();

        return [
            'id'    => (string) $this->id,
            'title' => $this->title,

            // Resume info — latest episode the user was watching
            'resume' => [
                'episode_id'      => (string) optional($latestEpisode)->id,
                'season_id'       => (string) optional($latestSeason)->id,
                'season_title'    => optional($latestSeason)->title ?? '',
                'episode_title'   => optional($latestEpisode)->title ?? '',
                'watch_time'      => optional($latestEpisode?->episode_users->first())->watch_time ?? "0",
                'watched_percent' => optional($latestEpisode?->episode_users->first())->watched_percent ?? 0,
                'release_date'    => optional($latestEpisode)->release_date,
            ],

            // All seasons with their episodes
            'seasons' => $this->seasons->map(function ($season) {
                return [
                    'id'       => (string) $season->id,
                    'title'    => $season->title,
                    'season_number' => $season->season_number,
                    'episodes' => $season->episodes->map(function ($ep) {
                        $epUser = $ep->episode_users->first();
                        return [
                            'id'              => (string) $ep->id,
                            'title'           => $ep->title,
                            'urlkey'          => $ep->urlkey,
                            'content'         => $ep->content,
                            'image'           => empty($ep->image) ? '' : $ep->image->urlkey,
                            'medium'          => empty($ep->medium) ? '' : $ep->medium->urlkey,
                            'thumbnail'       => empty($ep->thumbnail) ? '' : $ep->thumbnail->urlkey,
                            'duration'        => Lib::formatSecondsToHoursMinutes($ep->duration),
                            'duration_secs'   => $ep->duration,
                            'release_date'    => $ep->release_date,
                            'published_date'  => $ep->published_date,
                            'certificate'     => $ep->certificate ?? '',
                            'movie_access'    => $ep->movie_access,
                            'trailer'         => empty($ep->trailer_url_480p) ? ($ep->trailer_url ?? '') : $ep->trailer_url_480p,
                            'video_url'       => $ep->video_url ?? '',
                            'episode_user'    => [
                                'watch_time'      => $epUser->watch_time ?? "0",
                                'watched_percent' => $epUser->watched_percent ?? 0,
                                'watched'         => $epUser->watched ?? 0,
                            ],
                        ];
                    })->values(),
                ];
            })->values(),
        ];
    }
}