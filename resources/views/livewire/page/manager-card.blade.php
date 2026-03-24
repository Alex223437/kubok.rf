<div class="w-[250px] p-4 bg-white rounded-lg shadow-md border-gray-200 border">
  <div class="flex items-center justify-between mb-2">
    <div class="text-sm font-semibold">{{ $item->title }}</div>
    <div class="w-3 h-3 rounded-full {{ $item->active ? 'bg-green-500' : 'bg-gray-200' }}"></div>
  </div>
  <div class="text-xs text-gray-500 mb-2">[{{$item->sort}}]</div>
  <x-btn-thin wire:click="openModal({{ $item->id }})" class="w-full">Изменить</x-btn-thin>
</div>
