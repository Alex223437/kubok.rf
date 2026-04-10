<?php

namespace App\Livewire;

use App\Models\ParseLog;
use Livewire\Component;

class ParserDashboard extends Component
{
    // Единое значение: '', 'khl', 'rfs', 'basket-super', 'basket-vysshaya', 'basket-premier'
    public string $selection = '';

    public function runParser(): void
    {
        [$league, $basketGroup] = $this->parseSelection();

        $log = ParseLog::create([
            'league'     => $this->selection ?: null,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $php     = PHP_BINARY;
        $artisan = base_path('artisan');
        $args    = "--log-id={$log->id}";
        if ($league) {
            $args .= ' --league=' . escapeshellarg($league);
        }
        if ($basketGroup) {
            $args .= ' --basket-group=' . escapeshellarg($basketGroup);
        }

        $pid = (int) shell_exec("nohup {$php} {$artisan} app:parse-leagues {$args} > /dev/null 2>&1 & echo \$!");
        if ($pid) {
            $log->update(['pid' => $pid]);
        }
    }

    public function killParser(): void
    {
        $log = ParseLog::where('status', 'running')->latest('started_at')->first();
        if (!$log || !$log->pid) return;

        exec("kill {$log->pid} 2>/dev/null");
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
