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

    // Путь регионов: плей-офф
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

    // Хелпер: подготовить строки для x-bracket-match из score_or_date
    function rfs_bracket_lines(string $scoreOrDate): array {
        $parts = array_map('trim', explode(' | ', $scoreOrDate));
        $isScore = (bool) preg_match('/^\d+:\d+$/', $parts[0] ?? '');
        if ($isScore) return ['isScore' => true, 'lines' => $parts];
        $lines = [];
        foreach ($parts as $part) {
            $part = str_replace(["\xc2\xa0", "\xe2\x80\x93", "\xe2\x80\x94"], [' ', '-', '-'], $part);
            $part = trim($part);
            if (preg_match('/^(\d{2}\.\d{2}\.[\d]+)\s*-+\s*(\d{2}\.\d{2}\.[\d]+)/', $part, $r)) {
                $lines[] = $r[1]; $lines[] = $r[2];
            } elseif (preg_match('/^(\d{2}\.\d{2}\.[\d]+)(?:\s+(\d{2}:\d{2}))?/', $part, $r)) {
                $lines[] = $r[1];
                if (!empty($r[2])) $lines[] = $r[2];
            } else {
                $lines[] = $part;
            }
        }
        return ['isScore' => false, 'lines' => $lines];
    }

    // Плей-офф регионов
    $regionsPlayoffSorted = $regionsPlayoff->sortBy('id')->values();
    $regionsRoundsPlayoff = [
        ['title' => '1/2 ФИНАЛА', 'matches' => $regionsPlayoffSorted->slice(0, 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'matches' => $regionsPlayoffSorted->slice(2, 1)->values(), 'slots' => 1],
    ];

    // Плей-офф РПЛ
    $playoffSorted = $playoffMatches->sortBy('id')->values();
    $rounds = [
        ['title' => '1/4 ФИНАЛА', 'matches' => $playoffSorted->slice(0, 4)->values(), 'slots' => 4],
        ['title' => '1/2 ФИНАЛА', 'matches' => $playoffSorted->slice(4, 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'matches' => $playoffSorted->slice(6, 1)->values(), 'slots' => 1],
    ];
@endphp

{{-- ═══════════════════════════════════════════
     Единая секция с шапкой и вкладками
════════════════════════════════════════════ --}}
<div id="rfs-section">

{{-- Единая шапка с 3 кнопками --}}
<div class="rfs-header">
    <div class="rfs__title">Турнирная таблица</div>
    <div class="rfs-header__buttons rfs-header__buttons--triple">
        <button class="button is-active" type="button" id="rfs-btn-table">
            <div class="button__text">Путь РПЛ: Групповой этап</div>
        </button>
        <button class="button" type="button" id="rfs-btn-playoff">
            <div class="button__text">Путь РПЛ: Плей-офф</div>
        </button>
        <button class="button" type="button" id="rfs-btn-regions">
            <div class="button__text">Путь регионов</div>
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     Вкладка: ПУТЬ РПЛ — ГРУППОВОЙ ЭТАП
════════════════════════════════════════════ --}}
<div id="rfs-tab-table">
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
                                    <x-team-logo :name="$teamName" :logo="$stat['logo']" />
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
    @else
        <div style="padding: 20px;">Нет данных. Запустите парсер.</div>
    @endif
</div>

{{-- ═══════════════════════════════════════════
     Вкладка: ПУТЬ РПЛ — ПЛЕЙ-ОФФ
════════════════════════════════════════════ --}}
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
                            $logo1 = $match->team1_logo ?: ($teamLogos[$match->team1] ?? null);
                            $logo2 = $match->team2_logo ?: ($teamLogos[$match->team2] ?? null);
                            $bl = rfs_bracket_lines($match->score_or_date);
                        @endphp
                        <x-bracket-match
                            :team1="$match->team1"
                            :team2="$match->team2"
                            :logo1="$logo1"
                            :logo2="$logo2"
                            :is-score="$bl['isScore']"
                            :lines="$bl['lines']"
                        />
                    @else
                        <x-bracket-match :empty="true" />
                    @endif
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

{{-- ═══════════════════════════════════════════
     Вкладка: ПУТЬ РЕГИОНОВ — ПЛЕЙ-ОФФ
════════════════════════════════════════════ --}}
<div id="rfs-tab-regions" style="display:none;">
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
                            $blr = rfs_bracket_lines($match->score_or_date);
                        @endphp
                        <x-bracket-match
                            :team1="$match->team1"
                            :team2="$match->team2"
                            :logo1="$logo1r"
                            :logo2="$logo2r"
                            :is-score="$blr['isScore']"
                            :lines="$blr['lines']"
                        />
                    @else
                        <x-bracket-match :empty="true" />
                    @endif
                    @endfor
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>

</div>{{-- /rfs-section --}}

{{-- ═══════════════════════════════════════════
     Секция: БЛИЖАЙШИЕ МАТЧИ
════════════════════════════════════════════ --}}
@include('components.upcoming-matches', ['sport' => 'rfs', 'matches' => \App\Models\UpcomingMatch::where('sport', 'rfs')->where('match_at', '>=', now())->orderBy('match_at')->get(), 'eventsUrl' => $page->getPayloadValue('events_url')])

@include('partials.tab-switcher')
<script>
initTabSwitcher(
    { table: 'rfs-tab-table', playoff: 'rfs-tab-playoff', regions: 'rfs-tab-regions' },
    { table: ['rfs-btn-table'], playoff: ['rfs-btn-playoff'], regions: ['rfs-btn-regions'] }
);
</script>
