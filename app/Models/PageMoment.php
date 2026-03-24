<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageMoment extends Model
{
    protected $fillable = [
        'page_id',
        'sort',
        'type',
        'title',
        'text',
        'url',
        'img_id',
        'html',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];

    public function img()
    {
        return $this->belongsTo(FilePath::class, 'img_id');
    }
}
