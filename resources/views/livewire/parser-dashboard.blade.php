<div>
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Парсер данных</h3>

    {{-- Статус последнего запуска --}}
    @if($latestLog)
        <div class="mb-4 flex items-center gap-3 text-sm">
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
    <div class="flex flex-wrap gap-4 mb-4">
        @foreach(['all' => 'Все', 'khl' => 'КХЛ', 'rfs' => 'Кубок России', 'basket' => 'Баскетбол'] as $value => $label)
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" wire:model="league" value="{{ $value === 'all' ? '' : $value }}">
            <span>{{ $label }}</span>
        </label>
        @endforeach
    </div>

    <div class="flex items-center gap-3">
        <x-primary-button wire:click="runParser" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="runParser">Запустить парсер</span>
            <span wire:loading wire:target="runParser">Запускаю...</span>
        </x-primary-button>
        <span wire:loading wire:target="runParser" class="text-sm text-gray-500 animate-pulse">
            Парсер работает, подождите...
        </span>
    </div>

    {{-- Мини-консоль --}}
    @if($latestLog && $latestLog->output)
        <div class="mt-5">
            <div class="text-xs text-gray-400 mb-1 uppercase tracking-wide">Лог последнего запуска</div>
            <pre class="bg-gray-900 text-green-400 text-xs rounded p-4 overflow-y-auto" style="max-height: 260px; white-space: pre-wrap; word-break: break-word;">{{ trim($latestLog->output) }}</pre>
        </div>
    @endif
</div>
