<?php

namespace Database\Seeders;

use App\Models\FilePath;
use App\Models\PageInfo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageInfosSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see PageInfo
     * Run the database seeds.
     * php artisan db:seed --class=PageInfosSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        \App\Models\PageInfo::truncate();
        $items = $this->getData();
        foreach ($items as $pageId => $item) {
            foreach ($item as $i => $fields) {
                $fields['sort'] = $i * 10 + 10;
                $fields['page_id'] = $pageId;
                $fields['active'] = true;
                $fields['title'] = $fields['title'] ?? 'Блок ' . $i + 1;
                $fields['type'] = $fields['type'] ?? (isset($fields['html']) ? 'html' : null);
                if (isset($fields['img'])) {
                    $fp = FilePath::create(['path' => $fields['img']]);
                    $fields['img_id'] = $fp->id;
                }
                unset($fields['img']);
                PageInfo::create($fields);
            }
            unset($key);
        }
    }

    public function getData(): array
    {
        //@formatter:off
        return [
            0 => [],
            1 => [],
            // КХЛ
            2 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>FONBET является официальным партнером Континентальной хоккейной лиги (КХЛ) с&nbsp;2021&nbsp;года, поддерживая как регулярный чемпионат, так и&nbsp;важные мероприятия, такие как Матч звезд КХЛ. Это партнёрство включает многолетний контракт, предполагающий финансовую поддержку и&nbsp;продвижение хоккея. В&nbsp;рамках сотрудничества FONBET активно развивает цифровые и&nbsp;рекламные инициативы, включая активации с&nbsp;болельщиками и&nbsp;акционные программы на&nbsp;матчах.</p>
<p>В&nbsp;2021 году FONBET стал титульным партнером Матча звезд КХЛ. Это сотрудничество повысило популярность события и&nbsp;привлекло к&nbsp;нему дополнительное внимание через трансляции и&nbsp;социальные сети.</p>',
                ],
                [
                    'html' => '<div class="tourney__col tourney__col--two-image">
<div class="tourney__image">
  <div class="img"><img src="/assets/images/article-1/tourney/1.jpg" alt=""></div><a class="button button button--white" href="#" target="_blank"><span class="button__text">Fonbet overtime</span></a>
</div>
<div class="tourney__image">
  <div class="img"><img src="/assets/images/article-1/tourney/2.jpg" alt=""></div><a class="button button button--white" href="#" target="_blank"><span class="button__text">Хокквартс</span></a>
</div>
</div>',
                ],
                [
                    'text' => '<p>КХЛ&nbsp;&mdash; одна из&nbsp;крупнейших хоккейных лиг мира, основанная в&nbsp;2008&nbsp;году, в&nbsp;которой участвуют клубы из&nbsp;России и&nbsp;других стран Европы и&nbsp;Азии. В&nbsp;сезоне 2023/24 в&nbsp;турнире</p>
<p>В&nbsp;рамках сотрудничества с&nbsp;КХЛ, FONBET активно поддерживает Матч звезд КХЛ, который является важным событием для продвижения хоккея. Спонсорство FONBET</p>
<p>В&nbsp;сезоне 2022/23&nbsp;на матчах КХЛ было зарегистрировано более 2&nbsp;миллионов зрителей, и&nbsp;благодаря партнёрству с&nbsp;FONBET ожидается дальнейший рост этой</p>
',
                ],
                [
                    'html' => '<div class="tourney__col tourney__col--two-text">
  <div class="tourney__blocks">
    <div class="tourney-info"><span>24&nbsp;клуба</span><span>в&nbsp;сезоне 2023/24</span></div>
    <div class="tourney-info"><span>24&nbsp;клуба</span><span>в&nbsp;сезоне 2023/24</span></div>
  </div>
  <div class="tourney__item">
    <div class="tourney__text">
      <p>В&nbsp;рамках соглашения FONBET с&nbsp;КХЛ используются новейшие технологии для анализа данных и&nbsp;улучшения взаимодействия с&nbsp;болельщиками через мобильные приложения</p>
    </div>
  </div>
</div>',
                ],
            ],
            // Кубок России по футболу
            3 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>В&nbsp;2022 году FONBET заключил соглашение с&nbsp;Российским футбольным союзом (РФС) и&nbsp;стал титульным спонсором Кубка России по&nbsp;футболу. Соглашение многолетнее и&nbsp;рассчитано на&nbsp;долгосрочное развитие турнира.</p>
