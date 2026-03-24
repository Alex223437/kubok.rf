<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

@php
  $meta_title ??= 'Кубки title';
  $meta_description ??= 'Кубки description';
  $meta_keywords ??= 'Кубки description';
  $image = '/assets/images/share/share.jpg';
@endphp

<title>{!! $meta_title !!}</title>
<meta name="description" content="{!! $meta_description !!}">
<meta name="keywords" content="{!! $meta_keywords !!}">

<meta property="og:type" content="website">
<meta property="og:locale" content="ru_RU">
<meta property="og:title" content="{!! $meta_title !!}">
<meta property="og:description" content="{!! $meta_description !!}">
<meta property="og:image" content="{{$image}}">
<meta property="og:image:type" content="image/jpeg">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{!! $meta_title !!}">
<meta name="twitter:description" content="{!! $meta_description !!}">
<meta name="twitter:image" content="{{$image}}">

<meta name="msapplication-TileColor" content="#2d89ef">
<meta name="msapplication-TileImage" content="/favicon/mstile-144x144.png">
<meta name="msapplication-config" content="/assets/browserconfig.xml">
<meta name="theme-color" content="#ffffff">

<link rel="apple-touch-icon" href="/favicon/apple-touch-icon.png" sizes="180x180">
<link rel="icon" href="/favicon/favicon.ico">
<link rel="icon" href="/favicon/favicon-16x16.png" sizes="16x16">
<link rel="icon" href="/favicon/favicon-32x32.png" sizes="32x32">
<link rel="icon" href="/favicon/android-chrome-192x192.png" sizes="192x192">
<link rel="manifest" href="/assets/site.webmanifest">
<link rel="mask-icon" href="/favicon/safari-pinned-tab.svg" color="#69b0c4">

<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- metrics -->
@include('layouts._metrics')
<!-- end metrics -->

@php
  $v = env('BUILD_VERSION', 1);
@endphp
<script defer="defer" src="/assets/vendors.js?v={{$v}}"></script>
<script defer="defer" src="/assets/main.js?v={{$v}}"></script>
<link href="/assets/vendors.css?v={{$v}}" rel="stylesheet">
<link href="/assets/main.css?v={{$v}}" rel="stylesheet">
