<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardClaim extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'door_no',
        'street_name',
        'country',
        'state',
        'city',
        'pin_code',
        'mobile_no',
    ];
}