<p>Это спонсорство позволило турниру официально называться FONBET Кубок России. Также оно включило в&nbsp;себя повышение призового фонда турнира, хотя точные суммы призового фонда после сотрудничества не&nbsp;разглашаются.</p>
<p>FONBET инвестировал значительные средства в&nbsp;популяризацию турнира, развитие новых форматов и&nbsp;продвижение матчей среди широкой аудитории,</p>
',
                ],
                [
                    'title' => 'Фото',
                    'type' => 'img',
                    'img' => '/assets/images/article-2/tourney/1.jpg',
                ],
                [
                    'html' => '<div class="tourney__col tourney__col--two-text">
  <div class="tourney__item">
    <div class="tourney__text">
      <p>Фонд призовых на&nbsp;FONBET Кубке России увеличен после сотрудничества с&nbsp;FONBET, что&nbsp;стимулирует участие клубов всех дивизионов, начиная от&nbsp;РПЛ</p>
      <p>Уникальный формат соревнований был введён в&nbsp;2022&nbsp;году, который включает не&nbsp;только традиционную систему на&nbsp;выбывание, но&nbsp;и&nbsp;групповые этапы, что увеличило</p>
    </div>
  </div>
  <div class="tourney__blocks">
    <div class="tourney-info"><span>1,513 млрд&nbsp;₽</span><span>призовой фонд</span></div>
    <div class="tourney-info for-devices"><span>1&nbsp;136&nbsp;724</span><span>зрителя за&nbsp;сезон</span></div>
    <div class="tourney-info"><span>300,35 млн&nbsp;₽</span><span>призовые за&nbsp;победу в&nbsp;сезоне 22/23</span></div>
    <div class="tourney-info"><span>272,05 млн&nbsp;₽</span><span>призовые за&nbsp;победу в&nbsp;сезоне 23/24</span></div>
  </div>
</div>',
                ],
                [
                    'html' => '<div class="tourney__col tourney__col--two-text">
              <div class="tourney__item">
                <div class="tourney__text">
                  <p>В&nbsp;Кубке России ежегодно участвуют около 100 команд различных команд, от&nbsp;клубов Российской Премьер-лиги до&nbsp;команд второго и&nbsp;третьего дивизионов, а&nbsp;также медийные</p>
                  <p>В&nbsp;2023&nbsp;году, в&nbsp;рамках медийного продвижения турнира, количество зрителей матчей Кубка России выросло благодаря новому формату</p>
                </div>
              </div>
              <div class="tourney__blocks">
                <div class="tourney-info"><span>107</span><span>команд</span></div>
                <div class="tourney-info"><span>164</span><span>матча</span></div>
                <div class="tourney-info for-desktop"><span>1&nbsp;136&nbsp;724</span><span>зрителя за&nbsp;сезон</span></div>
              </div>
            </div>',
                ],
            ],
            // Мужская Суперлига по баскетболу
            4 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>В&nbsp;2023 году FONBET стал официальным спонсором Суперлиги (мужской и&nbsp;женской) по&nbsp;баскетболу, что оказало значительное влияние на&nbsp;развитие этих турниров. С&nbsp;2023 года компания активно поддерживает соревнования, которые стали частью её&nbsp;стратегии по&nbsp;продвижению спорта на&nbsp;всех уровнях&nbsp;— от&nbsp;топовых лиг до&nbsp;второй по&nbsp;значимости в&nbsp;России Суперлиги.</p>
