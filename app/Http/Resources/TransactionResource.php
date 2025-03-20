<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use Lib;
use App\Models\Transaction;

class TransactionResource extends JsonResource
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
            'status'    =>  $this->status,
            'status_text'    =>  Transaction::status($this->status),
            'user_id'   =>  $this->user_id,
            'razorpay_order_id'   =>  $this->razorpay_order_id,
            'price'   =>  $this->price,
            'title'     =>  $this->title
        ];
    }
}