<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UpcomingMatch extends Model
{
    protected $guarded = [];

    protected $casts = [
        'match_at' => 'datetime',
    ];
}