<p>Соглашение с&nbsp;FONBET включает поддержку как мужской, так и&nbsp;женской Суперлиги, что способствует популяризации баскетбола и&nbsp;расширению аудитории этих турниров.</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-3/tourney/1.jpg',
                    'type' => 'img',
                ],
                [
                    'type' => '2text',
                    'text' => '<p>Мужская Суперлига по&nbsp;баскетболу&nbsp;— второй по&nbsp;значимости чемпионат России, в&nbsp;котором участвуют около 16&nbsp;команд. Она существует с&nbsp;1992&nbsp;года, и&nbsp;за&nbsp;последние годы, благодаря поддержке FONBET, стала более</p>',
                    'html' => '<p>Женская Суперлига также активно развивается с&nbsp;момента заключения партнёрства с&nbsp;FONBET. В&nbsp;сезоне 2022/23&nbsp;в турнире приняли участие 12&nbsp;женских команд, что&nbsp;отражает рост интереса к&nbsp;женскому</p>',
                ],
                [
                    'html' => '<div class="tourney__col tourney__col--two-text">
  <div class="tourney__blocks">
    <div class="tourney-info for-desktop"><span>1992</span><span>Год основания</span></div>
    <div class="tourney-info"><span>16</span><span>Год основания</span></div>
    <div class="tourney-info"><span>12</span><span>Год основания</span></div>
  </div>
  <div class="tourney__item">
    <div class="tourney__text">
      <p>Важным аспектом сотрудничества FONBET является поддержка не&nbsp;только элитных команд, но&nbsp;и&nbsp;региональных клубов, что&nbsp;способствует развитию</p>
    </div>
  </div>
</div>',
                ],
            ],
            // Мужская Высшая лига по баскетболу
            5 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>С&nbsp;2023 года FONBET стал титульным спонсором мужской и&nbsp;женской Высшей лигипо баскетболу, что значительно усилило развитие турниров. Высшая лига&nbsp;— это третий по&nbsp;значимости дивизион в&nbsp;российском баскетболе, где выступают перспективные молодые игроки и&nbsp;команды, которые стремятся выйти на&nbsp;уровень Суперлиги. Партнёрство с&nbsp;FONBET направлено на&nbsp;укрепление инфраструктуры, улучшение условий для участников и&nbsp;продвижение турниров</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-4/tourney/1.jpg',
                    'type' => 'img',
                ],
                [
                    'title' => '2 текста',
                    'type' => '2text',
                    'text' => '<p>Мужская Высшая лига по&nbsp;баскетболу&nbsp;— третий по&nbsp;значимости дивизион российского баскетбола, состоящий из&nbsp;20&nbsp;команд, которые борются за&nbsp;продвижение в&nbsp;Суперлигу. Лига была создана в&nbsp;1992&nbsp;году, и&nbsp;с&nbsp;момента подписания контракта с&nbsp;FONBET получила значительную поддержку</p>',
                    'html' => '<p>Женская Высшая лига включает 14&nbsp;команд, и&nbsp;её&nbsp;популярность растёт благодаря поддержке FONBET, который активно развивает трансляции матчей и&nbsp;увеличивает финансирование лиги.</p>',
                ],
                [
                    'title' => 'HTML',
                    'type' => 'html',
                    'html' => '<div class="tourney__col tourney__col--two-text">
  <div class="tourney__blocks">
    <div class="tourney-info for-desktop"><span>1992</span><span>Год основания</span></div>
    <div class="tourney-info"><span>20</span><span>Мужских команд</span></div>
    <div class="tourney-info"><span>14</span><span>Женских команд</span></div>
  </div><a class="tourney__banner" href="#" target="_blank">
    <div class="tourney__banner-text">Безусловный фрибет за&nbsp;регистрацию</div>
    <div class="tourney__banner-price"><span>до</span><span>15000₽</span></div>
  </a>
</div>',
                ],
            ],
            // Женская Премьер-лига по баскетболу
            6 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>С&nbsp;2021 года FONBET является титульным спонсором Женской Премьер-лиги по&nbsp;баскетболу (ЖБЛ), что привело к&nbsp;официальному названию турнира&nbsp;— FONBET Женская Премьер-лига по&nbsp;баскетболу. Это партнерство направлено на&nbsp;поддержку женского баскетбола в&nbsp;России, включая развитие инфраструктуры, улучшение условий для команд, а&nbsp;также повышение медийной популярности турнира.</p>
