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

    // Плей-офф РПЛ
    $playoffMatches = $allMatches->where('group_name', 'Путь РПЛ. Плей-офф');

    // Путь регионов: раунды и плей-офф
    $regionsRoundNames = [
        'Путь регионов. Раунд 1',
        'Путь регионов. Раунд 2',
        'Путь регионов. Раунд 3',
        'Путь регионов. Раунд 4',
        'Путь регионов. Раунд 5',
        'Путь регионов. Раунд 6',
    ];
    $regionsRounds = $allMatches->whereIn('group_name', $regionsRoundNames);
    $regionsPlayoff = $allMatches->where('group_name', 'Путь регионов. Плей-офф');

    // Считаем турнирные таблицы из результатов групповых матчей (только Путь РПЛ. Группа *)
    $groupStandings = [];
    foreach ($allMatches->where('is_played', true)
        ->filter(fn($m) => str_starts_with($m->group_name ?? '', 'Путь РПЛ. Группа'))
        ->groupBy('group_name') as $groupName => $groupMatches) {
        $teams = [];

        foreach ($groupMatches as $match) {
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
{{-- ═══════════════════════════════════════════
     Вкладка: ТАБЛИЦА
════════════════════════════════════════════ --}}
<div id="rfs-tab-table">
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
    @if(!empty($groupStandings))
    <section class="table khl-table rfs-groups-section">
        <div class="table__wrapper is-active rfs-groups-grid" style="display:grid;">
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
    <div class="rfs-header">
        <div class="rfs__title">Плей-офф</div>
        <div class="table__buttons rfs-header__buttons">
            <button class="button" type="button" id="rfs-btn-table2">
                <div class="button__text">Таблица</div>
            </button>
            <button class="button is-active" type="button" id="rfs-btn-playoff2">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>
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
     Секция: БЛИЖАЙШИЕ МАТЧИ (Путь РПЛ)
════════════════════════════════════════════ --}}
@include('components.upcoming-matches', ['sport' => 'rfs', 'matches' => \App\Models\UpcomingMatch::where('sport', 'rfs')->where('league_name', 'LIKE', 'Путь РПЛ%')->where('match_at', '>=', now())->orderBy('match_at')->get()])

