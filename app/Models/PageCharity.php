<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageCharity extends Model
{
    protected $fillable = [
        'page_id',
        'sort',
        'type',
        'title',
        'text',
        'img_id',
        'html',
        'active',
        'url',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
        'active' => 'bool',
    ];

    public function img()
    {
        return $this->belongsTo(FilePath::class, 'img_id');
    }
}
