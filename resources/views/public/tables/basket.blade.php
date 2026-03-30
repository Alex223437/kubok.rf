@push('styles')
<link href="/assets/basket.css" rel="stylesheet">
@endpush

@php
    $leagueTag    = $tag ?? 'msl';
    $allStandings = \App\Models\BasketballStanding::where('tag', $leagueTag)->orderBy('rank')->get();

    $regularRows  = $allStandings->where('section', 'Регулярный чемпионат')->values();
    $hasRegular   = $regularRows->isNotEmpty() && !($defaultPlayoff ?? false);
    $extra5_8     = $allStandings->whereIn('section', ['Игры за 5-8 места', 'Игры за 5-6 места'])->values();
    $extra11_14   = $allStandings->whereIn('section', ['Игры за 11-14 места', 'Игры за 9-12 места', 'Игры за 9-10 места'])->values();

    $playoffPairs = \App\Models\BasketballPlayoffPair::where('tag', $leagueTag)->get();
    $playinPairs  = $playoffPairs->where('section', 'playin')->sortBy('sort')->values();
    $bracketPairs = $playoffPairs->where('section', 'playoff')->sortBy('sort')->values();

    $rounds = [
        ['title' => '1/4 ФИНАЛА', 'pairs' => $bracketPairs->where('round', 4)->values(), 'slots' => 4],
        ['title' => '1/2 ФИНАЛА', 'pairs' => $bracketPairs->where('round', 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'pairs' => $bracketPairs->where('round', 1)->values(), 'slots' => 2],
    ];

    function bsk_pair_display(\App\Models\BasketballPlayoffPair $pair): array {
        $scores = [];
        $dates  = [];
        if ($pair->score1 !== null && $pair->score2 !== null && ($pair->score1 > 0 || $pair->score2 > 0)) {
            $scores[] = $pair->score1 . ':' . $pair->score2;
        }
        foreach ($pair->games ?? [] as $game) {
            if (($game['status'] ?? '') === 'Scheduled' && !empty($game['date'])) {
                $dates[] = $game['date'];
            }
        }
        return ['scores' => $scores, 'dates' => $dates];
    }

    function bsk_standings_table($rows, string $title = ''): void {
    ?>
    <div class="table__wrapper is-active" style="display:block;">
        <div class="table__item is-active">
            <?php if ($title): ?><div class="khl-table__header"><?= e($title) ?></div><?php endif; ?>
            <div class="table__container">
                <table>
                    <tbody class="table__body">
                        <tr class="table__row">
                            <td class="table__cell bsk-cell-hdr">№</td>
                            <td class="table__cell bsk-cell-hdr bsk-cell-club">Клуб</td>
                            <td class="table__cell bsk-cell-hdr">И</td>
                            <td class="table__cell bsk-cell-hdr">В</td>
                            <td class="table__cell bsk-cell-hdr">П</td>
                            <td class="table__cell bsk-cell-hdr">%</td>
                            <td class="table__cell bsk-cell-hdr">Последние 5</td>
                            <td class="table__cell bsk-cell-hdr">Забито</td>
                            <td class="table__cell bsk-cell-hdr">Пропущено</td>
                            <td class="table__cell bsk-cell-hdr">+/-</td>
                            <td class="table__cell bsk-cell-hdr">Очки</td>
                        </tr>
                        <?php foreach ($rows as $i => $row):
                            $last5 = $row->last_5 ? array_map('trim', explode(',', $row->last_5)) : [];
                            [$scored, $missed] = array_pad(explode('/', $row->plus_minus ?? '/'), 2, '0');
                            $diff = (int)$scored - (int)$missed;
                        ?>
                        <tr class="table__row">
                            <td class="table__cell bsk-cell-num"><?= $i + 1 ?></td>
                            <td class="table__cell table__cell--team bsk-cell-club">
                                <div class="rfs-club">
                                    <?php if ($row->logo): ?>
                                        <img src="<?= e($row->logo) ?>" alt="<?= e($row->team) ?>" loading="lazy">
                                    <?php endif; ?>
                                    <span><?= e($row->team) ?></span>
                                </div>
                            </td>
                            <td class="table__cell bsk-cell-num"><?= e($row->games) ?></td>
                            <td class="table__cell bsk-cell-num"><?= e($row->wins) ?></td>
                            <td class="table__cell bsk-cell-num"><?= e($row->losses) ?></td>
                            <td class="table__cell bsk-cell-num"><?= e($row->win_pct) ?>%</td>
                            <td class="table__cell bsk-cell-last5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="80" height="12" viewBox="0 0 80 12" fill="none">
                                    <?php
                                    $filled = array_slice($last5, 0, 5);
                                    $total  = max(count($filled), 5);
                                    for ($j = 0; $j < 5; $j++):
                                        $result = $filled[$j] ?? null;
                                        $color  = $result === 'W' ? '#5BA500' : ($result === 'L' ? '#E80024' : '#D9D9D9');
                                    ?>
                                    <circle cx="<?= 6 + $j * 17 ?>" cy="6" r="6" fill="<?= $color ?>"/>
                                    <?php endfor; ?>
                                </svg>
                            </td>
                            <td class="table__cell bsk-cell-num"><?= e($scored) ?></td>
                            <td class="table__cell bsk-cell-num"><?= e($missed) ?></td>
                            <td class="table__cell bsk-cell-num"><?= $diff > 0 ? '+' . $diff : $diff ?></td>
                            <td class="table__cell bsk-cell-pts"><?= e($row->points) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php
    }
@endphp

{{-- ═══ Вкладка: ЧЕМПИОНАТ ══════════════════ --}}
@if($hasRegular)
<div id="bsk-tab-standings">
    <div class="rfs-header">
        <div class="rfs__title">Турнирная таблица</div>
        <div class="table__buttons rfs-header__buttons">
            <button class="button is-active" type="button" id="bsk-btn-standings">
                <div class="button__text">Чемпионат</div>
            </button>
            <button class="button" type="button" id="bsk-btn-playoff">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>

    @if($regularRows->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @php bsk_standings_table($regularRows, 'Регулярный чемпионат') @endphp
    </section>
    @endif
</div>
@endif

{{-- ═══ Вкладка: ПЛЕЙ-ОФФ ══════════════════ --}}
<div id="bsk-tab-playoff" @if($hasRegular) style="display:none;" @endif>
    <div class="rfs-header">
        <div class="rfs__title">Плей-офф</div>
        <div class="table__buttons rfs-header__buttons">
            @if($hasRegular)
            <button class="button" type="button" id="bsk-btn-standings2">
                <div class="button__text">Чемпионат</div>
            </button>
            @endif
            <button class="button is-active" type="button" id="bsk-btn-playoff2">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>

    {{-- Плей-ин (скрыт, пока нет в дизайне) --}}
    @if(false && $playinPairs->isNotEmpty())
    <section class="rfs rfs-playoff">
        <div class="rfs-bracket__round-title bsk-playin-title">ПЛЕЙ-ИН</div>
        <div class="bsk-bracket--playin">
            @foreach($playinPairs as $pair)
            @php $disp = bsk_pair_display($pair); $hasScore = !empty($disp['scores']); @endphp
            <div class="rfs-bracket__match {{ !$hasScore ? 'rfs-bracket__match--upcoming' : '' }}">
                <div class="rfs-bracket__team">
                    @if($pair->team1_logo)
                        <img class="rfs-bracket__logo" src="{{ $pair->team1_logo }}" alt="{{ $pair->team1_name }}">
                    @else
                        <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($pair->team1_name ?? '?', 0, 2)) }}</div>
                    @endif
                    <span class="rfs-bracket__team-name">{{ $pair->team1_name }}</span>
                </div>
                <div class="rfs-bracket__scores">
                    @if($hasScore)
                        @foreach($disp['scores'] as $s)<span class="rfs-bracket__score">{{ $s }}</span>@endforeach
                    @else
                        @foreach($disp['dates'] as $d)<span class="rfs-bracket__date">{{ $d }}</span>@endforeach
                    @endif
                </div>
                <div class="rfs-bracket__team">
                    @if($pair->team2_logo)
                        <img class="rfs-bracket__logo" src="{{ $pair->team2_logo }}" alt="{{ $pair->team2_name }}">
                    @else
                        <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($pair->team2_name ?? '?', 0, 2)) }}</div>
                    @endif
                    <span class="rfs-bracket__team-name">{{ $pair->team2_name }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Основная сетка плей-офф --}}
    <section class="rfs rfs-playoff bsk-playoff">
        <div class="rfs-bracket bsk-bracket--playoff">
            @foreach($rounds as $round)
            <div class="rfs-bracket__round">
                <div class="rfs-bracket__round-title">{{ $round['title'] }}</div>
                <div class="rfs-bracket__matches">
                    @for($i = 0; $i < $round['slots']; $i++)
                    @php $pair = $round['pairs']->get($i); @endphp
                    @if($pair)
                        @php $disp = bsk_pair_display($pair); $hasScore = !empty($disp['scores']); @endphp
                        <div class="rfs-bracket__match {{ !$hasScore ? 'rfs-bracket__match--upcoming' : '' }}">
                            <div class="rfs-bracket__team">
                                @if($pair->team1_logo)<img class="rfs-bracket__logo" src="{{ $pair->team1_logo }}" alt="{{ $pair->team1_name }}">
                                @else<div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($pair->team1_name ?? '?', 0, 2)) }}</div>@endif
                                <span class="rfs-bracket__team-name">{{ $pair->team1_name }}</span>
                            </div>
                            <div class="rfs-bracket__scores">
                                @if($hasScore)
                                    @foreach($disp['scores'] as $s)<span class="rfs-bracket__score">{{ $s }}</span>@endforeach
                                @else
                                    @foreach($disp['dates'] as $d)<span class="rfs-bracket__date">{{ $d }}</span>@endforeach
                                @endif
                            </div>
                            <div class="rfs-bracket__team">
                                @if($pair->team2_logo)<img class="rfs-bracket__logo" src="{{ $pair->team2_logo }}" alt="{{ $pair->team2_name }}">
                                @else<div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($pair->team2_name ?? '?', 0, 2)) }}</div>@endif
                                <span class="rfs-bracket__team-name">{{ $pair->team2_name }}</span>
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

    {{-- Игры за места --}}
    @foreach([['rows' => $extra5_8, 'title' => $extra5_8->first()?->section ?? 'Игры за 5-8 места'], ['rows' => $extra11_14, 'title' => $extra11_14->first()?->section ?? 'Игры за 11-14 места']] as $extra)
    @if($extra['rows']->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @php bsk_standings_table($extra['rows'], $extra['title']) @endphp
    </section>
    @endif
    @endforeach
</div>

@include('components.upcoming-matches', ['sport' => 'basketball'])

<script>
(function () {
    var tabStandings = document.getElementById('bsk-tab-standings');
    var tabPlayoff   = document.getElementById('bsk-tab-playoff');

    function showStandings() {
        tabStandings.style.display = 'block';
        tabPlayoff.style.display   = 'none';
        ['bsk-btn-standings','bsk-btn-standings2'].forEach(function(id){var el=document.getElementById(id);if(el)el.classList.add('is-active');});
        ['bsk-btn-playoff','bsk-btn-playoff2'].forEach(function(id){var el=document.getElementById(id);if(el)el.classList.remove('is-active');});
    }
    function showPlayoff() {
        tabPlayoff.style.display   = 'block';
        tabStandings.style.display = 'none';
        ['bsk-btn-playoff','bsk-btn-playoff2'].forEach(function(id){var el=document.getElementById(id);if(el)el.classList.add('is-active');});
        ['bsk-btn-standings','bsk-btn-standings2'].forEach(function(id){var el=document.getElementById(id);if(el)el.classList.remove('is-active');});
    }

    ['bsk-btn-standings','bsk-btn-standings2'].forEach(function(id){var el=document.getElementById(id);if(el)el.addEventListener('click',showStandings);});
    ['bsk-btn-playoff','bsk-btn-playoff2'].forEach(function(id){var el=document.getElementById(id);if(el)el.addEventListener('click',showPlayoff);});
})();
</script>
