@php
    $matches = $matches ?? \App\Models\UpcomingMatch::when(isset($sport), fn($q) => $q->where('sport', $sport))
        ->where('match_at', '>=', now())
        ->orderBy('match_at')
        ->get();
@endphp

@if($matches->isNotEmpty())
<section class="rfs-upcoming-section">
    <div class="rfs-upcoming__header">
        <div class="rfs__title">Ближайшие матчи</div>
        <div class="rfs-upcoming__arrows">
            <button class="rfs-upcoming__arrow js-upcoming-prev" type="button">
                <span class="rfs-upcoming__arrow-wrapper">
                    <span class="rfs-upcoming__arrow-icon"><svg><use xlink:href="/spritemap.svg#icon-arrow-back"></use></svg></span>
                </span>
            </button>
            <button class="rfs-upcoming__arrow js-upcoming-next" type="button">
                <span class="rfs-upcoming__arrow-wrapper">
                    <span class="rfs-upcoming__arrow-icon rfs-upcoming__arrow-icon--next"><svg><use xlink:href="/spritemap.svg#icon-arrow-back"></use></svg></span>
                </span>
            </button>
        </div>
    </div>
    <div class="rfs-upcoming__scroll js-upcoming-scroll">
        @foreach($matches as $match)
        <div class="rfs-mcard">
            <div class="rfs-mcard__header">{!! str_replace('. ', '.<br class="rfs-mcard__br"> ', e($match->league_name)) !!}</div>
            <div class="rfs-mcard__body">
                <div class="rfs-mcard__date-row">
                    <span class="rfs-mcard__date">{{ $match->match_at->format('d/m') }}</span>
                    @if($match->match_at->format('H:i') !== '00:00')
                        <span class="rfs-mcard__time">{{ $match->match_at->format('H:i') }} мск</span>
                    @endif
                </div>
                <div class="rfs-mcard__teams">
                    <div class="rfs-mcard__team">
                        <div class="rfs-mcard__logo">
                            @if($match->team1_logo)
                                <img src="{{ $match->team1_logo }}" alt="{{ $match->team1 }}">
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
                            @if($match->team2_logo)
                                <img src="{{ $match->team2_logo }}" alt="{{ $match->team2 }}">
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
<script>
(function () {
    var section = document.currentScript.previousElementSibling;
    var scroll  = section.querySelector('.js-upcoming-scroll');
    var prev    = section.querySelector('.js-upcoming-prev');
    var next    = section.querySelector('.js-upcoming-next');
    if (!scroll) return;
    var step = function () {
        var card = scroll.querySelector('.rfs-mcard');
        return card ? card.offsetWidth + parseInt(getComputedStyle(scroll).gap) : 300;
    };
    prev.addEventListener('click', function () { scroll.scrollBy({ left: -step(), behavior: 'smooth' }); });
    next.addEventListener('click', function () { scroll.scrollBy({ left:  step(), behavior: 'smooth' }); });
})();
</script>
@endif
