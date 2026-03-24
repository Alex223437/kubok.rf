<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title',
        'url',
        'code',
        'html_xs',
        'html_md',
        'html_xl',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];
}
