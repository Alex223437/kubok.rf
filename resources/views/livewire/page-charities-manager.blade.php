@php
  /** @var \App\Models\Page $page */
@endphp
<div class="p-4">
  @if(!$showModal)
    <div class="py-2">
      <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
    </div>

    <div class="flex flex-wrap gap-4">
      @foreach($page->charities as $item)
        @include('livewire.manager.card')
      @endforeach
    </div>
  @endif

  @if($showModal)
      <div class="flex justify-between gap-2">
        <div><h3 class="font-semibold">{{$model ? 'Изменить ' . $model->title : 'Добавить благотворительность'}}</h3></div>
        <div>
          <x-btn-secondary wire:click="closeModal">Отмена</x-btn-secondary>
          <x-btn-primary wire:click="save">Сохранить</x-btn-primary>
        </div>
      </div>

    <div x-data="{ type: '{{$formData['type']}}' }">
      @php
        $types = ['' => 'card', 'img' => 'img'];
      @endphp
      @include('livewire.manager.header')

      <div class="mb-4">
        <label><p>Текст</p>
          <textarea wire:model="formData.text" rows="8"
                    class="text-xs w-full rounded mt-1 border-gray-300"></textarea>
        </label>
        @error('formData.text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      <div class="mb-4">
        <label><p>URL</p>
          <input type="text" wire:model="formData.url" class="w-full rounded mt-1 border-gray-300">
        </label>
        @error('formData.url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
      </div>

      @if($model)
        <div class="col-span-6 sm:col-span-3">
          <h3>Изображение:</h3>
          <livewire:image-uploader :model="$model" field="img_id" wire:key="charities_img_id"/>
        </div>
      @endif

    </div>

    @include('livewire.manager.footer')

  @endif
</div>
