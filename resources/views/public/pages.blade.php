@php
    /** @var \App\Models\Page $items */
@endphp
<x-guest-layout>
  @foreach($items as $item)
    <p>{{$item->title}}</p>
  @endforeach
</x-guest-layout>
