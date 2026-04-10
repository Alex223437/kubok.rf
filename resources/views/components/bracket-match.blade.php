@props([
    'team1' => null,
    'team2' => null,
    'logo1' => null,
    'logo2' => null,
    'isScore' => false,
    'lines' => [],
    'penalty' => null,
    'empty' => false,
])

@if($empty)
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
@else
<div class="rfs-bracket__match {{ !$isScore ? 'rfs-bracket__match--upcoming' : '' }}">
    <div class="rfs-bracket__team">
        @if($logo1)
            <img class="rfs-bracket__logo" src="{{ $logo1 }}" alt="{{ $team1 }}">
        @else
            <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($team1 ?? '?', 0, 2)) }}</div>
        @endif
        <span class="rfs-bracket__team-name">{{ $team1 }}</span>
    </div>
    <div class="rfs-bracket__scores">
        @foreach($lines as $line)
            <span class="{{ $isScore ? 'rfs-bracket__score' : 'rfs-bracket__date' }}">{{ $line }}</span>
        @endforeach
        @if($penalty)
            <span class="rfs-bracket__penalty">{{ $penalty }}</span>
        @endif
    </div>
    <div class="rfs-bracket__team">
        @if($logo2)
            <img class="rfs-bracket__logo" src="{{ $logo2 }}" alt="{{ $team2 }}">
        @else
            <div class="rfs-bracket__logo-placeholder">{{ mb_strtoupper(mb_substr($team2 ?? '?', 0, 2)) }}</div>
        @endif
        <span class="rfs-bracket__team-name">{{ $team2 }}</span>
    </div>
</div>
@endif
