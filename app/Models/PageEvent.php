<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageEvent extends Model
{
    protected $fillable = [
        'page_id',
        'sort',
        'type',
        'title',
        'text',
        'team1',
        'team2',
        'img_id',
        'active',
        'url',
        'date_start',
        'date_end',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'active' => 'bool',
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];
}
