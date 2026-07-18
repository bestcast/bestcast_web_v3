<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BmpEventQueue extends Model
{
    protected $table = 'bmp_event_queue';

    protected $fillable = [
        'event_id', 'event_type', 'payload', 'status', 'attempts', 'last_response',
    ];

    protected $casts = [
        'payload' => 'array',
        'attempts' => 'integer',
    ];
}
