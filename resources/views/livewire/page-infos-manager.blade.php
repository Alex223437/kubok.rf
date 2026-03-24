@php
  /** @var \App\Models\Page $page */
  /** @var \App\Models\PageInfo $model */
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
    <x-popup @mousedown="$wire.closeModal()" :size="'lg'" :title="$model ? 'Изменить - ' . $page->title : 'Добавить'">
      <div class="p-4" x-data="{ type: '{{$formData['type']}}' }">
        @php
          $types = ['' => 'text', 'html' => 'html', 'img' => 'img', '2text' => '2text', 'banner' => 'banner'];
        @endphp
        @include('livewire.manager.header')

        <div class="mb-2" x-show="type === 'banner'">
          <label class="block text-sm"><p>Баннер</p>
            <select class="rounded mt-1 w-full border-gray-300" wire:model="formData.banner_id">
              <option value=""></option>
              @foreach (\App\Models\Banner::all() as $b)
                <option value="{{ $b->id }}">{{ $b->title }}</option>
              @endforeach
            </select>
          </label>
        </div>

        <div class="mb-2" x-show="type !== 'img' && type !== 'html'">
          <label><p>Текст</p>
            <textarea wire:model="formData.text" rows="6"
                      class="text-xs w-full rounded mt-1 border-gray-300"></textarea>
          </label>
          @error('formData.text') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-2" x-show="type !== '' && type !== 'img'">
          <label>HTML</label>
          <textarea wire:model="formData.html" rows="6" class="text-xs w-full rounded mt-1 border-gray-300"></textarea>
          @error('formData.html') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        @if($model)
          <div x-show="type === 'img'">
            <h3>Изображение:</h3>
            <livewire:image-uploader :model="$model" field="img_id" wire:key="infos_img_id"/>
          </div>
        @endif

      </div>

      @include('livewire.manager.footer')

    </x-popup>
  @endif
</div>
