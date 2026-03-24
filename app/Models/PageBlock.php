<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageBlock extends Model
{
    protected $fillable = [
        'page_id',
        'sort',
        'active',
        'title',
        'subtitle',
        'description',
        'img1',
        'img2',
        'url',
        'type',
        'html',
        'payload',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
