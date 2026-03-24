<?php

namespace Database\Seeders;

use App\Models\PageEvent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageEventsSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageEvent
     * Run the database seeds.
     * php artisan db:seed --class=PageEventsSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        //\App\Models\PageEvent::truncate();
        //$items = $this->getData();
        //foreach ($items as $pageId => $item) {
        //    foreach ($item as $i => $fields) {
        //        $fields['sort'] = $i * 10 + 10;
        //        $fields['page_id'] = $pageId;
        //        $fields['active'] = true;
        //        $fields['title'] = $fields['title'] ?? 'Матч ' . $i + 1;
        //        PageEvent::create($fields);
        //    }
        //}
    }

    public function getData(): array
    {
        $stub = [
            [
                'team1' => 'Ростов',
                'team2' => 'Крылья Советов',
                'date_start' => now(),
            ],
        ];
        //@formatter:off
        return [
            0 => [],
            1 => [],
            2 => $stub,
            3 => $stub,
            4 => $stub,
            5 => $stub,
            6 => $stub,
            7 => $stub,
            8 => $stub,
            9 => $stub,
            10 => $stub,
        ];
    }
}


























