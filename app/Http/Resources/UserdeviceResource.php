<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserdeviceResource extends JsonResource
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
            'token_id' => $this->token_id,
            'profile_id' => $this->profile_id,
            'ip_address' => $this->ip_address,
            'device' => $this->device,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}