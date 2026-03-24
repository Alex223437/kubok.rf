<?php

namespace App\Services;

use App\Models\Option;
use Illuminate\Support\Collection;

/**
 * Сервис для работы с настройками
 * @example $value = app(OptionsService::class)->get(OptionsService::CODE);
 */
class OptionsService
{
    const META_TITLE = 'meta_title';
    const META_DESCRIPTION = 'meta_description';
    const META_KEYWORDS = 'meta_keywords';
    const CACHE_ENABLED = 'cache_enabled';

    private static ?OptionsService $instance = null;
    /** @var Collection<Option> */
    private Collection $options;

    private function __construct()
    {
        $options = \Cache::store('file')->remember('options', 3600 / 2, function () {
            return $this->getOptions();
        });
        $this->options = $options;
    }

    private function getOptions(): Collection|null
    {
        return Option::all()->where('active', true)->keyBy('code');
        //return Option::all()->where('active', true)->pluck('value', 'code');
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function isCacheEnabled(): bool
    {
        return $this->getBool(static::CACHE_ENABLED);
    }

    public function get(string $code, string $default = ''): string
    {
        if ($opt = $this->options->get($code)) {
            return (string)$opt->value ?: $default;
        }
        return $default;
    }

    public function getBool(string $code): bool
    {
        if ($opt = $this->options->get($code)) {
            return (bool)$opt->enabled;
        }
        return false;
    }

    public function getDefaultMeta(): array
    {
        return [
            'meta_title' => (string)$this->options->get(OptionsService::META_TITLE)->value,
            'meta_description' => (string)$this->options->get(OptionsService::META_DESCRIPTION)->value,
            'meta_keywords' => (string)$this->options->get(OptionsService::META_KEYWORDS)->value,
        ];
    }
}
