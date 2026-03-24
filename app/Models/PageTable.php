<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTable extends Model
{
    const TYPES = [
        '' => '',
        'rpl' => 'РПЛ',
        'region' => 'Регионы',
        'east' => 'Восток',
        'west' => 'Запад',
        'man' => 'Мужчины',
        'woman' => 'Женщины',
    ];
    protected $fillable = [
        'page_id',
        'sort',
        'title',
        'short',
        'type',
        'active',
        'payload',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    protected $casts = [
        'payload' => 'array',
        'active' => 'bool',
    ];
}
