<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;
use App\Models\Subscription;

class SubscriptionResource extends JsonResource
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
            'urlkey' => $this->urlkey,
            'title' => $this->title,
            'content' => $this->content,
            'content_plain' => strip_tags($this->content),
            'before_price' => $this->before_price,
            'price' => $this->price,
            'tagtext' => $this->tagtext,
            'sortorder' => $this->sortorder,
            'razorpay_id' => $this->razorpay_id,
            'duration_text' => ucwords(Subscription::getDurationText($this))
        ];
    }
}