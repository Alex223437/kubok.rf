@props([
    'name',
    'logo' => null,
    'wrapperClass' => 'rfs-club',
    'imgClass' => null,
    'nameClass' => null,
])
<div class="{{ $wrapperClass }}">
    @if($logo)
        <img src="{{ $logo }}" alt="{{ $name }}" loading="lazy" @class([$imgClass])>
    @endif
    <span @class([$nameClass])>{{ $name }}</span>
</div>
