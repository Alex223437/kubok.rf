<?php

namespace Database\Seeders;

use App\Models\PageBlock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageBlocksSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageBlock
     * Run the database seeds.
     * php artisan db:seed --class=PageBlocksSeeder
     */
    public function run(): void
    {
        //// WARNING!
        \App\Models\PageBlock::truncate();
        $items = $this->getData();

        foreach ($items as $pageId => $item) {
            foreach ($item as $i => $blockFields) {
                $blockFields['sort'] = $i * 10 + 10;
                $blockFields['page_id'] = $pageId;
                PageBlock::create($blockFields);
            }
            unset($key);
        }
    }

    public function getData(): array
    {
        //@formatter:off
        return [
            0 => [],
            1 => [
                ['title' => 'О турнире'],
                ['title' => 'Благотворительность'],
            ],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
            8 => [],
            9 => [],
        ];
    }
}


























