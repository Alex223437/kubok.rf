<?php

namespace Database\Seeders;

use App\Models\Option;
use App\Services\OptionsService;
use Illuminate\Database\Seeder;

class OptionsSeeder extends Seeder
{
    /**
     * @see \App\Models\Option
     * Run the database seeds.
     * php artisan db:seed --class=OptionsSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        \App\Models\Option::truncate();
        $items = $this->getData();
        foreach ($items as $key => $item) {
            $item['active'] = true;
            $item['sort'] = $key * 10 + 10;
            \App\Models\Option::create($item);
        }
    }

    public function getData(): array
    {
        return [
            [
                'code' => OptionsService::CACHE_ENABLED,
                'title' => 'Кеширование',
                'enabled' => true,
                'type' => Option::TYPE_BOOLEAN,
            ],
            [
                'code' => OptionsService::META_TITLE,
                'title' => 'Meta title',
                'value' => 'Кубки Fonbet META_TITLE',
            ],
            [
                'code' => OptionsService::META_DESCRIPTION,
                'title' => 'Meta description',
                'value' => 'Кубки Fonbet META_DESCRIPTION',
            ],
            [
                'code' => OptionsService::META_KEYWORDS,
                'title' => 'Meta keywords',
                'value' => 'Кубки Fonbet META_KEYWORDS',
            ],
        ];
    }
}