<p>Соглашение предусматривает многолетнюю поддержку турнира с&nbsp;целью расширения его аудитории и&nbsp;повышения качества соревнований.</p>
<p>FONBET поддерживает не&nbsp;только элитный уровень женского баскетбола,</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-5/tourney/1.jpg',
                    'type' => 'img',
                ],
                [
                    'title' => '2 текста',
                    'type' => '2text',
                    'text' => '<p>Женская Премьер-лига по&nbsp;баскетболу&nbsp;— главный чемпионат России по&nbsp;женскому баскетболу, в&nbsp;котором участвуют лучшие клубы страны. Турнир существует с&nbsp;1992&nbsp;года, а&nbsp;с&nbsp;приходом FONBET стал более масштабным и&nbsp;медийно привлекательным.</p>
<p>В&nbsp;сезоне 2022/23&nbsp;в турнире принимали участие 12&nbsp;команд,</p>',
                    'html' => '<p>В&nbsp;рамках спонсорства FONBET было значительно увеличено внимание к&nbsp;цифровым трансляциям матчей, что&nbsp;позволило привлечь больше болельщиков. FONBET активно использует социальные сети и&nbsp;цифровые платформы для продвижения турнира, что привело к&nbsp;росту аудитории на&nbsp;30%</p>',
                ],
                [
                    'title' => 'Баннер и текст',
                    'type' => 'html',
                    'html' => '<div class="tourney__col tourney__col--two-text"><a class="tourney__banner" href="#" target="_blank">
    <div class="tourney__banner-text">Безусловный фрибет за&nbsp;регистрацию</div>
    <div class="tourney__banner-price"><span>до</span><span>15000₽</span></div>
  </a>
  <div class="tourney__item">
    <div class="tourney__text">
      <p>FONBET также инициировал интеграцию с&nbsp;другими спортивными соревнованиями, такими как Кубок России по&nbsp;баскетболу, что повысило популярность женского баскетбола на&nbsp;региональном уровне.</p>
    </div>
  </div>
</div>',
                ],
            ],
            7 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>FONBET Кубок Дзюдо России по&nbsp;дзюдо&nbsp;— это собирательное название для серии региональных и&nbsp;федеральных турниров, проходящих по&nbsp;всей территории страны в&nbsp;течение сезона. Соревнования проходят в&nbsp;мужском и&nbsp;женском разрядах, интерeсным направлением является командный чемпионат по&nbsp;дзюдо. Также есть серия международных турниров RJT, куда активно приезжают спортсмены из&nbsp;дружественных стран, например, прошлый турнир принял&nbsp;ХХ спортсменов</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-6/tourney/1.png',
                    'type' => 'img',
                ],
            ],
            // кубок дружбы
            8 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>FONBET Кубок Дружбы по&nbsp;баскетболу 2024 года прошел в&nbsp;Перми с&nbsp;21&nbsp;по&nbsp;25&nbsp;августа. В&nbsp;турнире приняли участие национальные мужские сборные России, Венесуэлы и&nbsp;Колумбии, а&nbsp;также местная команда «Парма», представляющая Единую лигу ВТБ.</p>
<p>Турнир прошел в&nbsp;Перми на&nbsp;УДС «Молот», сыграли мужские и&nbsp;женские сборные.</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-7/tourney/1.png',
                    'type' => 'img',
                ],
            ],
            // кубок дружбы
            9 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>FONBET Кубок России по&nbsp;спортивной гимнастике, прошедший в&nbsp;Новосибирске с&nbsp;31&nbsp;июля по&nbsp;4&nbsp;августа 2024&nbsp;года, объединил лучших гимнастов страны, превратив турнир в&nbsp;настоящий праздник для поклонников этого зрелищного вида спорта. Для&nbsp;спортсменов&nbsp;— это важнейшие национальные соревнования, которые проходят в&nbsp;середине гимнастического сезона. Ведущие спортсмены, включая олимпийских чемпионов и&nbsp;триумфаторов игр БРИКС, продемонстрировали высочайший уровень мастерства и&nbsp;грации, подтверждая статус турнира как одного из&nbsp;главных событий в&nbsp;мире спортивной гимнастики.</p>
<p>В&nbsp;рамках мероприятия FONBET выделил двух MVP, наградив их&nbsp;призовым фондом в&nbsp;рамках 100&nbsp;000 рублей на&nbsp;каждого&nbsp;— Станислава Исаева из&nbsp;Казани и&nbsp;Ксению Зеляева из&nbsp;Малаховки.</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-8/tourney/1.png',
                    'type' => 'img',
                ],
            ],
            10 => [
                [
                    'title' => 'О турнире',
                    'text' => '<p>FONBET Кубок России по&nbsp;компьютерному спорту 2024 состоял из&nbsp;соревнований по&nbsp;трём популярным дисциплинам: Counter-Strike 2, Dota&nbsp;2, и&nbsp;файтинг Tekken&nbsp;8. Призовой фонд турнира составил 2,5 млн рублей.</p>
<p>Финал прошел на&nbsp;VK&nbsp;Play Арене в&nbsp;Москве, где участвовали лучшие команды и&nbsp;игроки со&nbsp;всей страны. Среди победителей:</p>
<p>Counter-Strike 2: HOTU x&nbsp;ELGA (Республика Саха)</p>',
                ],
                [
                    'title' => 'Фото',
                    'img' => '/assets/images/article-9/tourney/1.png',
                    'type' => 'img',
                ],
            ],
        ];
    }
}


























