<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">Настройки</h2>
</x-slot>

<div class="py-12">
  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <div class="flex space-x-4 px-4 py-4 justify-between">
        <button class="rounded-md border border-gray-300 px-2" disabled @click="$wire.create()">+</button>

        <div class="flex items-center">
          <div wire:loading wire:target="clearCache" class="inline-block mr-2">
            <div class="w-6 h-6 border-4 border-gray-300 border-t-gray-700 rounded-full animate-spin"></div>
          </div>
          <button wire:click="clearCache" wire:loading.attr="disabled"
                  class="rounded-md border border-gray-300 px-4 py-2 text-sm flex items-center space-x-2">
            <span>Очистить кэш</span>
          </button>
        </div>

      </div>

      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase text-left">
        <tr>
          <th scope="col" class="px-2 py-3"></th>
          <th scope="col" class="px-2 py-3"></th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($data as $item)
          <tr wire:key="{{ $item->id }}">
            <td class="p-2 w-1/2 text-right">
              <p class="font-semibold">
                <span>{{ $item->title }}:</span>
              </p>
              <code class="text-xs text-gray-400">{{ $item->code }}</code>
            </td>
            <td class="p-2 w-1/2 whitespace-no-wrap">
              <p>
                @if($item->type === \App\Models\Option::TYPE_BOOLEAN)
                  <input type="checkbox" class="rounded" disabled readonly {{$item->enabled ? 'checked' : ''}}>
                @else
                  <span class="text-gray-800 text-sm">{{ Str::limit(strip_tags($item->value, 80)) }}</span>
                  <span
                    class="ml-4 inline-block w-3 h-3 rounded-full {{ $item->active ? 'bg-green-500' : 'bg-gray-200' }}"></span>
                @endif
              </p>
              <button class="text-xs underline cursor-pointer" wire:click="edit({{ $item->id }})">Изменить</button>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    </div>
  </div>

  @if($isModalOpen)
    <x-popup @mousedown="$wire.closeModal()" :size="'md'" :title="$optionId ? 'Изменить' : 'Добавить'">

      <div class="p-4">

        <div class="mt-2 flex flex-wrap gap-4">
          <label><p class="mb-1 text-sm font-medium text-gray-600">Код</p>
            <input {{$optionId ? 'disabled' : ''}} class="rounded w-full font-mono" type="text" id="code"
                   wire:model="code">
          </label>
          @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

          @if($type === \App\Models\Option::TYPE_BOOLEAN)
            <div class="mt-2">
              <label><p class="mb-1 text-sm font-medium text-gray-600">Включено</p>
                <input type="checkbox" class="rounded" wire:model="enabled">
              </label>
              @error('enabled') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
          @else
            <div class="mt-2">
              <label class="text-gray-800"><p class="text-sm font-medium">Активно</p>
                <input type="checkbox" class="rounded" wire:model="active">
              </label>
              @error('active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
          @endif

        </div>

        <div class="mt-2">
          <label class="text-gray-800"><p class="mb-1 text-sm font-medium">Описание</p>
            <input type="text" class="rounded w-full text-xs" wire:model="title">
          </label>
          @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        @if(empty($type))
          <div class="mt-2">
            <label class="text-gray-800"><p class="mb-1 text-sm font-medium">Значение</p>
              <textarea class="rounded w-full text-xs" rows="8" wire:model="value"></textarea>
            </label>
            @error('value') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
          </div>
        @endif

      </div>

      <div class="p-4 mt-4 border-t flex justify-between">
        <div>
          @if($optionId)
            <x-btn-secondary disabled wire:click="delete({{ $optionId }})"
                             wire:confirm="Действие не обратимо. Вы уверены?"
                             class="text-red-600 hover:bg-red-600 hover:text-white">
              Удалить
            </x-btn-secondary>
          @endif
        </div>
        <div>
          <x-btn-secondary class="bg-white" wire:click.prevent="closeModal">Отмена</x-btn-secondary>
          <x-btn-primary class="ml-1 bg-white" wire:click.prevent="store">Сохранить</x-btn-primary>
        </div>
      </div>


    </x-popup>
  @endif
</div>
