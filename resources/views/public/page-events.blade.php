<div class="swiper-container" data-config="3">
  <div class="swiper">
    <div class="swiper-wrapper">
      @foreach($page->events as $item)
        <div class="swiper-slide">
          <div class="match-card">
            @if($item->url)
              <a href="{{$item->url}}" rel="nofollow" target="_blank"> @endif
                <span class="match-card__dates">
                  <span class="match-card__day">17/09</span>
                  <span class="match-card__time">19:00</span>
                </span>
                <span class="match-card__teams">
                  <span class="match-card__team">{{$item->team1}}</span>
                  <span class="match-card__team">{{$item->team2}}</span>
                </span>
                <span class="match-card__live">LIVE</span>
                @if($item->url)
              </a>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
