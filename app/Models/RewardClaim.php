<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RewardClaim extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'bank_name',
        'account_no',
        'ifsc',
        'branch',
        'mobile_no',
        'upi'
    ];
}