{{-- ═══════════════════════════════════════════
     Секция: ПУТЬ РЕГИОНОВ — ТУРНИРНАЯ ТАБЛИЦА
════════════════════════════════════════════ --}}
<div id="regions-tab-rounds">
    <div class="rfs-header">
        <div class="rfs__title">Путь регионов</div>
        <div class="table__buttons rfs-header__buttons">
            <button class="button is-active" type="button" id="regions-btn-rounds">
                <div class="button__text">Раунды</div>
            </button>
            <button class="button" type="button" id="regions-btn-playoff">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>
    @if($regionsRounds->isNotEmpty())
    <section class="table khl-table rfs-groups-section">
        <div class="table__wrapper is-active rfs-groups-grid" style="display:grid;">
            @foreach($regionsRounds->groupBy('group_name') as $roundName => $roundMatches)
            <div class="table__item is-active">
                <div class="khl-table__header">{{ $roundName }}</div>
                <div class="table__container">
                    <table>
                        <tbody class="table__body">
                            <tr class="table__row">
                                <td class="table__cell rfs-cell-club">Команда 1</td>
                                <td class="table__cell rfs-cell-bold" style="text-align:center; width:80px;">Счёт</td>
                                <td class="table__cell rfs-cell-club">Команда 2</td>
                            </tr>
                            @foreach($roundMatches as $match)
                            @php
                                $logo1r = $match->team1_logo ?: ($teamLogos[$match->team1] ?? null);
                                $logo2r = $match->team2_logo ?: ($teamLogos[$match->team2] ?? null);
                            @endphp
                            <tr class="table__row">
                                <td class="table__cell table__cell--team rfs-cell-club">
                                    <div class="rfs-club">
                                        @if($logo1r)<img src="{{ $logo1r }}" alt="{{ $match->team1 }}" loading="lazy">@endif
                                        <span>{{ $match->team1 }}</span>
                                    </div>
                                </td>
                                <td class="table__cell rfs-cell-bold {{ $match->is_played ? 'color-red' : '' }}" style="text-align:center; white-space:nowrap;">
                                    {{ $match->score_or_date }}
                                </td>
                                <td class="table__cell table__cell--team rfs-cell-club">
                                    <div class="rfs-club">
                                        @if($logo2r)<img src="{{ $logo2r }}" alt="{{ $match->team2 }}" loading="lazy">@endif
                                        <span>{{ $match->team2 }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @else
        <div style="padding: 20px;">Нет данных. Запустите парсер.</div>
    @endif
</div>

@php
    $regionsPlayoffSorted = $regionsPlayoff->sortBy('id')->values();
    $regionsRoundsPlayoff = [
        ['title' => '1/2 ФИНАЛА', 'matches' => $regionsPlayoffSorted->slice(0, 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'matches' => $regionsPlayoffSorted->slice(2, 1)->values(), 'slots' => 1],
    ];
@endphp

<div id="regions-tab-playoff" style="display:none;">
    <div class="rfs-header">
        <div class="rfs__title">Путь регионов. Плей-офф</div>
        <div class="table__buttons rfs-header__buttons">
            <button class="button" type="button" id="regions-btn-rounds2">
                <div class="button__text">Раунды</div>
            </button>
            <button class="button is-active" type="button" id="regions-btn-playoff2">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>
    <section class="rfs rfs-playoff">
        <div class="rfs-bracket">
            @foreach($regionsRoundsPlayoff as $round)
            <div class="rfs-bracket__round">
                <div class="rfs-bracket__round-title">{{ $round['title'] }}</div>
                <div class="rfs-bracket__matches">
                    @for($i = 0; $i < $round['slots']; $i++)
                    @php $match = $round['matches']->get($i); @endphp
                    @if($match)
                        @php
                            $logo1r = $match->team1_logo ?: ($teamLogos[$match->team1] ?? null);
                            $logo2r = $match->team2_logo ?: ($teamLogos[$match->team2] ?? null);
                            $parts  = array_map('trim', explode(' | ', $match->score_or_date));
                            $isActualScore = (bool) preg_match('/^\d+:\d+$/', $parts[0] ?? '');
                            $scores = $isActualScore ? $parts : [];
                            $dateLines = [];
                            if (!$isActualScore) {
                                foreach ($parts as $part) {
                                    $part = str_replace(["\xc2\xa0", "\xe2\x80\x93", "\xe2\x80\x94"], [' ', '-', '-'], $part);
                                    $part = trim($part);
                                    if (preg_match('/^(\d{2}\.\d{2}\.[\d]+)\s*-+\s*(\d{2}\.\d{2}\.[\d]+)/', $part, $r)) {
                                        $dateLines[] = $r[1]; $dateLines[] = $r[2];
                                    } elseif (preg_match('/^(\d{2}\.\d{2}\.[\d]+)(?:\s+(\d{2}:\d{2}))?/', $part, $r)) {
                                        $dateLines[] = $r[1];
                                        if (!empty($r[2])) $dateLines[] = $r[2];
                                    } else { $dateLines[] = $part; }
                                }
                            }
                        @endphp
                        <div class="rfs-bracket__match {{ !$isActualScore ? 'rfs-bracket__match--upcoming' : '' }}">
                            <div class="rfs-bracket__team">
                                @if($logo1r)<img class="rfs-bracket__logo" src="{{ $logo1r }}" alt="{{ $match->team1 }}">
                                @else<div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($match->team1, 0, 2)) }}</div>@endif
                                <span class="rfs-bracket__team-name">{{ $match->team1 }}</span>
                            </div>
                            <div class="rfs-bracket__scores">
                                @if($isActualScore)
                                    @foreach($scores as $score)<span class="rfs-bracket__score">{{ $score }}</span>@endforeach
                                @else
                                    @foreach($dateLines as $line)<span class="rfs-bracket__date">{{ $line }}</span>@endforeach
                                @endif
                            </div>
                            <div class="rfs-bracket__team">
                                @if($logo2r)<img class="rfs-bracket__logo" src="{{ $logo2r }}" alt="{{ $match->team2 }}">
                                @else<div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($match->team2, 0, 2)) }}</div>@endif
                                <span class="rfs-bracket__team-name">{{ $match->team2 }}</span>
                            </div>
                        </div>
                    @else
                        <div class="rfs-bracket__match rfs-bracket__match--empty">
                            <div class="rfs-bracket__team"><div class="rfs-bracket__logo-placeholder rfs-bracket__logo-placeholder--unknown">?</div><span class="rfs-bracket__team-name rfs-bracket__team-name--unknown">TBD</span></div>
                            <div class="rfs-bracket__scores"></div>
                            <div class="rfs-bracket__team"><div class="rfs-bracket__logo-placeholder rfs-bracket__logo-placeholder--unknown">?</div><span class="rfs-bracket__team-name rfs-bracket__team-name--unknown">TBD</span></div>
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
     Секция: БЛИЖАЙШИЕ МАТЧИ (Путь регионов)
