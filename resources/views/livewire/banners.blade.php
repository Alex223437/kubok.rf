<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">Баннеры</h2>
</x-slot>

<div class="py-12">

  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <div class="flex space-x-4 px-4 py-4 justify-end">
        <x-btn-primary wire:click="openModal">Добавить</x-btn-primary>
      </div>

      <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
        <tr>
          <th scope="col" class="px-2 py-3 text-left tracking-wider">id</th>
          <th scope="col" class="px-2 py-3 text-left tracking-wider">Название</th>
          <th scope="col" class="px-2 py-3 text-left tracking-wider"></th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($items as $item)
          <tr wire:key="{{ $item->id }}">
            <td class="px-2 py-2 whitespace-nowrap">{{$item->id}}</td>
            <td class="px-2 py-2 whitespace-nowrap">{{$item->title}}</td>
            <td class="px-2 py-2 whitespace-nowrap">
              <x-btn-thin wire:click="openModal({{ $item->id }})" class="w-full">Изменить</x-btn-thin>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>

      @if($showModal)
        <x-popup @mousedown="$wire.closeModal()" :size="'lg'" :title="$model ? 'Изменить' : 'Добавить'">
          <div class="p-4">
            <div class="mt-2 flex flex-wrap gap-2">
              <label><p>Название</p>
                <input type="text" wire:model="formData.title" class="w-full rounded mt-1 border-gray-300">
                @error('formData.title') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
              </label>

              <label><p>Код</p>
                <input type="text" value="{{$formData['code']}}" readonly class="rounded mt-1 border-gray-300">
              </label>

              <label><p>Активно</p>
                <input type="checkbox" wire:model="formData.active" class="rounded mt-1 border-gray-300">
              </label>

            </div>

            <div class="mt-2">
              <label><p>URL</p>
                <input type="text" wire:model="formData.url" class="w-full rounded mt-1 border-gray-300">
              </label>
              @error('formData.url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div x-data="{ active: 'html_md' }">
              <details class="mt-2 bg-gray-100 border rounded" :open="active === 'html_xs'">
                <summary @click.prevent="active = (active === 'html_xs' ? null : 'html_xs')"
                         class="p-3 cursor-pointer text-sm text-slate-900 font-semibold">
                  html_xs
                </summary>
                <textarea wire:model="formData.html_xs" rows="10"
                          class="text-xs w-full rounded border-gray-300"></textarea>
              </details>

              <details class="mt-2 bg-gray-100 border rounded" :open="active === 'html_md'">
                <summary @click.prevent="active = (active === 'html_md' ? null : 'html_md')"
                         class="p-3 cursor-pointer text-sm text-slate-900 font-semibold">
                  html_md
                </summary>
                <textarea wire:model="formData.html_md" rows="10"
                          class="text-xs w-full rounded border-gray-300"></textarea>
              </details>

              <details class="mt-2 bg-gray-100 border rounded" :open="active === 'html_xl'">
                <summary @click.prevent="active = (active === 'html_xl' ? null : 'html_xl')"
                         class="p-3 cursor-pointer text-sm text-slate-900 font-semibold">
                  html_xl
                </summary>
                <textarea wire:model="formData.html_xl" rows="10"
                          class="text-xs w-full rounded border-gray-300"></textarea>
              </details>
            </div>

          </div>

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
        </x-popup>
      @endif
    </div>
  </div>
</div>
