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

    {{-- Карточка: Финал за 3 место --}}
    @php
        // Пары round=1 отсортированы по sort: первая — финал, последняя — матч за 3 место
        $thirdPlacePair = $bracketPairs->where('round', 1)->sortBy('sort')->last();
        $bsk3HasTeams = $thirdPlacePair
            && !empty($thirdPlacePair->team1_name) && $thirdPlacePair->team1_name !== '?'
            && !empty($thirdPlacePair->team2_name) && $thirdPlacePair->team2_name !== '?';
        $bsk3NextGame = null;
        foreach (($thirdPlacePair?->games ?? []) as $g) {
            if (($g['status'] ?? '') === 'Scheduled' && !empty($g['date'])) {
                $bsk3NextGame = $g; break;
            }
        }
        $bsk3Date = '';
        $bsk3Time = '';
        if ($bsk3NextGame) {
            try {
                $bsk3Dt   = \Carbon\Carbon::parse($bsk3NextGame['date']);
                $bsk3Date = $bsk3Dt->format('d/m');
                $bsk3Time = $bsk3Dt->format('H:i') !== '00:00' ? $bsk3Dt->format('H:i') . ' мск' : '';
            } catch (\Throwable $e) {
                $bsk3Date = $bsk3NextGame['date'];
            }
        }
    @endphp
    <div class="bsk-pcards">
        <div class="bsk-pcard {{ !$bsk3HasTeams ? 'bsk-pcard--empty' : '' }}">
            <div class="bsk-pcard__title">ФИНАЛ ЗА 3 МЕСТО</div>
            <div class="bsk-pcard__body">
                {{-- Команда 1 --}}
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
                {{-- Центр: дата или пусто --}}
                <div class="bsk-pcard__center">
                    @if($bsk3HasTeams && $bsk3Date)
                        <span class="bsk-pcard__date">{{ $bsk3Date }}</span>
                        @if($bsk3Time)<span class="bsk-pcard__time">{{ $bsk3Time }}</span>@endif
                    @endif
                </div>
                {{-- Команда 2 --}}
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

@include('components.upcoming-matches', ['sport' => 'basketball', 'eventsUrl' => $page->getPayloadValue('events_url')])

@include('partials.tab-switcher')
@if($hasRegular)
<script>
initTabSwitcher(
    { standings: 'bsk-tab-standings', playoff: 'bsk-tab-playoff' },
    { standings: ['bsk-btn-standings', 'bsk-btn-standings2'], playoff: ['bsk-btn-playoff', 'bsk-btn-playoff2'] }
);
</script>
@endif
