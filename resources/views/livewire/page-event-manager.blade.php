<div class="p-4">
  <h2 class="text-2xl py-2">Благотворительность</h2>

  <div class="py-2">
    <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
  </div>

  <div class="flex flex-wrap gap-4">
    @foreach($page->events as $item)
      <div class="w-[200px] p-4 bg-white rounded-lg shadow-md border-gray-200 border">
        <div class="flex items-center justify-between mb-2">
          <div class="text-sm font-semibold">{{ $item->title }}</div>
          <div class="w-3 h-3 rounded-full {{ $item->active ? 'bg-green-500' : 'bg-gray-200' }}"></div>
        </div>
        <x-btn-thin wire:click="openModal({{ $item->id }})" class="w-full">Изменить</x-btn-thin>
      </div>
    @endforeach
  </div>

  @if($showModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-6 rounded-lg w-full max-w-md">
        <h3 class="text-lg font-semibold mb-4">{{ $model ? 'Изменить' : 'Добавить' }}</h3>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Название</label>
          <input type="text" wire:model="formData.title" class="w-full rounded border-gray-300">
          @error('formData.title')
          <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>

        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Описание</label>
          <textarea wire:model="formData.description" class="w-full rounded border-gray-300" rows="3"></textarea>
          @error('formData.description')
          <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>

        <div class="mb-4">
          <label class="flex items-center">
            <input type="checkbox" wire:model="formData.active" class="rounded border-gray-300">
            <span class="ml-2 text-sm">Активно</span>
          </label>
        </div>

        <div class="flex justify-between gap-2">
          @if($model)
            <x-btn-secondary wire:click="delete" wire:confirm="Действие не обратимо. Вы уверены?"
                             class="text-red-500 hover:bg-red-600 hover:text-white">
              Удалить
            </x-btn-secondary>
          @endif
          <div>
            <x-btn-secondary wire:click="closeModal">Отмена</x-btn-secondary>
            <x-btn-primary wire:click="save">Сохранить</x-btn-primary>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
