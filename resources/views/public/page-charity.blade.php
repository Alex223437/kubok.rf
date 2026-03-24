@if($item->type === 'img')
  <div class="swiper-slide">
    <div class="charity__banner">
      @if($item->url)
        <a href="{{$item->url}}" rel="nofollow" target="_blank"> @endif
          <div class="charity__banner-image">
            <img src="{{$item->img?->path}}" alt="{{$item->title}}">
          </div>
          @if($item->url)
        </a>
      @endif
    </div>
  </div>
@else
  <div class="swiper-slide">
    <a class="charity-card" href="{{$item->url}}" target="_blank">
      <span class="charity-card__image"><img src="{{$item->img?->path}}" alt=""></span>
      <span class="charity-card__title">{!! $item->title !!}</span>
      <span class="charity-card__text">{!! $item->text !!}</span>
      <span class="charity-card__arrow"><svg><use
          xlink:href="/spritemap.svg#icon-arrow-right-bottom"></use></svg></span>
    </a>
  </div>
@endif
