<?php

namespace App\Livewire;

use App\Jobs\RunParserJob;
use App\Models\ParseLog;
use Livewire\Component;

class ParserDashboard extends Component
{
    public string $selection = '';

    public function runParser(): void
    {
        [$league, $basketGroup] = $this->parseSelection();

        $log = ParseLog::create([
            'league'     => $this->selection ?: null,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        RunParserJob::dispatch($log->id, $league, $basketGroup);
    }

    public function killParser(): void
    {
        $log = ParseLog::where('status', 'running')->latest('started_at')->first();
        if (!$log) return;

        $log->update([
            'status'      => 'error',
            'output'      => 'Остановлен вручную.',
            'finished_at' => now(),
        ]);
    }

    public function render()
    {
        return view('livewire.parser-dashboard', [
            'latestLog' => ParseLog::latest('started_at')->first(),
        ]);
    }

    private function parseSelection(): array
    {
        if (str_starts_with($this->selection, 'basket-')) {
            return ['basket', substr($this->selection, 7)];
        }
        return [$this->selection, ''];
    }
}
