<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParseLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['league', 'status', 'output', 'started_at', 'finished_at'];

    protected $casts = [
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];
}
