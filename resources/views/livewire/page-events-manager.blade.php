@php
  /** @var \App\Models\Page $page */
  /** @var \App\Models\PageEvent $model */
@endphp
<div class="p-4">

  <div class="py-2">
    <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
  </div>

  <div class="flex flex-wrap gap-4">
    @foreach($page->events as $item)
      @include('livewire.manager.card')
    @endforeach
  </div>

  @if($showModal)
    <x-popup @mousedown="$wire.closeModal()" :size="'lg'" :title="$model ? 'Изменить - ' . $page->title : 'Добавить'">
      <div class="p-4" x-data="{ type: '{{$formData['type']}}' }">
        @php
          $types = [];
        @endphp
        @include('livewire.manager.header')

        <div class="mb-4">
          <label><p>URL</p>
            <input type="text" wire:model="formData.url" class="w-full rounded mt-1 border-gray-300">
          </label>
          @error('formData.url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4 flex flex-wrap gap-2">
          <div>
            <label><p>Команда 1</p>
              <input type="text" wire:model="formData.team1" class="w-full rounded mt-1 border-gray-300">
            </label>
            @error('formData.team1') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label><p>Команда 2</p>
              <input type="text" wire:model="formData.team2" class="w-full rounded mt-1 border-gray-300">
            </label>
            @error('formData.team2') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label><p>LIVE</p>
              <input type="checkbox" wire:model="formData.payload.live" class="rounded mt-1 border-gray-300">
            </label>
            @error('formData.payload.live') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>
        </div>

        <div class="mb-4 flex flex-wrap gap-2">
          <div>
            <label><p>Дата начала</p>
              <input type="datetime-local" step="1" wire:model="formData.date_start" class="w-full rounded mt-1 border-gray-300">
            </label>
            @error('formData.date_start') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>

          <div>
            <label><p>Дата окончания</p>
              <input type="datetime-local" step="1" wire:model="formData.date_end" class="w-full rounded mt-1 border-gray-300">
            </label>
            @error('formData.date_end') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
          </div>
        </div>

      </div>

      @include('livewire.manager.footer')

    </x-popup>
  @endif
</div>
