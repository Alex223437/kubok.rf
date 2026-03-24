<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageProp extends Model
{
    protected $fillable = [
        'page_id',
        'prop_id',
        'value',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
