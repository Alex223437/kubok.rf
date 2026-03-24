<?php

namespace Database\Seeders;

use App\Models\FilePath;
use App\Models\PageMoment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageMomentsSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageMoment
     * Run the database seeds.
     * php artisan db:seed --class=PageMomentsSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        //\App\Models\PageMoment::truncate();
        //$items = $this->getData();
        //foreach ($items as $pageId => $item) {
        //    foreach ($item as $i => $fields) {
        //        $fields['sort'] = $i * 10 + 10;
        //        $fields['page_id'] = $pageId;
        //        $fields['active'] = true;
        //        $fields['title'] = $fields['title'] ?? 'Фото ' . $i + 1;
        //        if (isset($fields['img'])) {
        //            $fp = FilePath::create(['path' => $fields['img']]);
        //            $fields['img_id'] = $fp->id;
        //        }
        //        unset($fields['img']);
        //        PageMoment::create($fields);
        //    }
        //}
    }

    public function getData(): array
    {
        $stub = [
            [
                'title' => 'Видео',
                'html' => '<iframe width="720" height="405" src="https://rutube.ru/play/embed/53b38a3836bfdcc38801e8bd9ee7ad06"
frameBorder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>',
            ],
            ['img' => '/assets/images/article-1/moments/slider/1.png'],
            ['img' => '/assets/images/article-1/moments/slider/1.png'],
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


























