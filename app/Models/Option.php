<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    const TYPE_STRING = 'string';
    const TYPE_NUMBER = 'number';
    const TYPE_BOOLEAN = 'bool';
    const TYPE_ENUM = 'enum';

    public $timestamps = false;

    protected $fillable = [
        'sort',
        'code',
        'title',
        'type',
        'value',
        'payload',
        'active',
        'enabled',
    ];

    protected static function booted()
    {
        static::updated(function (Model $model) {
            \Cache::store('file')->forget($model->getTable());
            \Cache::store('file')->forget($model->getTable() . '_' . $model->id);
        });
    }

    protected $casts = [
        'payload' => 'array',
        'active' => 'bool',
        'enabled' => 'bool',
    ];

    public static function getTypes(): array
    {
        return [
            self::TYPE_STRING,
            self::TYPE_NUMBER,
            self::TYPE_BOOLEAN,
            self::TYPE_ENUM,
        ];
    }
}
