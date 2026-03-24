@props(['size', 'title' => ''])
@php
  $sizeClass = match ($size) {
    'xs' => 'rounded-lg max-w-xs',
    'sm' => 'rounded-lg max-w-md',
    'md' => 'sm:rounded-lg sm:max-w-lg',
    'lg' => 'md:rounded-lg md:max-w-3xl',
    'xl' => 'lg:rounded-lg lg:max-w-6xl',
    default => '',
  };
@endphp
<div {{ $attributes->merge(['class' => 'fixed z-99 inset-0 bg-black/50 flex items-center justify-center animate-fade-in']) }}>
  <div class="bg-white overflow-hidden {{$sizeClass}} w-full" role="dialog" aria-modal="true" @mousedown.stop>
    <h3 class="border-b p-4 text-lg font-semibold">{{$title}}</h3>
    {{ $slot }}
  </div>
</div>
