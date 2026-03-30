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
@endphp

{{-- ═══ Вкладка: ЧЕМПИОНАТ ══════════════════ --}}
@if($hasRegular)
<div id="bsk-tab-standings">
    <div class="rfs-header">
        <div class="rfs__title">Турнирная таблица</div>
        <div class="rfs-header__buttons">
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
        @include('partials.bsk-standings-table', ['rows' => $regularRows, 'title' => 'Регулярный чемпионат'])
    </section>
    @endif
</div>
@endif

{{-- ═══ Вкладка: ПЛЕЙ-ОФФ ══════════════════ --}}
<div id="bsk-tab-playoff" @if($hasRegular) style="display:none;" @endif>
    <div class="rfs-header">
        <div class="rfs__title">Плей-офф</div>
        <div class="rfs-header__buttons">
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

    {{-- Игры за места --}}
    @foreach([['rows' => $extra5_8, 'title' => $extra5_8->first()?->section ?? 'Игры за 5-8 места'], ['rows' => $extra11_14, 'title' => $extra11_14->first()?->section ?? 'Игры за 11-14 места']] as $extra)
    @if($extra['rows']->isNotEmpty())
    <section class="table khl-table bsk-table-section">
        @include('partials.bsk-standings-table', ['rows' => $extra['rows'], 'title' => $extra['title']])
    </section>
    @endif
    @endforeach
</div>

@include('components.upcoming-matches', ['sport' => 'basketball'])

@include('partials.tab-switcher')
@if($hasRegular)
<script>
initTabSwitcher(
    { standings: 'bsk-tab-standings', playoff: 'bsk-tab-playoff' },
    { standings: ['bsk-btn-standings', 'bsk-btn-standings2'], playoff: ['bsk-btn-playoff', 'bsk-btn-playoff2'] }
);
</script>
@endif
