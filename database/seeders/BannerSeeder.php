<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * @see \App\Models\Banner
     * Run the database seeds.
     * php artisan db:seed --class=BannerSeeder
     */
    public function run(): void
    {
        //// WARNING ////
        // DO NOT RUN ME, REAL DATA IN DATABASE!
        /////////////////
        //\App\Models\Banner::truncate();
        //$items = $this->getData();
        //foreach ($items as $key => $item) {
        //    $item['active'] = true;
        //    \App\Models\Banner::create($item);
        //}
    }

    public function getData(): array
    {
        //@formatter:off
        return [
            [
                'title' => 'Фрибет',
                'code' => 'free_bet',
                'url' => '/banner_url',
                'html_xs' => <<<HTML
<div class="tourney__banner-decor"><img src="/assets/images/article-4/tourney/tourney-banner-bg.png" alt=""></div>
<div class="tourney__banner-text">Безусловный фрибет за&nbsp;регистрацию</div>
<div class="tourney__banner-price"><span>до</span><span>15000₽</span>
  <div class="decor"><img src="/assets/images/article-4/tourney/tourney-banner-decor.png" alt=""></div>
</div>
HTML,
                'html_md' => <<<HTML
<div class="home__banner">
<div class="home__banner-background for-desktop">
  <picture>
    <source srcset="/assets/images/home/banner.png"><img src="/assets/images/home/banner.png" alt="" decoding="async">
  </picture>
</div>
<div class="home__banner-cup for-devices">
  <picture>
    <source srcset="/assets/images/home/banner-cup.png"><img src="/assets/images/home/banner-cup.png" alt="" decoding="async">
  </picture>
</div>
<div class="home__banner-content">
  <div class="home__banner-text">Безусловный <span class="for-desktop">фрибет</span></div>
  <div class="home__banner-price for-desktop">
    <div class="decor">
      <picture>
        <source srcset="/assets/images/home/banner-decor.png"><img src="/assets/images/home/banner-decor.png" alt="" decoding="async">
      </picture>
    </div><span>до</span><span>15000₽</span>
  </div>
  <div class="home__banner-text for-devices">фрибет</div>
  <div class="home__banner-text">За&nbsp;регистрацию</div>
</div>
<div class="home__banner-price for-devices">
  <div class="decor">
    <picture>
      <source srcset="/assets/images/home/banner-decor.png"><img src="/assets/images/home/banner-decor.png" alt="" decoding="async">
    </picture>
  </div><span>до</span><span>15000₽</span>
</div>
<div class="home__banner-button">Забрать</div>
</div>
HTML,
                'html_xl' => <<<HTML
<div class="banner__background"><img class="for-desktop" src="/assets/images/banner.png" alt=""><img class="for-devices" src="/assets/images/banner-m.png" alt=""></div>
<div class="banner__inner">
  <div class="banner__text"><span>БЕЗУСЛОВНЫЙ</span> <span>ФРИБЕТ</span>  <span>ЗА РЕГИСТРАЦИЮ</span></div>
  <div class="banner__amount">
    <div class="banner__decor"><img src="/assets/images/banner-decor.png" alt=""></div>
    <div class="banner__amount-text"><span>до</span> 15000₽</div>
  </div>
  <div class="button button--white">
    <div class="button__text">ЗАБРАТЬ</div>
  </div>
</div>
HTML,
            ],
        ];
    }
}
