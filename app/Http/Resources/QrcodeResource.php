<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;
use App\Models\Transaction;

class QrcodeResource extends JsonResource
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
            'id'        =>  (string)$this->id,
            'qrcode'    =>  $this->tvcode,
            'status'    =>  $this->tvcode_status,
            'user_id'   =>  $this->id
        ];
    }
}