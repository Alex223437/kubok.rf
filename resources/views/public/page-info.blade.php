@if($item->type === 'html')
  {!! $item->html  !!}
@elseif($item->type === 'img')
  <div class="tourney__col">
    <div class="tourney__picture"><img src="{{$item->img?->path}}" alt="{{$item->title}}"></div>
  </div>
@elseif($item->type === '2text')
  <div class="tourney__col tourney__col--two-text">
    <div class="tourney__item">
      <div class="tourney__text">{!! $item->text !!}</div>
    </div>
    <div class="tourney__item">
      <div class="tourney__text">{!! $item->html !!}</div>
    </div>
  </div>
@elseif($item->type === 'banner')
  <div class="tourney__col tourney__col--two-text">
    @if($item->html)
      <div class="tourney__blocks">
        {!! $item->html !!}
      </div>
    @endif
    @if($item->banner)
      <a class="tourney__banner" href="{{$item->banner->url}}">
        {!! $item->banner->html_xs !!}
      </a>
    @endif
    @if($item->text)
      <div class="tourney__item">
        <div class="tourney__text">{!! $item->text !!}</div>
      </div>
    @endif
  </div>
@else
  <div class="tourney__col">
    <div class="tourney__item @if($i === 0) tourney__item--first @endif">
      @if($i === 0)
        <div class="article__title">{{$item->title}}</div>
      @endif
      <div class="tourney__text">{!! $item->text !!}</div>
    </div>
  </div>
@endif
