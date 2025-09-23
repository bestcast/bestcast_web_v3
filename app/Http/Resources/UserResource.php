<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\UsersDevice;
use App\Models\Subscription;
use Auth;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!empty($this->plan_expiry) && strtotime($this->plan_expiry) > strtotime(date('Y-m-d H:i:s', strtotime('-12 hours')))){
            $plan_status=1;
        }else{
            $plan_status=0;
        }

        $plan_device_status=1;
        $plan=Subscription::getPlan();
        if(!empty($plan)){
            $user=Auth::user();
            if(!empty($user->id)){
                $device=UsersDevice::getApiList($user);
                if(!empty($device) && !empty($plan->device_limit)){
                    if(count($device)>$plan->device_limit){
                        $plan_device_status=0;
                    }
                }
            }
        }

        return [
            'id' => (string)$this->id,
            'status' => empty($this->status)?0:1,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            'title' => $this->title,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'dob' => $this->dob,
            'gender' => $this->gender,
            'plan' => $this->plan,
            'plan_expiry' => $this->plan_expiry,
            'plan_status' => $plan_status,
            'plan_device_status' => $plan_device_status,
            'photo' => $this->photo,
            'otp' => $this->otp,
            'tvcode' => $this->tvcode,
            'tvcode_status' => $this->tvcode_status,
            'referal_code' => $this->referal_code,
            'credits_used' => $this->credits_used,
            'refferer' => $this->refferer,
            'email_verified_at' => $this->email_verified_at,
            'phone_verified_at' => $this->phone_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}