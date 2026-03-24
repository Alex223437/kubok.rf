@php
  /** @var \App\Models\FilePath $filePath */
@endphp
<div>

    @if($filePath)
      <div class="my-2 w-32 h-32">
        @if($filePath->path)
          <a href="{{$filePath->path ?? '#'}}" @if($filePath->path) target="_blank" @endif>
            <img src="{{ $filePath->path }}" alt="{{$filePath->name}}"
                 class="bg-gray-200 object-cover w-full h-full rounded-lg shadow">
          </a>
        @else
          <div class="bg-gray-200 w-full h-full rounded-lg shadow"></div>
        @endif
      </div>
      <p class="text-xs text-gray-600">{{$filePath->path}}</p>
      <p class="text-xs text-gray-600">[{{$filePath->id}}] {{ $filePath->name }} ( {{ $filePath->getFormattedSize() }} )</p>
    @endif

    <label class="block py-2">
      <input type="file" wire:model="image"
             class="text-sm text-gray-500 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
    </label>


  <div wire:loading wire:target="image">
    <div>Загрузка...</div>
  </div>

  @error('image')
  <span class="text-sm text-red-600">{{ $message }}</span>
  @enderror
</div>
