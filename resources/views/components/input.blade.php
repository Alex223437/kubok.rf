@props(['type' => 'text', 'label' => '', 'hint' => ''])
@php
  $class = [
      'text' => 'rounded mt-1 w-full border-gray-300',
      'number' => 'rounded mt-1 w-full border-gray-300',
      'checkbox' => 'rounded-md mt-1',
  ][$type];
  $field = $attributes->has('name') ? $attributes->get('name') : $attributes->get('wire:model');
@endphp
<label class="text-sm">
  @if($label)
    <span class="font-medium text-gray-700">{{ $label }}</span>
  @endif
  @if($hint)
    <span class="text-gray-400">{{ $hint }}</span>
  @endif
  <input
    type="{{ $type }}"
    class="{{ $class }}"
    {{ $attributes->except('class') }}
  />
  @error($field) <span class="text-xs text-red-600">{{$message}}</span> @enderror
</label>
