<div @if($latestLog && $latestLog->status === 'running') wire:poll.5000ms @endif>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Парсер данных</h3>

    {{-- Статус последнего запуска --}}
    @if($latestLog)
        <div class="mb-4 flex items-center gap-3 text-sm flex-wrap">
            <span class="text-gray-500">Последнее обновление:</span>
            <span class="font-medium text-gray-800">{{ $latestLog->started_at->format('d.m.Y в H:i') }}</span>
            @if($latestLog->status === 'success')
                <span class="px-2 py-0.5 rounded bg-green-100 text-green-700 font-medium">✓ Успешно</span>
            @elseif($latestLog->status === 'error')
                <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 font-medium">✗ Ошибка</span>
            @else
                <span class="px-2 py-0.5 rounded bg-yellow-100 text-yellow-700 font-medium">⏳ Выполняется</span>
            @endif
            @if($latestLog->league)
                <span class="text-gray-400">({{ $latestLog->league }})</span>
            @endif
        </div>
    @else
        <div class="mb-4 text-sm text-gray-400">Ещё не запускался</div>
    @endif

    {{-- Форма запуска --}}
    <div class="flex flex-wrap gap-x-6 gap-y-2 mb-4 text-sm">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" wire:model="league" value="" wire:change="$set('basketGroup', '')">
            <span>Все</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" wire:model="league" value="khl" wire:change="$set('basketGroup', '')">
            <span>КХЛ</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" wire:model="league" value="rfs" wire:change="$set('basketGroup', '')">
            <span>Кубок России</span>
        </label>

        <span class="text-gray-300 self-center hidden sm:inline">|</span>

        @foreach(['super' => 'Баскет: Супер Лига', 'vysshaya' => 'Баскет: Высшая Лига', 'premier' => 'Баскет: Премьер Лига'] as $group => $label)
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" wire:model="league" value="basket"
                   wire:change="$set('basketGroup', '{{ $group }}')"
                   @if($league === 'basket' && $basketGroup === $group) checked @endif>
            <span>{{ $label }}</span>
        </label>
        @endforeach
    </div>

    <div class="flex items-center gap-3">
        @if($latestLog && $latestLog->status === 'running')
            <button wire:click="killParser" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none transition">
                Остановить
            </button>
        @else
            <x-primary-button wire:click="runParser" wire:loading.attr="disabled">
                <span wire:loading.remove wire:target="runParser">Запустить парсер</span>
                <span wire:loading wire:target="runParser">Запускаю...</span>
            </x-primary-button>
        @endif
    </div>

    {{-- Мини-консоль --}}
    @if($latestLog && $latestLog->output)
        <div class="mt-5">
            <div class="text-xs text-gray-400 mb-1 uppercase tracking-wide">Лог последнего запуска</div>
            <pre class="bg-gray-900 text-green-400 text-xs rounded p-4 overflow-y-auto" style="max-height: 260px; white-space: pre-wrap; word-break: break-word;">{{ trim($latestLog->output) }}</pre>
        </div>
    @endif
</div>
