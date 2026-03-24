<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Page extends Model
{
    protected $fillable = [
        'sort',
        'active',
        'code',
        'type',
        'title',
        'subtitle',
        'description',
        'text',
        'html',
        'facts',
        'img_id',
        'picture_id',
        'logo_id',
        'banner_id',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'payload',
    ];

    protected static function booted()
    {
        static::updated(function (Model $model) {
            \Cache::store('file')->forget($model->getTable());
            \Cache::store('file')->forget($model->getTable() . '_' . $model->id);
        });
    }

    public function infos(): HasMany
    {
        return $this->hasMany(PageInfo::class)->orderBy('sort');
    }

    public function charities(): HasMany
    {
        return $this->hasMany(PageCharity::class)->orderBy('sort');
    }

    public function events(): HasMany
    {
        return $this->hasMany(PageEvent::class)->orderBy('sort');
    }

    public function moments(): HasMany
    {
        return $this->hasMany(PageMoment::class)->orderBy('sort');
    }

    public function tables(): HasMany
    {
        return $this->hasMany(PageTable::class)->orderBy('sort');
    }


    public function infosActive(): HasMany
    {
        return $this->infos()->where('active', true);
    }

    public function charitiesActive(): HasMany
    {
        return $this->charities()->where('active', true);
    }

    public function eventsActive(): HasMany
    {
        return $this->events()->where('active', true);
    }

    public function momentsActive(): HasMany
    {
        return $this->moments()->where('active', true);
    }

    public function tablesActive(): HasMany
    {
        return $this->tables()->where('active', true);
    }


    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class, 'id', 'banner_id');
    }

    public function bannerActive(): HasOne
    {
        return $this->hasOne(Banner::class, 'id', 'banner_id')->where('active', true);
    }

    public function img()
    {
        return $this->belongsTo(FilePath::class, 'img_id');
    }

    public function picture()
    {
        return $this->belongsTo(FilePath::class, 'picture_id');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    protected $casts = [
        'payload' => 'array',
        'active' => 'bool',
    ];

    public function getCssList(): string
    {
        if (is_array($this->payload) && isset($this->payload['css_code'])) {
            return (string)$this->payload['css_code'];
        }
        return '';
    }

    public function getCssPage(): string
    {
        return (string)((int)$this->getCssList() - 1);
    }

    public function hasPayloadValue(string $key): bool
    {
        if (is_array($this->payload) && isset($this->payload[$key])) {
            return !empty($this->payload[$key]);
        }
        return false;
    }

    public function getPayloadValue(string $key): string
    {
        if (is_array($this->payload) && isset($this->payload[$key])) {
            return (string)$this->payload[$key];
        }
        return '';
    }

}
