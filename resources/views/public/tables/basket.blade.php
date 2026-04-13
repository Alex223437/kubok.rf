@push('styles')
<link href="/assets/basket.css" rel="stylesheet">
@endpush

@php
    $leagueTag    = $tag ?? 'msl';
    $womenTag     = $womenTag ?? null;
    $allowedTags  = array_filter([$leagueTag, $womenTag]);

    // Мужская лига
    $allStandings = \App\Models\BasketballStanding::where('tag', $leagueTag)
        ->orderByRaw('CASE WHEN games = 0 OR games IS NULL THEN 1 ELSE 0 END')
        ->orderByRaw('CAST(points AS UNSIGNED) DESC')
        ->orderByRaw('CAST(REPLACE(diff, "+", "") AS SIGNED) DESC')
        ->get();

    $regularRows  = $allStandings->where('section', 'Регулярный чемпионат')->values();
    $hasRegular   = $regularRows->isNotEmpty();
    $extra5_8     = $allStandings->whereIn('section', ['Игры за 5-8 места', 'Игры за 5-6 места'])->values();
    $extra11_14   = $allStandings->whereIn('section', ['Игры за 11-14 места', 'Игры за 9-12 места', 'Игры за 9-10 места'])->values();

    $playoffPairs = \App\Models\BasketballPlayoffPair::where('tag', $leagueTag)->get();
    $playinPairs  = $playoffPairs->where('section', 'playin')->sortBy('sort')->values();
    $bracketPairs = $playoffPairs->where('section', 'playoff')->sortBy('sort')->values();

    $rounds = [
        ['title' => '1/4 ФИНАЛА', 'pairs' => $bracketPairs->where('round', 4)->values(), 'slots' => 4],
        ['title' => '1/2 ФИНАЛА', 'pairs' => $bracketPairs->where('round', 2)->values(), 'slots' => 2],
        ['title' => 'ФИНАЛ',      'pairs' => $bracketPairs->where('round', 1)->sortBy('sort')->take(1)->values(), 'slots' => 1],
    ];

    // Женская лига (только если передан $womenTag)
    if ($womenTag) {
        $wAllStandings = \App\Models\BasketballStanding::where('tag', $womenTag)
            ->orderByRaw('CASE WHEN games = 0 OR games IS NULL THEN 1 ELSE 0 END')
            ->orderByRaw('CAST(points AS UNSIGNED) DESC')
            ->orderByRaw('CAST(REPLACE(diff, "+", "") AS SIGNED) DESC')
            ->get();

        $wRegularRows  = $wAllStandings->where('section', 'Регулярный чемпионат')->values();
        $wHasRegular   = $wRegularRows->isNotEmpty();
        $wExtra5_8     = $wAllStandings->whereIn('section', ['Игры за 5-8 места', 'Игры за 5-6 места'])->values();
        $wExtra11_14   = $wAllStandings->whereIn('section', ['Игры за 11-14 места', 'Игры за 9-12 места', 'Игры за 9-10 места'])->values();

        $wPlayoffPairs = \App\Models\BasketballPlayoffPair::where('tag', $womenTag)->get();
        $wBracketPairs = $wPlayoffPairs->where('section', 'playoff')->sortBy('sort')->values();

        $wRounds = [
            ['title' => '1/4 ФИНАЛА', 'pairs' => $wBracketPairs->where('round', 4)->values(), 'slots' => 4],
            ['title' => '1/2 ФИНАЛА', 'pairs' => $wBracketPairs->where('round', 2)->values(), 'slots' => 2],
            ['title' => 'ФИНАЛ',      'pairs' => $wBracketPairs->where('round', 1)->sortBy('sort')->take(1)->values(), 'slots' => 1],
        ];

        $wThirdPlacePair = $wBracketPairs->where('round', 1)->sortBy('sort')->last();
        $wBsk3HasTeams = $wThirdPlacePair
            && !empty($wThirdPlacePair->team1_name) && $wThirdPlacePair->team1_name !== '?'
            && !empty($wThirdPlacePair->team2_name) && $wThirdPlacePair->team2_name !== '?';
        $wBsk3Dates = [];
        foreach (($wThirdPlacePair?->games ?? []) as $g) {
            if (($g['status'] ?? '') === 'Scheduled' && !empty($g['date'])) {
                $wBsk3Dates[] = $g['date'];
            }
        }
    }
@endphp

