<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

class MoviesUsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $gLabel=User::groupLabel();
        $gSlug=User::groupSlug();
        return [
            'group' => $this->group,
            'group_label' => empty($gLabel[$this->group])?'Unknown':$gLabel[$this->group],
            'group_slug' => empty($gSlug[$this->group])?'unknown':$gSlug[$this->group],
            'cast' => new CastResource($this->users)
        ];
    }
}