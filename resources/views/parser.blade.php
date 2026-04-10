<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Парсер</h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <livewire:parser-dashboard />
          <p class="mt-4 text-sm text-gray-500">
            Автоматическое расписание: КХЛ 08:00/20:00 · Кубок России 08:20/20:20 · Супер Лига 08:45/20:45 · Высшая Лига 09:15/21:15 · Премьер Лига 09:45/21:45
          </p>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