{{-- ════════════════════════════════════════════
     ШАПКА с переключателями
════════════════════════════════════════════ --}}
@if($hasRegular || $womenTag || $bracketPairs->isNotEmpty())
<div class="rfs-header {{ $womenTag ? 'bsk-dual-header' : 'bsk-solo-header' }}">

    @if($womenTag)
    <div class="rfs__title">Турнирная таблица</div>
    {{-- МУЖСКАЯ ЛИГА --}}
    <div class="bsk-league-group">
        <span class="bsk-league-title">Мужская лига</span>
        <div class="rfs-header__buttons">
            <button class="button {{ $hasRegular ? 'is-active' : '' }}" type="button" id="bsk-btn-standings">
                <div class="button__text">Чемпионат</div>
            </button>
            <button class="button {{ !$hasRegular ? 'is-active' : '' }}" type="button" id="bsk-btn-playoff">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>

    {{-- Разделитель --}}
    <div class="bsk-league-divider">
        <svg xmlns="http://www.w3.org/2000/svg" width="1" height="45" viewBox="0 0 1 45" fill="none">
            <path d="M0.5 0.5L0.499998 44.5" stroke="black" stroke-opacity="0.18" stroke-linecap="round"/>
        </svg>
    </div>

    {{-- ЖЕНСКАЯ ЛИГА --}}
    <div class="bsk-league-group">
        <span class="bsk-league-title">Женская лига</span>
        <div class="rfs-header__buttons">
            <button class="button" type="button" id="bsk-btn-w-standings">
                <div class="button__text">Чемпионат</div>
            </button>
            <button class="button" type="button" id="bsk-btn-w-playoff">
                <div class="button__text">Плей-офф</div>
            </button>
        </div>
    </div>

    @else
    {{-- Стандартная шапка (без женской лиги) --}}
    <div class="rfs__title">Турнирная таблица</div>
    <div class="rfs-header__buttons">
        <button class="button {{ $hasRegular ? 'is-active' : '' }}" type="button" id="bsk-btn-standings">
            <div class="button__text">Чемпионат</div>
        </button>
        <button class="button {{ !$hasRegular ? 'is-active' : '' }}" type="button" id="bsk-btn-playoff">
            <div class="button__text">Плей-офф</div>
        </button>
    </div>
    @endif

</div>
@endif

