<?php

namespace Database\Seeders;

use App\Models\FilePath;
use App\Models\PageCharity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageCharitySeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageCharity
     * Run the database seeds.
     * php artisan db:seed --class=PageCharitySeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        //\App\Models\PageCharity::truncate();
        //$items = $this->getData();
        //foreach ($items as $pageId => $item) {
        //    foreach ($item as $i => $fields) {
        //        $fields['sort'] = $i * 10 + 10;
        //        $fields['page_id'] = $pageId;
        //        $fields['active'] = true;
        //        $fields['title'] = $fields['title'] ?? 'Блок ' . $i + 1;
        //        if (isset($fields['img'])) {
        //            $fp = FilePath::create(['path' => $fields['img']]);
        //            $fields['img_id'] = $fp->id;
        //        }
        //        unset($fields['img']);
        //        PageCharity::create($fields);
        //    }
        //    unset($key);
        //}
    }

    public function getData(): array
    {
        //@formatter:off
        return [
            0 => [],
            1 => [],
            2 => [
                [
                    'title' => 'ВСЕРОССИЙСКИЙ ФЕСТИВАЛЬ АДАПТИВНОГО ХОККЕЯ',
                    'text' => 'ДоброFON совместно с&nbsp;ФАХ провёл уже VI&nbsp;Всероссийский Фестиваль адаптивного хоккея, количество команд в&nbsp;котором за&nbsp;6&nbsp;лет выросло',
                    'img' => '/assets/images/article-1/charity/1.png',
                ],
                [
                    'title' => 'ДОБРОФУРА',
                    'text' => 'Совместный проект ДоброFON и&nbsp;FONBET КХЛ, в&nbsp;рамках которого два брендированных грузовика отправились навстречу друг другу с&nbsp;Запада и&nbsp;Востока России. Они останавливались во&nbsp;всех городах, где проводились хоккейные матчи, и&nbsp;организовывали различные',
                    'img' => '/assets/images/article-1/charity/2.png',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
                [
                    'title' => 'СОТРУДНИЧЕСТВО С&nbsp;«ЛИЗААЛЕРТ»',
                    'text' => 'Совместный проект РФС, ДоброFON и&nbsp;ЛизаАлерт, который направлен на&nbsp;информационную поддержку поиска пропавших людей.',
                    'img' => '/assets/images/article-1/charity/3.png',
                ],
            ],
            3 => [
                [
                    'title' => 'Акция «футбол помогает»',
                    'text' => 'Благотворительная акция проводится на&nbsp;матчах FONBET Кубка России по&nbsp;футболу, количество зрителей на&nbsp;которых умножается на&nbsp;определенное число и&nbsp;получившаяся сумма жертвуется в&nbsp;различные фонды.',
                    'img' => '/assets/images/article-2/charity/1.jpg',
                ],
                [
                    'title' => 'Акция «важен каждый»',
                    'text' => 'В&nbsp;феврале и&nbsp;марте 2024 года на&nbsp;четырех четвертьфинальных матчах FONBET Кубка России была организована акция в&nbsp;поддержку проекта «Футбол помогает».',
                    'img' => '/assets/images/article-2/charity/1.jpg',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
                [
                    'title' => 'СОТРУДНИЧЕСТВО С&nbsp;«ЛИЗААЛЕРТ»',
                    'text' => 'Совместный проект РФС, ДоброFON и&nbsp;ЛизаАлерт, который направлен на&nbsp;информационную поддержку поиска пропавших людей.',
                    'img' => '/assets/images/article-1/charity/3.png',
                ],
            ],
            4 => [
                [
                    'title' => 'Акция «футбол помогает»',
                    'text' => 'Благотворительная акция проводится на&nbsp;матчах FONBET Кубка России по&nbsp;футболу, количество зрителей на&nbsp;которых умножается на&nbsp;определенное число и&nbsp;получившаяся сумма жертвуется в&nbsp;различные фонды.',
                    'img' => '/assets/images/article-3/charity/1.jpg',
                ],
                [
                    'title' => 'Акция «важен каждый»',
                    'text' => 'В&nbsp;феврале и&nbsp;марте 2024 года на&nbsp;четырех четвертьфинальных матчах FONBET Кубка России была организована акция в&nbsp;поддержку проекта «Футбол помогает».',
                    'img' => '/assets/images/article-3/charity/1.jpg',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
                [
                    'title' => 'СОТРУДНИЧЕСТВО С&nbsp;«ЛИЗААЛЕРТ»',
                    'text' => 'Совместный проект РФС, ДоброFON и&nbsp;ЛизаАлерт, который направлен на&nbsp;информационную поддержку поиска пропавших людей.',
                    'img' => '/assets/images/article-1/charity/3.png',
                ],
            ],
            5 => [
                [
                    'title' => '«тихий баскетбол»',
                    'text' => 'Доброфон поддерживает программу Рфб «тихий баскетбол».',
                    'img' => '/assets/images/article-4/charity/1.jpg',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
            6 => [
                [
                    'title' => '«тихий баскетбол»',
                    'text' => 'Доброфон поддерживает программу Рфб «тихий баскетбол».',
                    'img' => '/assets/images/article-4/charity/1.jpg',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
            7 => [
                [
                    'title' => 'ДОБРОМЕРЧ',
                    'text' => 'Это благотворительный проект FONBET и&nbsp;Федерации Дзюдо России, в&nbsp;котором вы&nbsp;получаете стильный мерч и&nbsp;плюс в&nbsp;карму, а&nbsp;благотворительный фонд&nbsp;— ваш донат на&nbsp;добрые дела.',
                    'img' => '/assets/images/article-6/charity/1.png',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
            8 => [
                [
                    'title' => 'ДОБРОМЕРЧ',
                    'text' => 'Это благотворительный проект FONBET и&nbsp;Федерации Дзюдо России, в&nbsp;котором вы&nbsp;получаете стильный мерч и&nbsp;плюс в&nbsp;карму, а&nbsp;благотворительный фонд&nbsp;— ваш донат на&nbsp;добрые дела.',
                    'img' => '/assets/images/article-6/charity/1.png',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
            9 => [
                [
                    'title' => 'ДОБРОМЕРЧ',
                    'text' => 'Это благотворительный проект FONBET и&nbsp;Федерации Дзюдо России, в&nbsp;котором вы&nbsp;получаете стильный мерч и&nbsp;плюс в&nbsp;карму, а&nbsp;благотворительный фонд&nbsp;— ваш донат на&nbsp;добрые дела.',
                    'img' => '/assets/images/article-6/charity/1.png',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
            10 => [

                [
                    'title' => 'ДОБРОМЕРЧ',
                    'text' => 'Это благотворительный проект FONBET и&nbsp;Федерации Дзюдо России, в&nbsp;котором вы&nbsp;получаете стильный мерч и&nbsp;плюс в&nbsp;карму, а&nbsp;благотворительный фонд&nbsp;— ваш донат на&nbsp;добрые дела.',
                    'img' => '/assets/images/article-9/charity/1.png',
                ],
                [
                    'type' => 'img',
                    'title' => 'ДоброFON',
                    'img' => '/assets/images/article-1/charity/banner.svg',
                ],
            ],
        ];
    }
}


























