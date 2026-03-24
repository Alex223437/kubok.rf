<div class="mb-4">
  <label><p>Название</p>
    <input type="text" wire:model="formData.title" class="w-full rounded mt-1 border-gray-300">
  </label>
  @error('formData.title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
</div>

<div class="mb-4">
  <div class="flex flex-wrap gap-4">
    @if($types)
      <label><p>Тип</p>
        <select class="rounded mt-1 w-full border-gray-300" wire:model="formData.type" x-model="type" name="type">
          @foreach ($types as $k => $v)
            <option value="{{ $k }}">{{ $v }}</option>
          @endforeach
        </select>
      </label>
    @endif

    <label><p>Сортировка</p>
      <input type="number" class="rounded mt-1 border-gray-300" wire:model="formData.sort"/>
    </label>

    <label><p>Активно</p>
      <input type="checkbox" wire:model="formData.active" class="rounded mt-1 border-gray-300">
    </label>
  </div>
</div>