════════════════════════════════════════════ --}}
@include('components.upcoming-matches', ['sport' => 'rfs', 'matches' => \App\Models\UpcomingMatch::where('sport', 'rfs')->where('league_name', 'LIKE', 'Путь регионов%')->where('match_at', '>=', now())->orderBy('match_at')->get()])

<script>
(function () {
    var tabTable   = document.getElementById('rfs-tab-table');
    var tabPlayoff = document.getElementById('rfs-tab-playoff');

    function showTable() {
        tabTable.style.display   = 'block';
        tabPlayoff.style.display = 'none';
        ['rfs-btn-table','rfs-btn-table2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.add('is-active');
        });
        ['rfs-btn-playoff','rfs-btn-playoff2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.remove('is-active');
        });
    }

    function showPlayoff() {
        tabPlayoff.style.display = 'block';
        tabTable.style.display   = 'none';
        ['rfs-btn-playoff','rfs-btn-playoff2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.add('is-active');
        });
        ['rfs-btn-table','rfs-btn-table2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.remove('is-active');
        });
    }

    ['rfs-btn-table','rfs-btn-table2'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', showTable);
    });
    ['rfs-btn-playoff','rfs-btn-playoff2'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', showPlayoff);
    });
})();

// Путь регионов
(function () {
    var tabRounds  = document.getElementById('regions-tab-rounds');
    var tabPlayoff = document.getElementById('regions-tab-playoff');

    function showRounds() {
        tabRounds.style.display  = 'block';
        tabPlayoff.style.display = 'none';
        ['regions-btn-rounds','regions-btn-rounds2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.add('is-active');
        });
        ['regions-btn-playoff','regions-btn-playoff2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.remove('is-active');
        });
    }

    function showPlayoff() {
        tabPlayoff.style.display = 'block';
        tabRounds.style.display  = 'none';
        ['regions-btn-playoff','regions-btn-playoff2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.add('is-active');
        });
        ['regions-btn-rounds','regions-btn-rounds2'].forEach(function(id) {
            var el = document.getElementById(id);
            if (el) el.classList.remove('is-active');
        });
    }

    ['regions-btn-rounds','regions-btn-rounds2'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', showRounds);
    });
    ['regions-btn-playoff','regions-btn-playoff2'].forEach(function(id) {
        var el = document.getElementById(id);
        if (el) el.addEventListener('click', showPlayoff);
    });
})();
</script>
