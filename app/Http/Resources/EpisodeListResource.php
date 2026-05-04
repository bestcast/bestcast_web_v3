<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;
use App\Models\Subscription;

class EpisodeListResource extends JsonResource
{
    public function toArray($request)
    {
        $user = auth()->user();
        $plan = Subscription::getPlan();

        // ✅ Safe null handling
        $season      = $this->seasons->sortByDesc('id')->first() ?? null;
        $episode     = optional($season)->episodes->sortByDesc('id')->first();
        $episodeUser = optional($episode)->episode_users->first();

        if (!$episode) {
            return [
                'id'           => (string) $this->id,
                'movie_access' => 0,
                'title'        => $this->title ?? '',
                'topten'       => 0,
                'trailer'      => '',
                'certificate'  => '',
                'duration'     => '',
                'tag_text'     => '',
                'published_date' => null,
                'release_date'   => null,
                'userlist'     => 0,
                'userlike'     => 0,
                'image'        => '',
                'medium'       => '',
                'thumbnail'    => '',
                'portraitsmall'=> '',
                'portrait'     => '',
                'userepisodes' => (object)[],
            ];
        }

        $video_url = '';
        if (empty($plan->video_quality)) {
            $video_url = $episode->video_url_480p;
            $video_url = empty($video_url) ? $episode->video_url_720p : $video_url;
            $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
            $video_url = empty($video_url) ? $episode->video_url : $video_url;
        } else {
            if ($plan->video_quality == 1) {
                $video_url = $episode->video_url_720p;
                $video_url = empty($video_url) ? $episode->video_url_1080p : $video_url;
                $video_url = empty($video_url) ? $episode->video_url : $video_url;
            } elseif ($plan->video_quality == 2) {
                $video_url = $episode->video_url_1080p;
                $video_url = empty($video_url) ? $episode->video_url : $video_url;
            } else {
                $video_url = $episode->video_url;
            }
        }
        $video_url = empty($video_url) ? $episode->video_url : $video_url;

        $episodeUserDetails = $episodeUser ? [
            'id'              => $episodeUser->id ?? 0,
            'episode_id'      => $episodeUser->episode_id ?? 0,
            'mylist'          => $episodeUser->mylist ?? 0,
            'likes'           => $episodeUser->likes ?? 0,
            'watch_time'      => $episodeUser->watch_time ?? "0",
            'watching'        => $episodeUser->watching ?? 0,
            'watched'         => $episodeUser->watched ?? 0,
            'watched_percent' => $episodeUser->watched_percent ?? 0,
            'viewed'          => $episodeUser->viewed ?? 0,
        ] : [
            'id'              => 0,
            'episode_id'      => $episode->id,
            'mylist'          => 0,
            'likes'           => 0,
            'watch_time'      => "0",
            'watching'        => 0,
            'watched'         => 0,
            'watched_percent' => 0,
            'viewed'          => 0,
        ];

        return [
            'id'             => (string) $episode->id,
            'movie_access'   => $episode->movie_access,
            'title'          => $episode->title,
            'topten'         => $episode->topten,
            'trailer'        => empty($episode->trailer_url_480p) ? $episode->trailer_url : $episode->trailer_url_480p,
            'certificate'    => $episode->certificate,
            'duration'       => Lib::formatSecondsToHoursMinutes($episode->duration),
            'tag_text'       => empty($episode->tag_text) ? '' : str_replace(", ", '<i></i>', $episode->tag_text),
            'published_date' => $episode->published_date,
            'release_date'   => $episode->release_date,
            'userlist'       => 0,
            'userlike'       => 0,
            'image'          => empty($episode->image) ? '' : $episode->image->urlkey,
            'medium'         => empty($episode->medium) ? '' : $episode->medium->urlkey,
            'thumbnail'      => empty($episode->thumbnail) ? '' : $episode->thumbnail->urlkey,
            'portraitsmall'  => empty($episode->portraitsmall) ? '' : $episode->portraitsmall->urlkey,
            'portrait'       => empty($episode->portrait) ? '' : $episode->portrait->urlkey,
            'userepisodes'   => (!empty($episode->episode_users) && count($episode->episode_users))
                                    ? new UserEpisodeResource($episode->episode_users->first())
                                    : (object)[],
        ];
    }
}
