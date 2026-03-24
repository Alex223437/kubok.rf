<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PageInfo extends Model
{
    protected $fillable = [
        'page_id',
        'sort',
        'type',
        'title',
        'img_id',
        'banner_id',
        'text',
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

    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class, 'id', 'banner_id');
    }
}
