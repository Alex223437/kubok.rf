@php
  /** @var \App\Models\PageEvent $item */
@endphp
<div class="swiper-slide">
  @if($item->url)
    <a class="match-card" href="{{$item->url}}" rel="nofollow" target="_blank"> @else
        <div class="match-card"> @endif
          <span class="match-card__dates">
          <span class="match-card__day">{{ $item->date_start?->format('d/m') }}</span>
          <span class="match-card__time">{{ $item->date_start?->format('H:i') }}</span>
          </span>
          <span class="match-card__teams">
          <span class="match-card__team">{{$item->team1}}</span>
          <span class="match-card__team">{{$item->team2}}</span>
          </span>
          @if($item->payload['live'] ?? false) <span class="match-card__live">LIVE</span>
      @endif
      @if($item->url) </a>
  @else </div> @endif
</div>
