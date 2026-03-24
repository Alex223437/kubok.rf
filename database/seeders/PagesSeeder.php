<?php

namespace Database\Seeders;

use App\Models\FilePath;
use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PagesSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see Page
     * Run the database seeds.
     * php artisan db:seed --class=PagesSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        /*
        \App\Models\Page::truncate();
        $items = $this->getData();
        foreach ($items as $key => $item) {
            $item['sort'] = $key * 10 + 10;
            $item['code'] = Str::slug(str_replace('&nbsp;', '-', strip_tags($item['title'])));
            $item['meta_title'] = strip_tags($item['title']);
            $item['meta_description'] = strip_tags($item['description']);
            $item['meta_keywords'] = strip_tags($item['title']);
            $item['active'] = true;
            $item['banner_id'] = 1;
            $item['payload'] = ['css_code' => $key + 1];

            if ($item['img']) {
                $fp = FilePath::create(['path' => $item['img']]);
                $item['img_id'] = $fp->id;
            }
            unset($item['img']);

            if ($item['picture']) {
                $fp = FilePath::create(['path' => $item['picture']]);
                $item['picture_id'] = $fp->id;
            }
            unset($item['picture']);

            Page::create($item);
        }
        */
    }

    public function getData(): array
    {
        //Array.from(document.getElementsByClassName('home__item-name')).forEach((i) => {console.log(i.innerHTML)})
        // document.querySelectorAll('.home__item-text').forEach((n) => console.log(n.innerHTML))
        // [...document.querySelectorAll('.home__item')].map(n => n.classList[1])
        //@formatter:off
        return [
            [
                'type' => 'promo',
                'title' => 'Профессиональный <span>спорт</span>',
                'description' => <<<HTML
<p>Информация о&nbsp;крупнейших спортивных турнирах&nbsp;— здесь.</p>
<p>Более 7&nbsp;видов спорта и&nbsp;10&nbsp;ключевых Кубков и&nbsp;Чемпионатов страны.<br class="for-desktop"> Среди них&nbsp;— FONBET Кубок России по&nbsp;футболу, FONBET Чемпионат КХЛ, FONBET Высшая Лига по&nbsp;баскетболу, FONBET Кубок России по&nbsp;компьютерному спорту, FONBET Кубок России по&nbsp;спортивной гимнастике и&nbsp;FONBET Кубок России по&nbsp;дзюдо.</p>
<p>Следи за&nbsp;спортивной статистикой и&nbsp;турнирными сетками Чемпионатов.</p>
HTML,
                'html' => '',
                'img' => null,
                'picture' => null,
            ],
            [
                'title' => 'Fonbet чемпионат кхл',
                'description' => <<<HTML
<p>FONBET КХЛ&nbsp;— международная хоккейная лига, образованная в&nbsp;феврале 2008 года для клубов из&nbsp;России и&nbsp;других стран Европы и&nbsp;Азии. Организатор регулярного чемпионата КХЛ, в&nbsp;котором разыгрывается Кубок Континента, и&nbsp;плей-офф, в&nbsp;котором 16&nbsp;команд соревнуются за&nbsp;Кубок Гагарина. По&nbsp;итогам плей-офф определяется победитель, который получает титул чемпиона КХЛ и&nbsp;становится обладателем главного трофея лиги&nbsp;— Кубка Гагарина.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/1.png"><img src="/assets/images/home/1.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/1.svg',
                'picture' => '/assets/images/article-1/head/head.png',
            ],
            [
                'type' => 'banner',
                'title' => 'Fonbet кубок россии по&nbsp;футболу',
                'description' => <<<HTML
<p>FONBET Кубок России по&nbsp;футболу 2023/2024&nbsp;— соревнование для российских футбольных клубов, проводимое Российским футбольным союзом. Футбольный турнир проводится по&nbsp;системе с&nbsp;выбыванием, начиная с&nbsp;1/256 финала (Путь Регионов). Победитель получит право сыграть с&nbsp;чемпионом России 2023/24&nbsp;в матче за&nbsp;Суперкубок России, а&nbsp;также в&nbsp;случае допуска российских команд в&nbsp;еврокубки получит право сыграть в&nbsp;Лиге Европы 2024/25.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/2-mobile.png" type="image/png" media="(max-width: 1024px)"><source srcset="/assets/images/home/2.png" type="image/png" media="(min-width: 1025px)"><img src="/assets/images/home/2.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/2.svg',
                'picture' => '/assets/images/article-2/head/head.png',
            ],
            [
                'title' => 'Fonbet супер лига',
                'description' => <<<HTML
<p>Мужская Суперлига по&nbsp;баскетболу&nbsp;— второй по&nbsp;значимости чемпионат России, в&nbsp;котором участвуют около 16&nbsp;команд. Она существует с&nbsp;1992&nbsp;года, и&nbsp;за&nbsp;последние годы, благодаря поддержке FONBET, стала более медийной и&nbsp;привлекательной для зрителей.</p>
<p>Женская Суперлига также активно развивается с&nbsp;момента заключения партнёрства с&nbsp;FONBET. В&nbsp;сезоне 2022/23&nbsp;в турнире приняли участие 12&nbsp;женских команд, что отражает рост интереса к&nbsp;женскому баскетболу в&nbsp;России.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/3.png"><img src="/assets/images/home/3.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/3.svg',
                'picture' => '/assets/images/article-3/head/head.png',
            ],
            [
                'title' => 'Fonbet высшая лига',
                'description' => <<<HTML
<p>Мужская Высшая лига по&nbsp;баскетболу&nbsp;— третий по&nbsp;значимости дивизион российского баскетбола, состоящий из&nbsp;20&nbsp;команд, которые борются за&nbsp;продвижение в&nbsp;Суперлигу. Лига была создана в&nbsp;1992&nbsp;году, и&nbsp;с&nbsp;момента подписания контракта с&nbsp;FONBET получила значительную поддержку как в&nbsp;медийном, так и&nbsp;в&nbsp;финансовом плане.</p>
<p>Женская Высшая лига включает 14&nbsp;команд, и&nbsp;её&nbsp;популярность растёт благодаря поддержке FONBET, который активно развивает трансляции матчей и&nbsp;увеличивает финансирование лиги.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/4.png"><img src="/assets/images/home/4.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/4.svg',
                'picture' => '/assets/images/article-4/head/head.png',
            ],
            [
                'title' => 'Fonbet премьер лига',
                'description' => <<<HTML
<p>Женская Премьер-лига по&nbsp;баскетболу&nbsp;— главный чемпионат России по&nbsp;женскому баскетболу, в&nbsp;котором участвуют лучшие клубы страны. Турнир существует с&nbsp;1992&nbsp;года, а&nbsp;с&nbsp;приходом FONBET стал более масштабным и&nbsp;медийно привлекательным.</p>
<p>В&nbsp;сезоне 2022/23&nbsp;в турнире принимали участие 12&nbsp;команд, включая ведущие клубы, такие как УГМК, Динамо, и&nbsp;Ника.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/5.png"><img src="/assets/images/home/5.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/5.svg',
                'picture' => '/assets/images/article-5/head/head.png',
            ],
            [
                'title' => 'Fonbet кубок россии по&nbsp;дзюдо',
                'description' => <<<HTML
<p>FONBET Кубок России по&nbsp;дзюдо&nbsp;— это собирательное название для серии региональных и&nbsp;федеральных труниров, проходящих по&nbsp;всей территории страны в&nbsp;течение сезона. Соревнования проходят в&nbsp;мужском и&nbsp;женском разрядах, инетерсным направлением является командный чемпионат по&nbsp;дзюдо. Также есть серия международных турниров RJT, куда активно приезжают спортсмены из&nbsp;дружественных стран, например, прошлый турнир принял&nbsp;ХХ спортсменов из&nbsp;ХХ стран.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/6.png"><img src="/assets/images/home/6.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/6.svg',
                'picture' => '/assets/images/article-6/head/head.png',
            ],
            [
                'title' => 'Fonbet кубок дружбы',
                'description' => <<<HTML
<p>FONBET Кубок Дружбы по&nbsp;баскетболу 2024 года прошел в&nbsp;Перми с&nbsp;21&nbsp;по&nbsp;25&nbsp;августа в&nbsp;УДС «Молот». В&nbsp;турнире приняли участие национальные мужские сборные России, Венесуэлы и&nbsp;Колумбии,а также местная команда «Парма», представляющая Единую лигу ВТБ.</p>
<p>Турнир прошел в&nbsp;Перми на&nbsp;УДС «Молот», сыграли мужские и&nbsp;женские сборные. В&nbsp;финал турнира вышли Сборная России и&nbsp;БК&nbsp;Парма. Победитель&nbsp;— Сборная России.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/7.png"><img src="/assets/images/home/7.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/7.svg',
                'picture' => '/assets/images/article-7/head/head.png',
            ],
            [
                'title' => 'Fonbet кубок россии по&nbsp;спортивной гимнастике',
                'description' => <<<HTML
<p>FONBET Кубок России по&nbsp;спортивной гимнастике, прошедший в&nbsp;Новосибирске с&nbsp;31&nbsp;июля по&nbsp;4&nbsp;августа 2024&nbsp;года, объединил лучших гимнастов страны, превратив турнир в&nbsp;настоящий праздник для поклонников этого зрелищного вида спорта. Для спортсменов —это важнейшие национальные соревнования, которые проходят в&nbsp;середине гимнастического сезона. Ведущие спортсмены, включая олимпийских чемпионов и&nbsp;триумфаторов игр БРИКС, продемонстрировали высочайший уровень мастерства и&nbsp;грации, подтверждая статус турнира как одного из&nbsp;главных событий в&nbsp;мире</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/8.png"><img src="/assets/images/home/8.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/8.svg',
                'picture' => '/assets/images/article-8/head/head.png',
                'facts' => '<div class="tourney-info"><span>53</span><span>УЧАСТНИКА</span></div>
<div class="tourney-info"><span>22</span><span>ГОРОДА РОССИИ И&nbsp;БЕЛАРУСИ</span></div>
<div class="tourney-info"><span>5</span><span>ДНЕЙ СОРЕВНОВАНИЙ</span></div>
<div class="tourney-info"><span>12</span><span>КОМПЛЕКТОВ МЕДАЛЕЙ</span></div>'
            ],
            [
                'title' => 'Fonbet кубок россии по&nbsp;компьютерному спорту',
                'description' => <<<HTML
<p>Fonbet Кубок России по&nbsp;компьютерному спорту 2024 состоял из&nbsp;соревнований по&nbsp;трём популярным дисциплинам: Counter-Strike 2, Dota&nbsp;2, и&nbsp;файтинг Tekken&nbsp;8. Призовой фонд турнира составил 2,5 млн рублей.</p>
HTML,
                'html' => <<<HTML
<picture>
<source srcset="/assets/images/home/9.png"><img src="/assets/images/home/9.png" alt="" decoding="async">
</picture>
HTML,
                'img' => '/assets/images/home/9.svg',
                'picture' => '/assets/images/article-9/head/head.png',
                'facts' => '<div class="tourney-info tourney-info--title"><span>ФАКТЫ</span></div>
<div class="tourney-info"><span>2,5&nbsp;МЛН&nbsp;₽</span><span>ПРИЗОВОЙ ФОНД</span></div>
<div class="tourney-info"><span>286 ТЫС</span><span>ЗРИТЕЛЕЙ ОНЛАЙН-ТРАНСЛЯЦИИ</span></div>
<div class="tourney-info"><span>150</span><span>КОМАНД И СПОРТСМЕНОВ</span></div>'
            ],
        ];
    }
}


























