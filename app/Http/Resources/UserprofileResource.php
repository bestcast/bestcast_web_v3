<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserprofileResource extends JsonResource
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
            'autoplay' => $this->autoplay,
            'is_child' => $this->is_child,
            'last_login' => $this->last_login,
            'language' => $this->language,
            'is_have_pin' => empty($this->pin)?0:1,
            'profileicon' => new ProfileiconResource($this->profileicon)
        ];
    }
}