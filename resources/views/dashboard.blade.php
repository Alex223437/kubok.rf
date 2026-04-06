<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          {{ __("You're logged in!") }}
        </div>
      </div>

      <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          <h3 class="text-lg font-semibold text-gray-800 mb-4">Парсер данных</h3>

          @if(session('parser_success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
              {{ session('parser_success') }}
            </div>
          @endif

          @if(session('parser_error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
              {{ session('parser_error') }}
            </div>
          @endif

          <form method="POST" action="{{ route('run-parser') }}" id="parser-form">
            @csrf
            <div class="flex flex-wrap gap-4 mb-4">
              @foreach(['all' => 'Все', 'khl' => 'КХЛ', 'rfs' => 'Кубок России', 'basket' => 'Баскетбол'] as $value => $label)
              <label class="flex items-center gap-2 cursor-pointer">
                <input type="radio" name="league" value="{{ $value === 'all' ? '' : $value }}" {{ $value === 'all' ? 'checked' : '' }}>
                <span>{{ $label }}</span>
              </label>
              @endforeach
            </div>
            <x-primary-button id="parser-btn">Запустить парсер</x-primary-button>
          </form>
          <p class="mt-2 text-sm text-gray-500">Автоматически запускается каждые 12 часов. Здесь можно запустить вручную.</p>

          <script>
            document.getElementById('parser-form').addEventListener('submit', function () {
              var btn = document.getElementById('parser-btn');
              btn.disabled = true;
              btn.textContent = 'Запускаю...';
            });
          </script>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
