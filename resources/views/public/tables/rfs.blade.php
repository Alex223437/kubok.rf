@push('styles')
<link href="/assets/rfs.css" rel="stylesheet">
@endpush

@php
    $allMatches = \App\Models\RfsMatch::all();

    // Карта логотипов по названию команды (для плей-офф, где логотипы не парсятся)
    $teamLogos = [];
    foreach ($allMatches as $_m) {
        if ($_m->team1_logo && !isset($teamLogos[$_m->team1])) $teamLogos[$_m->team1] = $_m->team1_logo;
        if ($_m->team2_logo && !isset($teamLogos[$_m->team2])) $teamLogos[$_m->team2] = $_m->team2_logo;
    }

    // Все предстоящие матчи (группы + плей-офф) — только те, у которых дата в score_or_date
    $upcomingMatches = $allMatches->filter(function ($m) {
        return !$m->is_played || preg_match('/^\d{2}\.\d{2}\./', $m->score_or_date);
    })->sortBy(function ($m) {
        // Конвертируем DD.MM.YYYY HH:MM → YYYYMMDD HH:MM для корректной сортировки
        if (preg_match('/^(\d{2})\.(\d{2})\.(\d{4})(?:\s+(\d{2}:\d{2}))?/', $m->score_or_date, $r)) {
            return $r[3] . $r[2] . $r[1] . ($r[4] ?? '00:00');
        }
        return $m->score_or_date;
    });

    // Плей-офф
    $playoffMatches = $allMatches->where('group_name', 'Путь РПЛ. Плей-офф');

    // Считаем турнирные таблицы из результатов групповых матчей
    $groupStandings = [];
    foreach ($allMatches->where('is_played', true)->where('group_name', '!=', 'Путь РПЛ. Плей-офф')->groupBy('group_name') as $groupName => $matches) {
        $teams = [];

        foreach ($matches as $match) {
            if (!preg_match('/^(\d+):(\d+)$/', $match->score_or_date, $sc)) continue;
            $g1 = (int)$sc[1];
            $g2 = (int)$sc[2];

            foreach ([[$match->team1, $match->team1_logo], [$match->team2, $match->team2_logo]] as [$team, $logo]) {
                if (!isset($teams[$team])) {
                    $teams[$team] = ['logo' => $logo, 'И' => 0, 'В' => 0, 'ВП' => 0, 'П' => 0, 'ПП' => 0, 'gf' => 0, 'ga' => 0, 'О' => 0];
                }
            }

            $teams[$match->team1]['И']++;
            $teams[$match->team2]['И']++;
            $teams[$match->team1]['gf'] += $g1;
            $teams[$match->team1]['ga'] += $g2;
            $teams[$match->team2]['gf'] += $g2;
            $teams[$match->team2]['ga'] += $g1;

            $pw = $match->penalty_winner;
            if ($pw) {
                $winner = ($pw === 'team1') ? $match->team1 : $match->team2;
                $loser  = ($pw === 'team1') ? $match->team2 : $match->team1;
                $teams[$winner]['ВП']++;
                $teams[$winner]['О'] += 2;
                $teams[$loser]['ПП']++;
                $teams[$loser]['О'] += 1;
            } elseif ($g1 > $g2) {
                $teams[$match->team1]['В']++;
                $teams[$match->team1]['О'] += 3;
                $teams[$match->team2]['П']++;
            } elseif ($g2 > $g1) {
                $teams[$match->team2]['В']++;
                $teams[$match->team2]['О'] += 3;
                $teams[$match->team1]['П']++;
            } else {
                $teams[$match->team1]['О']++;
                $teams[$match->team2]['О']++;
            }
        }

        if (empty($teams)) continue;

        uasort($teams, function ($a, $b) {
            if ($b['О'] !== $a['О']) return $b['О'] - $a['О'];
            $diffA = $a['gf'] - $a['ga'];
            $diffB = $b['gf'] - $b['ga'];
            if ($diffB !== $diffA) return $diffB - $diffA;
            return $b['gf'] - $a['gf'];
        });

        $groupStandings[$groupName] = $teams;
    }
