<x-slot name="header">
  <h2 class="font-semibold text-xl text-gray-800 leading-tight">Страницы кубков</h2>
</x-slot>

<div class="py-12">

  <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
      <div class="p-4">
        <x-validation-errors/>
      </div>
      <div class="flex space-x-4 px-4 py-4 justify-end">

      </div>

      <table class="w-full divide-y divide-gray-200">
        <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase">
        <tr>
          <th scope="col" class="px-2 py-3 text-left tracking-wider">id</th>
          <th scope="col" class="px-2 py-3 text-left tracking-wider">sort</th>
          <th scope="col" class="px-2 py-3 text-left tracking-wider">Название</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
        @foreach($items as $item)
          <tr wire:key="{{ $item->id }}">
            <td class="px-2 py-2 whitespace-nowrap">{{$item->id}}</td>
            <td class="px-2 py-2 whitespace-nowrap">{{$item->sort}}</td>
            <td class="px-2 py-2 whitespace-nowrap">
              <span class="inline-block w-3 h-3 rounded-full {{ $item->active ? 'bg-green-500' : 'bg-gray-200' }}"></span>
              <a href="/admin/pages/{{$item->id}}">{!! $item->title !!}</a>
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
