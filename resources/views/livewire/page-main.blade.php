@php
  /** @var \App\Models\Page $page */
@endphp
<form wire:submit="save">
  <input type="hidden" name="id" value="{{$page->id}}">

  <fieldset class="grid grid-cols-6 gap-4">

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">Название
        <input type="text" class="rounded mt-1 w-full border-gray-300" wire:model="title"/>
      </label>
      @error('title') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">Код url
        <input type="text" class="rounded mt-1 w-full border-gray-300" wire:model="code"/>
      </label>
      @error('code') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-1">
      <label class="block text-sm"><p>Тип</p>
        <select class="rounded mt-1 w-full border-gray-300" wire:model="type" name="type">
          @foreach (['' => 'Обычная', 'banner' => 'С баннером', 'promo' => 'Промо без ссылки'] as $k => $v)
            <option value="{{ $k }}">{{ $v }}</option>
          @endforeach
        </select>
      </label>
      @error('type') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-1">
      <label class="block text-sm"><p>Баннер</p>
        <select class="rounded mt-1 w-full border-gray-300" wire:model="banner_id" name="banner_id">
          <option value=""></option>
          @foreach (\App\Models\Banner::all() as $b)
            <option value="{{ $b->id }}">{{ $b->title }}</option>
          @endforeach
        </select>
      </label>
      @error('banner_id') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-1">
      <label class="block text-sm">Сортировка
        <input type="number" class="rounded mt-1 w-full border-gray-300" wire:model="sort"/>
      </label>
      @error('sort') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-1">
      <label class="block text-sm">Код (css)
        <input type="text" class="rounded mt-1 w-full border-gray-300" wire:model="payload.css_code"/>
      </label>
      @error('payload.css_code') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-1">
      <label class="block text-sm"><p>Активность</p>
        <input type="checkbox" class="rounded-md mt-1" wire:model="active"/>
      </label>
      @error('active') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-3">
      <x-input label="Профиль в TG" hint="(https://t.me/[...])" wire:model="payload.social_tg"/>
    </div>

    <div class="col-span-6 sm:col-span-3">
      <x-input label="Профиль в VK" hint="(https://vk.com/[...])" wire:model="payload.social_vk"/>
    </div>

  </fieldset>

  <div class="pt-4 flex justify-end">
    <div class="pr-4">
      @session('message')
      <span class="text-green-600"> {{ $value }} </span>
      @endsession
    </div>
    <x-btn-primary type="submit">Сохранить</x-btn-primary>
  </div>

  <hr class="m-4">

  <fieldset class="mt-4 grid grid-cols-6 gap-4">

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">Описание
        <textarea class="rounded w-full border-gray-300" rows="5" wire:model="description"></textarea>
      </label>
      @error('description') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">Факты
        <textarea class="rounded w-full border-gray-300" rows="5" wire:model="facts"></textarea>
      </label>
      @error('facts') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">HTML
        <textarea class="rounded w-full border-gray-300" rows="5" wire:model="html"></textarea>
      </label>
      @error('html') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

  </fieldset>

  <div class="pt-4 flex justify-end">
    <div class="pr-4">
      @session('message')
      <span class="text-green-600"> {{ $value }} </span>
      @endsession
    </div>
    <x-btn-primary type="submit">Сохранить</x-btn-primary>
  </div>

  <hr class="m-4">

</form>

<div class="grid grid-cols-6 gap-6">

  <div class="col-span-6 sm:col-span-3">
    <h3>Изображение в списке:</h3>
    <livewire:image-uploader :model="$page" field="img_id" wire:key="img_id"/>
  </div>

  <div class="col-span-6 sm:col-span-3">
    <h3>Изображение заголовке:</h3>
    <livewire:image-uploader :model="$page" field="picture_id" wire:key="picture_id"/>
  </div>

</div>