{{-- ═══ Вкладка: МУЖСКАЯ ЧЕМПИОНАТ ══════════ --}}
@if($hasRegular)
<div id="bsk-tab-standings">
    @if($regularRows->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @include('partials.bsk-standings-table', ['rows' => $regularRows, 'title' => 'Регулярный чемпионат'])
    </section>
    @endif
</div>
@endif

{{-- ═══ Вкладка: МУЖСКОЙ ПЛЕЙ-ОФФ ═══════════ --}}
<div id="bsk-tab-playoff" @if($hasRegular) style="display:none;" @endif>

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
                        @php
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
                            $hasScore = !empty($scores);
                            $lines = $hasScore ? $scores : $dates;
                        @endphp
                        <x-bracket-match
                            :team1="$pair->team1_name ?? '?'"
                            :team2="$pair->team2_name ?? '?'"
                            :logo1="$pair->team1_logo"
                            :logo2="$pair->team2_logo"
                            :is-score="$hasScore"
                            :lines="$lines"
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

    {{-- Карточка: Финал за 3 место --}}
    @php
        $thirdPlacePair = $bracketPairs->where('round', 1)->sortBy('sort')->last();
        $bsk3HasTeams = $thirdPlacePair
            && !empty($thirdPlacePair->team1_name) && $thirdPlacePair->team1_name !== '?'
            && !empty($thirdPlacePair->team2_name) && $thirdPlacePair->team2_name !== '?';
        $bsk3Dates = [];
        foreach (($thirdPlacePair?->games ?? []) as $g) {
            if (($g['status'] ?? '') === 'Scheduled' && !empty($g['date'])) {
                $bsk3Dates[] = $g['date'];
            }
        }
    @endphp
    <div class="bsk-pcards">
        <div class="bsk-pcard {{ !$bsk3HasTeams ? 'bsk-pcard--empty' : '' }}">
            <div class="bsk-pcard__title">ФИНАЛ ЗА 3 МЕСТО</div>
            <div class="bsk-pcard__body">
                @if(!$bsk3HasTeams)
                    <div class="bsk-pcard__team">
                        <div class="bsk-pcard__logo-placeholder bsk-pcard__logo-placeholder--unknown">?</div>
                        <span class="bsk-pcard__team-name bsk-pcard__team-name--unknown">ПРОИГР. 1/2 ФИНАЛА</span>
                    </div>
                @else
                    <div class="bsk-pcard__team">
                        @if($thirdPlacePair->team1_logo)
                            <img class="bsk-pcard__logo" src="{{ $thirdPlacePair->team1_logo }}" alt="{{ $thirdPlacePair->team1_name }}">
                        @else
                            <div class="bsk-pcard__logo-placeholder">{{ mb_strtoupper(mb_substr($thirdPlacePair->team1_name ?? '?', 0, 2)) }}</div>
                        @endif
                        <span class="bsk-pcard__team-name">{{ $thirdPlacePair->team1_name }}</span>
                    </div>
                @endif
                <div class="bsk-pcard__center">
                    @foreach($bsk3Dates as $bsk3D)
                        <span class="bsk-pcard__date">{{ $bsk3D }}</span>
                    @endforeach
                </div>
                @if(!$bsk3HasTeams)
                    <div class="bsk-pcard__team">
                        <div class="bsk-pcard__logo-placeholder bsk-pcard__logo-placeholder--unknown">?</div>
                        <span class="bsk-pcard__team-name bsk-pcard__team-name--unknown">ПРОИГР. 1/2 ФИНАЛА</span>
                    </div>
                @else
                    <div class="bsk-pcard__team">
                        @if($thirdPlacePair->team2_logo)
                            <img class="bsk-pcard__logo" src="{{ $thirdPlacePair->team2_logo }}" alt="{{ $thirdPlacePair->team2_name }}">
                        @else
                            <div class="bsk-pcard__logo-placeholder">{{ mb_strtoupper(mb_substr($thirdPlacePair->team2_name ?? '?', 0, 2)) }}</div>
                        @endif
                        <span class="bsk-pcard__team-name">{{ $thirdPlacePair->team2_name }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Игры за места --}}
    @foreach([['rows' => $extra5_8, 'title' => $extra5_8->first()?->section ?? 'Игры за 5-8 места'], ['rows' => $extra11_14, 'title' => $extra11_14->first()?->section ?? 'Игры за 11-14 места']] as $extra)
    @if($extra['rows']->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @include('partials.bsk-standings-table', ['rows' => $extra['rows'], 'title' => $extra['title']])
    </section>
    @endif
    @endforeach
</div>

{{-- ═══ ЖЕНСКАЯ ЛИГА (только для Суперлиги) ══ --}}
@if($womenTag)

{{-- Вкладка: ЖЕНСКИЙ ЧЕМПИОНАТ --}}
<div id="bsk-tab-w-standings" style="display:none;">
    @if($wHasRegular && $wRegularRows->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @include('partials.bsk-standings-table', ['rows' => $wRegularRows, 'title' => 'Регулярный чемпионат'])
    </section>
    @else
    <div style="padding: 20px;">Нет данных. Запустите парсер.</div>
    @endif
</div>

{{-- Вкладка: ЖЕНСКИЙ ПЛЕЙ-ОФФ --}}
<div id="bsk-tab-w-playoff" style="display:none;">
    <section class="rfs rfs-playoff bsk-playoff">
        <div class="rfs-bracket bsk-bracket--playoff">
            @foreach($wRounds as $round)
            <div class="rfs-bracket__round">
                <div class="rfs-bracket__round-title">{{ $round['title'] }}</div>
                <div class="rfs-bracket__matches">
                    @for($i = 0; $i < $round['slots']; $i++)
                    @php $pair = $round['pairs']->get($i); @endphp
                    @if($pair)
                        @php
                            $wScores = [];
                            $wDates  = [];
                            if ($pair->score1 !== null && $pair->score2 !== null && ($pair->score1 > 0 || $pair->score2 > 0)) {
                                $wScores[] = $pair->score1 . ':' . $pair->score2;
                            }
                            foreach ($pair->games ?? [] as $game) {
                                if (($game['status'] ?? '') === 'Scheduled' && !empty($game['date'])) {
                                    $wDates[] = $game['date'];
                                }
                            }
                            $wHasScore = !empty($wScores);
                            $wLines = $wHasScore ? $wScores : $wDates;
                        @endphp
                        <x-bracket-match
                            :team1="$pair->team1_name ?? '?'"
                            :team2="$pair->team2_name ?? '?'"
                            :logo1="$pair->team1_logo"
                            :logo2="$pair->team2_logo"
                            :is-score="$wHasScore"
                            :lines="$wLines"
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

    @if($wBsk3HasTeams)
    <div class="bsk-pcards">
        <div class="bsk-pcard">
            <div class="bsk-pcard__title">ФИНАЛ ЗА 3 МЕСТО</div>
            <div class="bsk-pcard__body">
                <div class="bsk-pcard__team">
                    @if($wThirdPlacePair->team1_logo)
                        <img class="bsk-pcard__logo" src="{{ $wThirdPlacePair->team1_logo }}" alt="{{ $wThirdPlacePair->team1_name }}">
                    @else
                        <div class="bsk-pcard__logo-placeholder">{{ mb_strtoupper(mb_substr($wThirdPlacePair->team1_name ?? '?', 0, 2)) }}</div>
                    @endif
                    <span class="bsk-pcard__team-name">{{ $wThirdPlacePair->team1_name }}</span>
                </div>
                <div class="bsk-pcard__center">
                    @foreach($wBsk3Dates as $wD)
                        <span class="bsk-pcard__date">{{ $wD }}</span>
                    @endforeach
                </div>
                <div class="bsk-pcard__team">
                    @if($wThirdPlacePair->team2_logo)
                        <img class="bsk-pcard__logo" src="{{ $wThirdPlacePair->team2_logo }}" alt="{{ $wThirdPlacePair->team2_name }}">
                    @else
                        <div class="bsk-pcard__logo-placeholder">{{ mb_strtoupper(mb_substr($wThirdPlacePair->team2_name ?? '?', 0, 2)) }}</div>
                    @endif
                    <span class="bsk-pcard__team-name">{{ $wThirdPlacePair->team2_name }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    @foreach([['rows' => $wExtra5_8, 'title' => $wExtra5_8->first()?->section ?? 'Игры за 5-8 места'], ['rows' => $wExtra11_14, 'title' => $wExtra11_14->first()?->section ?? 'Игры за 11-14 места']] as $extra)
    @if($extra['rows']->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @include('partials.bsk-standings-table', ['rows' => $extra['rows'], 'title' => $extra['title']])
    </section>
    @endif
    @endforeach
</div>

@endif {{-- /womenTag --}}

@php
    $basketUpcoming = \App\Models\UpcomingMatch::where('sport', 'basketball')
        ->where(function ($q) use ($allowedTags) {
            foreach ($allowedTags as $t) {
                $q->orWhere('league_name', 'LIKE', '%(' . strtoupper($t) . ')%');
            }
        })
        ->where('match_at', '>=', now())
        ->orderBy('match_at')
        ->get();
@endphp
@include('components.upcoming-matches', ['sport' => 'basketball', 'matches' => $basketUpcoming, 'eventsUrl' => $page->getPayloadValue('events_url')])

@include('partials.tab-switcher')
<script>
@if($womenTag)
// Dual-header switcher: единая группа из 4 вкладок
(function () {
    var all = [
        { key: 'standings',  tab: document.getElementById('bsk-tab-standings'),   btn: document.getElementById('bsk-btn-standings') },
        { key: 'playoff',    tab: document.getElementById('bsk-tab-playoff'),      btn: document.getElementById('bsk-btn-playoff') },
        { key: 'wStandings', tab: document.getElementById('bsk-tab-w-standings'), btn: document.getElementById('bsk-btn-w-standings') },
        { key: 'wPlayoff',   tab: document.getElementById('bsk-tab-w-playoff'),   btn: document.getElementById('bsk-btn-w-playoff') },
    ];

    function showTab(activeKey) {
        all.forEach(function (item) {
            if (!item.tab) return;
            var isActive = item.key === activeKey;
            item.tab.style.display = isActive ? '' : 'none';
            if (item.btn) item.btn.classList.toggle('is-active', isActive);
        });
    }

    @if($hasRegular)
    showTab('standings');
    @else
    showTab('playoff');
    @endif

    all.forEach(function (item) {
        if (item.btn) item.btn.addEventListener('click', function () { showTab(item.key); });
    });
})();
@else
{{-- Стандартный switcher --}}
@if($hasRegular)
initTabSwitcher(
    { standings: 'bsk-tab-standings', playoff: 'bsk-tab-playoff' },
    { standings: ['bsk-btn-standings'], playoff: ['bsk-btn-playoff'] }
);
@endif
@endif
</script>
