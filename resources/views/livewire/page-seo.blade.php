@php
  /** @var \App\Models\Page $page */
@endphp
<form wire:submit="saveSeo">
  <input type="hidden" name="id" value="{{$page->id}}">

  <fieldset class="grid grid-cols-6 gap-6">

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">title
        <input type="text" class="rounded mt-1 w-full border-gray-300" wire:model="meta_title"/>
      </label>
      @error('meta_title') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">meta description
        <textarea rows="5" class="rounded mt-1 w-full border-gray-300" wire:model="meta_description"></textarea>
      </label>
      @error('meta_description') <small class="text-red-600">{{$message}}</small> @enderror
    </div>

    <div class="col-span-6 sm:col-span-6">
      <label class="block text-sm">meta keywords
        <textarea rows="5" class="rounded mt-1 w-full border-gray-300" wire:model="meta_keywords"></textarea>
      </label>
      @error('meta_keywords') <small class="text-red-600">{{$message}}</small> @enderror
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

</form>
