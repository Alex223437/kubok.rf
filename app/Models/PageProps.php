<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageProps extends Model
{
    protected $fillable = [
        'page_id',
        'payload',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
