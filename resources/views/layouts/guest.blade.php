<!DOCTYPE html>
<html lang="ru">

<head>
  @include('layouts._head')
</head>

<body>
@auth
  <style>
    .admin {
      position: fixed;
      top: 10px;
      right: 10px;
      z-index: 1000;
      background: #fff;
      border-radius: 8px;
      transition: all 0.2s ease;
      bottom: unset;
      left: unset;
      box-shadow: 5px 5px 5px rgba(0, 0, 0, .6);
      border: 2px solid black;
    }

    .admin__wrapper {
      height: 50px;
      width: 50px;
    }

    .admin:hover {
      background: #a45e5e;
      transform: translateY(2px);
    }
  </style>
<a class="admin" href="/admin" target="_blank">
  <span class="admin__wrapper">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
    <path stroke-linecap="round" stroke-linejoin="round" d="m6.75 7.5 3 2.25-3 2.25m4.5 0h3m-9 8.25h13.5A2.25 2.25 0 0 0 21 18V6a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 6v12a2.25 2.25 0 0 0 2.25 2.25Z"></path></svg>
  </span>
</a>
@endauth
  {{ $slot }}

</body>
</html>
