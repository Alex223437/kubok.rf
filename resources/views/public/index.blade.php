@php
  /** @var \App\Models\Page[] $pages */
@endphp
<x-guest-layout>

  <div class="page page--home">
    <main class="home" role="main">
      <div class="home__list" style="--count:{{$pages->count()}}">
        @foreach($pages as $key => $page)
          @php
            $itemClass =  'home__item--' . $page->getCssList() . ' ' . ($key === 0 ? 'is-active' : '');
            if ( $page->type === 'banner') {
              $itemClass .= ' home__item--banner';
            }
          @endphp
          <div class="home__item {{$itemClass}}" style="--index:{{$key+1}}">
            <div class="home__item-content">
              <div class="home__item-name">{!! $page->title !!}</div>
              <div class="home__item-arrow">
                <svg><use xlink:href="/spritemap.svg#icon-arrow-right-bottom"></use></svg>
              </div>

              @if($page->type === 'promo')
                <h1 class="home__item-title">{!! $page->title !!}</h1>
              @else
                <div class="home__item-logo">
                  @if($page->img)
                    <img src="{{$page->img->path}}" alt="">
                  @endif
                </div>
              @endif

              <div class="home__item-text">{!! $page->description !!}</div>

              @if($page->type !== 'promo')
                <a class="home__item-link" href="/page/{{$page->code}}">Перейти на&nbsp;страницу чемпионата</a>
              @endif

              <div class="home__item-background">{!! $page->html !!}</div>
            </div>

            @if($page->type === 'promo' && $page->banner)
              <div class="{{$page->type === 'promo' ? 'for-desktop' : ''}}">
                @if($page->banner->url)
                  <a href="{{$page->banner->url}}" target="_blank">{!! $page->banner->html_md !!}</a>
                @else
                  {!! $page->banner->html_md !!}
                @endif
              </div>
            @endif

            @if($page->type === 'banner' && $page->banner)
              <div class="{{$page->type === 'banner' ? 'for-devices' : ''}}">
                @if($page->banner->url)
                  <a href="{{$page->banner->url}}" target="_blank">{!! $page->banner->html_md !!}</a>
                @else
                  {!! $page->banner->html_md !!}
                @endif
              </div>
            @endif

          </div>
        @endforeach
      </div>
    </main>
  </div>

</x-guest-layout>
