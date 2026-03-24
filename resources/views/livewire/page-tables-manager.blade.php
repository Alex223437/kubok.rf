<?php

/** @var \App\Models\Page $page */
$types = \App\Models\PageTable::TYPES;
?>
<div class="p-4">

  <div class="py-2">
    <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
  </div>

  <div class="flex flex-wrap gap-4">
    @foreach($page->tables as $item)
      @include('livewire.manager.card')
    @endforeach
  </div>

  @if($showModal)
    <x-popup @mousedown="$wire.closeModal()" :size="'lg'" :title="$model ? 'Изменить - ' . $page->title : 'Добавить'">
      <div class="p-4" x-data="{ type: '{{$formData['type']}}' }">

        @include('livewire.manager.header')

        <div class="mb-4">
          <label><p>Короткое название</p>
            <input type="text" wire:model="formData.short" class="w-full rounded mt-1 border-gray-300">
          </label>
          @error('formData.short') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="space-y-2" x-data="{ active: 'rows' }">

          <details class="mt-2 border rounded shadow-md" :open="active === 'headers'">
            <summary @click.prevent="active = (active === 'headers' ? null : 'headers')"
                     class="p-3 bg-gray-100 cursor-pointer text-sm text-slate-900 font-semibold">
              Заголовки
            </summary>
            <div class="p-4">
              <div class="space-y-1">
                @foreach($formData['payload']['headers'] ?? [] as $index => $header)
                  <div class="flex gap-2 items-center">
                    <div class="text-xs text-gray-500">{{$index + 1}}</div>
                    <input type="text" class="jt-input" wire:model="formData.payload.headers.{{$index}}.title"
                           placeholder="title">
                    <input type="text" class="jt-input w-[250px]" wire:model="formData.payload.headers.{{$index}}.hint"
                           placeholder="hint">
                    <button class="text-red-500 text-xl" wire:click="removeHeader({{$index}})">×</button>
                  </div>
                @endforeach
              </div>
              <button class="mt-2 text-blue-500" wire:click="addHeader">+ Добавить</button>
            </div>
          </details>

          <details class="mt-2 border rounded shadow-md" :open="active === 'rows'">
            <summary @click.prevent="active = (active === 'rows' ? null : 'rows')"
                     class="p-3 bg-gray-100  cursor-pointer text-sm text-slate-900 font-semibold">
              Записи таблиц
            </summary>
            <div class="p-4">
              <div class="space-y-1" x-data="{
dragStart(e) {e.dataTransfer.setData('index', e.target.dataset.index)},
toggleDropZone(e, show) {
const row = e.target.closest('[draggable]');
row.classList.toggle('border-transparent', !show);
row.classList.toggle('border-blue-500', show)}}">
                <div class="flex gap-2 items-center bg-white">
                  <div class="text-xs text-gray-500 opacity-0">0</div>
                  <div class="text-gray-400 text-xl text-mono opacity-0">0 ⋮⋮</div>
                  @foreach($formData['payload']['headers'] ?? [] as $index => $header)
                    <div class="jt-input">{{$header['title']}}</div>
                  @endforeach
                </div>
                @foreach($formData['payload']['values'] ?? [] as $rowIndex => $row)
                  <div
                    class="flex gap-2 items-center bg-white transition-all duration-200 border-l-2 border-transparent"
                    draggable="true"
                    data-index="{{$rowIndex}}"
                    wire:key="row-{{$rowIndex}}"
                    @dragstart="dragStart($event)"

                    @dragover.prevent="toggleDropZone($event, true)"
                    @dragleave="toggleDropZone($event, false)"
                    @drop.prevent="
                    toggleDropZone($event, false);
                    $wire.reorderRows($event.dataTransfer.getData('index'), $event.target.closest('[draggable]').dataset.index)
                "
                  >
                    <div class="text-xs text-gray-500">{{$rowIndex + 1}}</div>
                    <div class="text-gray-400 cursor-move text-xl">⋮⋮</div>
                    @foreach($formData['payload']['headers'] as $colIndex => $value)
                      <input type="text" class="jt-input"
                             wire:model.defer="formData.payload.values.{{$rowIndex}}.{{$colIndex}}"
                             placeholder="">
                    @endforeach
                    <button class="text-red-500 text-xl" wire:click="removeRow({{$rowIndex}})">×</button>
                  </div>
                @endforeach
              </div>
              <button class="mt-2 text-blue-500" wire:click="addRow">+ Добавить</button>
            </div>
          </details>

        </div>
      </div>

      @include('livewire.manager.footer')

    </x-popup>
  @endif
</div>
