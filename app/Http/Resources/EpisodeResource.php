<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Lib;

class EpisodeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            /*'id' => (string)$this->id,
            'title' => $this->title,
            'video_url' => $this->video_url,
            'duration' => $this->duration,
            'image' => optional($this->image)->urlkey,*/
            'id' => (string) $this->id,
            'title' => $this->title,
            'urlkey' => $this->urlkey,
            /*'title' => $episode->title,*/
            'movie_access' => $this->movie_access,
            'content' => $this->content,
            'content_plain' => strip_tags($this->content),
            'published_date' => $this->published_date,
            'release_date' => $this->release_date,
            /*'image' => empty($this->image)?'':$this->image->urlkey,*/
            'image' => empty($this->image)?'':$this->image->urlkey,
            'medium' => empty($this->medium)?'':$this->medium->urlkey,
            'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,
            /*'thumbnail' => empty($this->thumbnail)?'':$this->thumbnail->urlkey,*/
            'portraitsmall' => empty($this->portraitsmall)?'':$this->portraitsmall->urlkey,
            'portrait' => empty($this->portrait)?'':$this->portrait->urlkey,
            'duration' => $this->duration,
            'duration_text' => Lib::formatSecondsToHoursMinutes($this->duration),
            'certificate' => $this->certificate,
            'certificate_text' => $this->certificate_text,
            'tag_text' => empty($this->tag_text)?'':str_replace(", ",'<i></i>',$this->tag_text),
            'topten' => $this->topten,
            'trailer' => $this->trailer_url,
            'trailer_480p' => $this->trailer_url_480p,
            'trailer' => $this->trailer_url,
            'video_url' => $this->video_url,
            'moviesource' => $this->moviesource, //need to check
            'video_url_480p' => $this->video_url_480p,
            'video_url_720p' => $this->video_url_720p,
            'video_url_1080p' => $this->video_url_1080p,
            'subtitle_status' => $this->subtitle_status,
            'meta' => (!empty($this->meta) && count($this->meta))?$this->meta:'',
            'subtitle' => (!empty($this->subtitle) && count($this->subtitle))?EpisodeSubtitleResource::collection($this->subtitle):'',
            'genres' => (!empty($this->genres) && count($this->genres))?EpisodeGenresResource::collection($this->genres):'',
            'languages' => (!empty($this->languages) && count($this->languages))?EpisodeLanguagesResource::collection($this->languages):'',
            /*'casts' => (!empty($this->users) && count($this->users))?EpisodeUsersResource::collection($this->users):'',*/
            'casts' => (!empty($this->users) && count($this->users)) ? EpisodeUsersResource::collection( collect($this->users)->where('group', '!=', 3)): '',
            'related' => (!empty($this->related) && count($this->related))?EpisodeRelatedResource::collection($this->related):'',
            /*'usermovies' => $usermoviesdetails*/
            //'episode_user' => $episodeUserDetails
            /*'episode_user' => [
                'watch_time' => optional($this->episode_users->first())->watch_time ?? "0"
            ]*/
            // In EpisodeResource::toArray() — replace the episode_user block at the bottom:
            'episode_user' => [
                'watch_time'      => optional($this->episode_users->first())->watch_time ?? "0",
                'watched_percent' => optional($this->episode_users->first())->watched_percent ?? 0,
            ],
        ];
    }
}