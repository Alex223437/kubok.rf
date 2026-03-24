@php
  /** @var \App\Models\Page $page */
@endphp
<div class="p-4">

  <div class="py-2">
    <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
  </div>

  <div class="flex flex-wrap gap-4">
    @foreach($page->infos as $item)
      @include('livewire.page.manager-card')
    @endforeach
  </div>

  @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-3xl" x-data="{ type: '{{$formData['type']}}' }">
        <h3 class="text-lg font-semibold mb-4">{{ $model ? 'Изменить' : 'Добавить' }}</h3>

        <div class="mb-4">
          <label><p>Название</p>
            <input type="text" wire:model="formData.title" class="w-full rounded mt-1 border-gray-300">
          </label>
          @error('formData.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
          <div class="flex flex-wrap gap-4">
            <label><p>Тип</p>
              <select class="rounded mt-1 w-full border-gray-300" wire:model="formData.type" x-model="type" name="type">
                @foreach (['' => 'text', 'html' => 'html', 'img' => 'img', '2text' => '2text'] as $k => $v)
                  <option value="{{ $k }}">{{ $v }}</option>
                @endforeach
              </select>
            </label>

            <label><p>Сортировка</p>
              <input type="number" class="rounded mt-1 border-gray-300" wire:model="formData.sort"/>
            </label>

            <label><p>Активно</p>
              <input type="checkbox" wire:model="formData.active" class="rounded mt-1 border-gray-300">
            </label>
          </div>
        </div>

        {{$slot}}

        @if($model)
          <div class="col-span-6 sm:col-span-3" x-show="type === 'img'">
            <h3>Изображение:</h3>
            <livewire:image-uploader :model="$model" field="img_id" wire:key="infos_img_id"/>
          </div>
        @endif

        <div class="flex justify-between gap-2">
          <div>
            @if($model)
              <x-btn-secondary wire:click="delete" wire:confirm="Действие не обратимо. Вы уверены?"
                               class="text-red-600 hover:bg-red-600 hover:text-white">
                Удалить
              </x-btn-secondary>
            @endif
          </div>
          <div>
            <x-btn-secondary wire:click="closeModal">Отмена</x-btn-secondary>
            <x-btn-primary wire:click="save">Сохранить</x-btn-primary>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
