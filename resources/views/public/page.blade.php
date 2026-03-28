@php
  /** @var \App\Models\Page $page */
@endphp
<x-guest-layout>
  <div class="page page--article">
    <main class="article" role="main">
      <div class="container">

        <a class="back js-btn-backHome" href="/">
          <span class="back__wrapper">
            <span class="back__icon"><svg>
                <use xlink:href="/spritemap.svg#icon-arrow-back"></use>
              </svg></span>
          </span>
        </a>

        <section>

          <div class="head">
            <div class="head__inner">
              <h1 class="head__title">{!! $page->title !!}</h1>
              <div class="head__logo">
                @if($page->img)
                  <img src="{{$page->img->path}}" alt="">
                @endif
              </div>
            </div>
            <div class="head__background">
              @if($page->picture)
                <img src="{{$page->picture->path}}" alt="">
              @endif
            </div>
          </div>

          <ul class="breadcrumbs for-desktop">
            <li><a href="/">Главная</a></li>
            <li>/</li>
            <li>{!! $page->title !!}</li>
          </ul>

          @if($page->banner)
            <div class="banner">
              @if($page->banner->url)
                <a href="{{$page->banner->url}}" target="_blank">{!! $page->banner->html_xl !!}</a>
              @else
                {!! $page->banner->html_xl !!}
              @endif
            </div>
          @endif

        </section>

        @if($page->infosActive->isNotEmpty())
          <section class="tourney js-btn-visible">
            @foreach($page->infosActive as $i => $item)
              @include('public.page-info')
            @endforeach
          </section>
        @endif

        @if($page->facts)
          <section class="facts facts--min js-btn-visible">
            <div class="article__title color-red ml-title">ФАКТЫ</div>
            <div class="facts__wrapper">{!! $page->facts !!}</div>
          </section>
        @endif

        @if($page->charitiesActive->isNotEmpty())
          <section class="charity js-charity">
            <div class="article__title color-red ml-title">Благотворительность</div>
            <div class="charity__wrapper">
              <div class="swiper-container" data-config="1">
                <div class="swiper">
                  <div class="swiper-wrapper">
                    @foreach($page->charitiesActive as $item)
                      @include('public.page-charity')
                    @endforeach
                  </div>
                </div>
                <div class="swiper-scrollbar"></div>
              </div>
            </div>
          </section>
        @endif

        @if($page->eventsActive->isNotEmpty())
          <section class="match">
            <div class="article__title color-red ml-title">БЛИЖАЙШИЕ МАТЧИ</div>
            <div class="match__wrapper">
              <div class="swiper-container" data-config="3">
                <div class="swiper">
                  <div class="swiper-wrapper">
                    @foreach($page->eventsActive as $item)
                      @include('public.page-event')
                    @endforeach
                  </div>
                </div>
              </div>
            </div>

            @if($page->hasPayloadValue('events_url'))
              <a class="button button button--red" href="{{$page->getPayloadValue('events_url')}}" target="_blank">
                <span class="button__text">ПОКАЗАТЬ ЕЩЕ</span>
              </a>
            @endif

          </section>
        @endif

        @if($page->momentsActive->isNotEmpty())
          <section class="moments js-moments">
            <div class="moments__header">
              <div class="article__title color-red ml-title">ЛУЧШИЕ МОМЕНТЫ</div>
              <!-- <div class="moments__search for-desktop">
                        <input type="text" placeholder="Поиск" name="search" value="">
                      </div> -->
            </div>

            @include('public.page-moments')

          </section>
        @endif


        <!-- @if($page->tablesActive->isNotEmpty())

          @include('public.page-table', ['khlStyle' => $page->code === 'fonbet-kubok-rossii-po-futbolu'])

        @endif -->

        @if($page->code == 'fonbet-cempionat-kxl')
          @include('public.tables.khl')
        @elseif($page->code == 'fonbet-kubok-rossii-po-futbolu')
          @include('public.tables.rfs')
        @elseif($page->code == 'fonbet-super-liga')
          <!-- Currently we only have one super-liga code but two DB tags (msl, wsl).
                 Assuming the page is men's by default: -->
          @include('public.tables.basket', ['tag' => 'msl'])
        @elseif($page->code == 'fonbet-vyssaia-liga')
          @include('public.tables.basket', ['tag' => 'mhl'])
        @elseif($page->code == 'fonbet-premer-liga')
          @include('public.tables.basket', ['tag' => 'wpremier'])
        @endif

      </div>
    </main>
    <footer class="footer">
      <div class="container">
        <div class="footer__wrapper">
          @if($page->hasPayloadValue('social_tg'))
            <a class="footer__item" href="https://t.me/{{$page->getPayloadValue('social_tg')}}" target="_blank">
              <span class="footer__icon footer__icon--tg">
                <svg>
                  <use xlink:href="/spritemap.svg#icon-tg"></use>
                </svg></span>
              <span class="footer__text">{{ '@' . $page->getPayloadValue('social_tg')}}</span>
            </a>
          @endif

          @if($page->hasPayloadValue('social_vk'))
            <a class="footer__item" href="https://vk.com/{{$page->getPayloadValue('social_vk')}}" target="_blank">
              <span class="footer__icon footer__icon--vk">
                <svg>
                  <use xlink:href="/spritemap.svg#icon-vk"></use>
                </svg></span>
              <span class="footer__text">{{ '@' . $page->getPayloadValue('social_vk')}}</span>
            </a>
          @endif
        </div>
      </div>
    </footer>
  </div>
</x-guest-layout>