@endphp

{{-- ═══════════════════════════════════════════
     Общий заголовок с кнопками-переключателями
════════════════════════════════════════════ --}}
<div class="rfs-header">
    <div class="rfs__title">Турнирная таблица</div>
    <div class="table__buttons rfs-header__buttons">
        <button class="button is-active" type="button" id="rfs-btn-table">
            <div class="button__text">Таблица</div>
        </button>
        <button class="button" type="button" id="rfs-btn-playoff">
            <div class="button__text">Плей-офф</div>
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     Вкладка: ТАБЛИЦА
════════════════════════════════════════════ --}}
<div id="rfs-tab-table">
    @if(!empty($groupStandings))
    <section class="table khl-table rfs-groups-section">
        <div class="table__wrapper is-active rfs-groups-grid">
            @foreach($groupStandings as $groupName => $teams)
            <div class="table__item is-active">
                <div class="khl-table__header">{{ $groupName }}</div>
                <div class="table__container">
                    <table>
                        <tbody class="table__body">
                            <tr class="table__row">
                                <td class="table__cell rfs-cell-bold">№</td>
                                <td class="table__cell rfs-cell-club">Клуб</td>
                                <td class="table__cell rfs-cell-bold">И</td>
                                <td class="table__cell rfs-cell-bold">В</td>
                                <td class="table__cell rfs-cell-bold">ВП</td>
                                <td class="table__cell rfs-cell-bold">П</td>
                                <td class="table__cell rfs-cell-bold">ПП</td>
                                <td class="table__cell rfs-cell-bold">М</td>
                                <td class="table__cell rfs-cell-bold">О</td>
                            </tr>
                            @foreach($teams as $teamName => $stat)
                            <tr class="table__row">
                                <td class="table__cell rfs-cell-bold">{{ $loop->index + 1 }}</td>
                                <td class="table__cell table__cell--team rfs-cell-club">
                                    <div class="rfs-club">
                                        @if($stat['logo'])
                                            <img src="{{ $stat['logo'] }}" alt="{{ $teamName }}" loading="lazy">
                                        @endif
                                        <span>{{ $teamName }}</span>
                                    </div>
                                </td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['И'] }}</td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['В'] }}</td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['ВП'] }}</td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['П'] }}</td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['ПП'] }}</td>
                                <td class="table__cell rfs-cell-bold">{{ $stat['gf'] }}:{{ $stat['ga'] }}</td>
                                <td class="table__cell rfs-cell-bold color-red">{{ $stat['О'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

{{-- ═══════════════════════════════════════════
     Вкладка: ПЛЕЙ-ОФФ
════════════════════════════════════════════ --}}
@php
    // Распределяем матчи по раундам: первые 4 → 1/4, следующие 2 → 1/2, последний → финал
    $playoffSorted = $playoffMatches->sortBy('id')->values();
    $rounds = [
        ['title' => '1/4 ФИНАЛА', 'matches' => $playoffSorted->slice(0, 4)->values(), 'slots' => 4],
        ['title' => '1/2 ФИНАЛА', 'matches' => $playoffSorted->slice(4, 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'matches' => $playoffSorted->slice(6, 1)->values(), 'slots' => 1],
    ];
@endphp

<div id="rfs-tab-playoff" style="display:none;">
    <section class="rfs rfs-playoff">
        <div class="rfs-bracket">
            @foreach($rounds as $round)
            <div class="rfs-bracket__round">
                <div class="rfs-bracket__round-title">{{ $round['title'] }}</div>
                <div class="rfs-bracket__matches">
                    @for($i = 0; $i < $round['slots']; $i++)
                    @php $match = $round['matches']->get($i); @endphp

                    @if($match)
                        @php
                            // Логотипы: сначала из матча, потом из общей карты по имени команды
                            $logo1 = $match->team1_logo ?: ($teamLogos[$match->team1] ?? null);
                            $logo2 = $match->team2_logo ?: ($teamLogos[$match->team2] ?? null);

                            // Определяем тип данных по содержимому, а не по флагу is_played:
                            // счёт выглядит как "3:1" или "3:1 | 3:2", дата — как "08.04.2026 18:15"
                            $parts = array_map('trim', explode(' | ', $match->score_or_date));
                            $isActualScore = (bool) preg_match('/^\d+:\d+$/', $parts[0] ?? '');

                            $scores = [];
                            $dateLines = [];

                            if ($isActualScore) {
                                $scores = $parts;
                            } else {
                                // Парсим каждую часть как дату
                                foreach ($parts as $part) {
                                    // Нормализуем неразрывные пробелы и разные тире
                                    $part = str_replace(["\xc2\xa0", "\xe2\x80\x93", "\xe2\x80\x94"], [' ', '-', '-'], $part);
                                    $part = trim($part);

                                    // Диапазон: "05.05.2026 - 07.05.2026" или "05.05.26 - 07.05.26"
                                    if (preg_match('/^(\d{2}\.\d{2}\.[\d]+)\s*-+\s*(\d{2}\.\d{2}\.[\d]+)/', $part, $r)) {
                                        $dateLines[] = $r[1];
                                        $dateLines[] = $r[2];
                                    }
                                    // Одна дата с временем: "08.04.2026 18:15"
                                    elseif (preg_match('/^(\d{2}\.\d{2}\.[\d]+)(?:\s+(\d{2}:\d{2}))?/', $part, $r)) {
                                        $dateLines[] = $r[1];
                                        if (!empty($r[2])) $dateLines[] = $r[2];
                                    } else {
                                        $dateLines[] = $part;
                                    }
                                }
                            }
                        @endphp
                        <div class="rfs-bracket__match {{ !$isActualScore ? 'rfs-bracket__match--upcoming' : '' }}">
                            {{-- Команда 1 --}}
                            <div class="rfs-bracket__team">
                                @if($logo1)
                                    <img class="rfs-bracket__logo" src="{{ $logo1 }}" alt="{{ $match->team1 }}">
                                @else
                                    <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($match->team1, 0, 2)) }}</div>
                                @endif
                                <span class="rfs-bracket__team-name">{{ $match->team1 }}</span>
                            </div>
                            {{-- Счёт / Дата --}}
                            <div class="rfs-bracket__scores">
                                @if($isActualScore)
                                    @foreach($scores as $score)
                                        <span class="rfs-bracket__score">{{ $score }}</span>
                                    @endforeach
                                @else
                                    @foreach($dateLines as $line)
                                        <span class="rfs-bracket__date">{{ $line }}</span>
                                    @endforeach
                                @endif
                            </div>
                            {{-- Команда 2 --}}
                            <div class="rfs-bracket__team">
                                @if($logo2)
                                    <img class="rfs-bracket__logo" src="{{ $logo2 }}" alt="{{ $match->team2 }}">
                                @else
                                    <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($match->team2, 0, 2)) }}</div>
                                @endif
                                <span class="rfs-bracket__team-name">{{ $match->team2 }}</span>
                            </div>
                        </div>
                    @else
                        {{-- Пустой слот — "?" --}}
                        <div class="rfs-bracket__match rfs-bracket__match--empty">
                            <div class="rfs-bracket__team">
                                <div class="rfs-bracket__logo-placeholder rfs-bracket__logo-placeholder--unknown">?</div>
                                <span class="rfs-bracket__team-name rfs-bracket__team-name--unknown">TBD</span>
                            </div>
                            <div class="rfs-bracket__scores"></div>
                            <div class="rfs-bracket__team">
                                <div class="rfs-bracket__logo-placeholder rfs-bracket__logo-placeholder--unknown">?</div>
                                <span class="rfs-bracket__team-name rfs-bracket__team-name--unknown">TBD</span>
                            </div>
                        </div>
                    @endif
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

