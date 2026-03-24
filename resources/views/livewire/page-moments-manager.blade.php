@php
  /** @var \App\Models\Page $page */
  /** @var \App\Models\PageMoment $model */
@endphp
<div class="p-4">

  <div class="py-2">
    <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
  </div>

  <div class="flex flex-wrap gap-4">
    @foreach($page->moments as $item)
      @include('livewire.manager.card')
    @endforeach
  </div>

  @if($showModal)
    <x-popup @mousedown="$wire.closeModal()" :size="'lg'" :title="$model ? 'Изменить - ' . $page->title : 'Добавить'">
      <div class="p-4" x-data="{ type: '{{$formData['type']}}' }">
        @php
          $types = ['' => 'img', 'html' => 'html'];
        @endphp
        @include('livewire.manager.header')

        <div class="mb-4" x-show="type === 'html'">
          <label><p>HTML</p>
            <textarea wire:model="formData.html" rows="8"
                      class="text-xs w-full rounded mt-1 border-gray-300"></textarea>
          </label>
          @error('formData.html') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        @if($model)
          <div class="col-span-6 sm:col-span-3" x-show="type === ''">
            <h3>Изображение:</h3>
            <livewire:image-uploader :model="$model" field="img_id" wire:key="moments_img_id"/>
          </div>
        @endif

      </div>

      @include('livewire.manager.footer')

    </x-popup>
  @endif
</div>
