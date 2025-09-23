<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;

class MenuResource extends JsonResource
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
            'name' => $this->name,
            'show_loggedin_user' => $this->show_loggedin_user,
            'url' => !empty($this->post)?Lib::url($this->post->urlkey):$this->url,
        ];
    }
}