{{-- ═══════════════════════════════════════════
     Секция: БЛИЖАЙШИЕ МАТЧИ
════════════════════════════════════════════ --}}
@if($upcomingMatches->isNotEmpty())
<section class="rfs-upcoming-section">
    <div class="rfs-upcoming__header">
        <div class="rfs__title">Ближайшие матчи</div>
        <div class="rfs-upcoming__arrows">
            <button class="rfs-upcoming__arrow" id="rfs-arrow-prev" type="button">&#8592;</button>
            <button class="rfs-upcoming__arrow" id="rfs-arrow-next" type="button">&#8594;</button>
        </div>
    </div>
    <div class="rfs-upcoming__scroll" id="rfs-upcoming-scroll">
        @foreach($upcomingMatches as $match)
        @php
            preg_match('/^(\d{2})\.(\d{2})\.(\d{4})(?:\s+(\d{2}:\d{2}))?$/', $match->score_or_date, $m);
            $dateFormatted = ($m[1] ?? '') . '/' . ($m[2] ?? '');
            $time = $m[4] ?? '';
            $logo1 = $match->team1_logo ?: ($teamLogos[$match->team1] ?? null);
            $logo2 = $match->team2_logo ?: ($teamLogos[$match->team2] ?? null);
        @endphp
        @if(empty($m[1]) || empty($match->team1) || empty($match->team2))
            @continue
        @endif
        <div class="rfs-mcard">
            <div class="rfs-mcard__header">{{ $match->group_name ?? 'Кубок России' }}</div>
            <div class="rfs-mcard__body">
                <div class="rfs-mcard__date-row">
                    <span class="rfs-mcard__date">{{ $dateFormatted }}</span>
                    @if($time)
                        <span class="rfs-mcard__time">{{ $time }} мск</span>
                    @endif
                </div>
                <div class="rfs-mcard__teams">
                    <div class="rfs-mcard__team">
                        <div class="rfs-mcard__logo">
                            @if($logo1)
                                <img src="{{ $logo1 }}" alt="{{ $match->team1 }}">
                            @else
                                <span>{{ mb_strtoupper(mb_substr($match->team1, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="rfs-mcard__team-info">
                            <span class="rfs-mcard__team-name">{{ $match->team1 }}</span>
                            @if($match->team1_city)
                                <span class="rfs-mcard__city">{{ $match->team1_city }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="rfs-mcard__sep">—</span>
                    <div class="rfs-mcard__team">
                        <div class="rfs-mcard__logo">
                            @if($logo2)
                                <img src="{{ $logo2 }}" alt="{{ $match->team2 }}">
                            @else
                                <span>{{ mb_strtoupper(mb_substr($match->team2, 0, 2)) }}</span>
                            @endif
                        </div>
                        <div class="rfs-mcard__team-info">
                            <span class="rfs-mcard__team-name">{{ $match->team2 }}</span>
                            @if($match->team2_city)
                                <span class="rfs-mcard__city">{{ $match->team2_city }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<script>
(function () {
    var btnTable   = document.getElementById('rfs-btn-table');
    var btnPlayoff = document.getElementById('rfs-btn-playoff');
    var tabTable   = document.getElementById('rfs-tab-table');
    var tabPlayoff = document.getElementById('rfs-tab-playoff');

    btnTable.addEventListener('click', function () {
        tabTable.style.display   = 'block';
        tabPlayoff.style.display = 'none';
        btnTable.classList.add('is-active');
        btnPlayoff.classList.remove('is-active');
    });

    btnPlayoff.addEventListener('click', function () {
        tabPlayoff.style.display = 'block';
        tabTable.style.display   = 'none';
        btnPlayoff.classList.add('is-active');
        btnTable.classList.remove('is-active');
    });

    // Стрелки слайдера ближайших матчей
    var scroll = document.getElementById('rfs-upcoming-scroll');
    var prev   = document.getElementById('rfs-arrow-prev');
    var next   = document.getElementById('rfs-arrow-next');
    if (scroll && prev && next) {
        var step = function () { return scroll.querySelector('.rfs-mcard').offsetWidth + 20; };
        prev.addEventListener('click', function () { scroll.scrollBy({ left: -step(), behavior: 'smooth' }); });
        next.addEventListener('click', function () { scroll.scrollBy({ left:  step(), behavior: 'smooth' }); });
    }
})();
</script>
