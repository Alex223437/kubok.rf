<?php

namespace Database\Seeders;

use App\Models\PageTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageTablesSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageTable
     * Run the database seeds.
     * php artisan db:seed --class=PageTablesSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        // \App\Models\PageTable::truncate();
        // $items = $this->getData();
        // foreach ($items as $pageId => $item) {
        //     foreach ($item as $i => $fields) {
        //         $fields['sort'] = $i * 10 + 10;
        //         $fields['page_id'] = $pageId;
        //         $fields['active'] = true;
        //         $fields['title'] = $fields['title'] ?? 'Группа ' . $i + 1;
        //         shuffle($fields['payload']['values']);
        //         PageTable::create($fields);
        //     }
        // }
    }

    public function getData(): array
    {
        $football = [
            ["title" => "Команда", "hint" => "Команда"],
            ["title" => "И", "hint" => "Сыграно матчей"],
            ["title" => "В/Н/П", "hint" => "Победы/Ничьи/Поражения"],
            ["title" => "М", "hint" => "Мячи (забито-пропущено)"],
            ["title" => "О", "hint" => "Очки"],
        ];

        $hockey = [
            ["title" => "Команда", "hint" => "Команда"],
            ["title" => "И", "hint" => "Сыграно матчей"],
            ["title" => "В/П", "hint" => "Победы/Поражения"],
            ["title" => "Ш", "hint" => "Шайбы (забито-пропущено)"],
            ["title" => "О", "hint" => "Очки"],
        ];

        $basket = [
            ["title" => "Команда", "hint" => "Команда"],
            ["title" => "И", "hint" => "Сыграно матчей"],
            ["title" => "В/П", "hint" => "Победы/Поражения"],
            ["title" => "М", "hint" => "Мячи (забито-пропущено)"],
            ["title" => "О", "hint" => "Очки"],
        ];

        $footballData = [
            "headers" => $football,
            "values" => [
                ["ЦСКА", 3, '2/0/3', "6-1", 6],
                ["Спартак", 2, '3/0/3', "1-5", 6],
                ["Зенит", 4, '1/0/2', "0-8", 9],
                ["Локомотив", 1, '1/2/1', "2-4", 4],
                ["Динамо", 3, '1/3/3', "1-7", 7],
            ],
        ];

        $hockeyData = [
            "headers" => $hockey,
            "values" => [
                ["СКА", 5, '3/1', "15-8", 12],
                ["ЦСКА", 4, '2/2', "12-10", 10],
                ["Авангард", 4, '1/1', "11-9", 9],
                ["Металлург", 3, '2/1', "9-7", 8],
                ["Ак Барс", 4, '1/2', "8-12", 7],
            ],
        ];

        $basketData = [
            "headers" => $basket,
            "values" => [
                ["ЦСКА", 6, '32/11', "15-8", 12],
                ["УНИКС", 5, '22/21', "12-10", 10],
                ["Зенит", 5, '12/11', "11-9", 9],
                ["Локомотив", 4, '22/11', "9-7", 8],
                ["Химки", 3, '12/21', "8-12", 7],
            ],
        ];

        //@formatter:off
        return [
            0 => [],
            1 => [],
            2 => [
                ['title' => 'Группа 1', 'payload' => $hockeyData, 'type' => 'rpl'],
                ['title' => 'Группа 2', 'payload' => $hockeyData, 'type' => 'rpl'],
                ['title' => 'Группа 3', 'payload' => $hockeyData, 'type' => 'rpl'],
                ['title' => 'Группа 4', 'payload' => $hockeyData, 'type' => 'rpl'],
                ['title' => 'Группа 1', 'payload' => $hockeyData, 'type' => 'region'],
                ['title' => 'Группа 2', 'payload' => $hockeyData, 'type' => 'region'],
                ['title' => 'Группа 3', 'payload' => $hockeyData, 'type' => 'region'],
                ['title' => 'Группа 4', 'payload' => $hockeyData, 'type' => 'region'],
            ],
            3 => [
                ['title' => 'Группа 1', 'payload' => $footballData, 'type' => 'east'],
                ['title' => 'Группа 2', 'payload' => $footballData, 'type' => 'east'],
                ['title' => 'Группа 3', 'payload' => $footballData, 'type' => 'east'],
                ['title' => 'Группа 4', 'payload' => $footballData, 'type' => 'east'],
                ['title' => 'Группа 1', 'payload' => $footballData, 'type' => 'west'],
                ['title' => 'Группа 2', 'payload' => $footballData, 'type' => 'west'],
                ['title' => 'Группа 3', 'payload' => $footballData, 'type' => 'west'],
                ['title' => 'Группа 4', 'payload' => $footballData, 'type' => 'west'],
            ],
            4 => [
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
            ],
            5 => [
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
            ],
            6 => [
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
                ['payload' => $basketData],
            ],
            7 => [],
            8 => [],
            9 => [],
        ];
    }
}


























