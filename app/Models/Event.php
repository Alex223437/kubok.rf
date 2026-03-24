<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'preview_img',
        'img',
        'type',
        'active',
        'date_start',
        'date_end',
    ];
}
