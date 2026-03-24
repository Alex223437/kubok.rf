<div class="moments__wrapper">
  @if($first = $page->momentsActive->first())
    <div class="moments__video for-desktop">{!! $first->html !!}</div>
  @endif
  <div class="moments__slider">
    <div class="swiper-container" data-config="2">
      <div class="swiper">
        <div class="swiper-wrapper">
          @foreach($page->momentsActive as $key => $item)
            @if($key === 0)
              @continue
            @endif
            <div class="swiper-slide">
              <div class="moments__item">
                <img class="moments__image" src="{{$item->img?->path}}" alt="{{$item->title}}" role="presentation">
                <div class="moments__text">{{$item->text}}</div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="swiper-pagination"></div>
      </div>
    </div>
  </div>
</div>
