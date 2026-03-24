<div class="p-4 border-t flex justify-between gap-2">
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
