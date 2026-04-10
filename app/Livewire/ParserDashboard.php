<?php

namespace App\Livewire;

use App\Models\ParseLog;
use Livewire\Component;

class ParserDashboard extends Component
{
    public string $league = '';
    public string $basketGroup = '';

    public function runParser(): void
    {
        $logLeague = $this->league ?: null;
        if ($this->league === 'basket' && $this->basketGroup) {
            $logLeague = 'basket-' . $this->basketGroup;
        }

        $log = ParseLog::create([
            'league'     => $logLeague,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $php     = PHP_BINARY;
        $artisan = base_path('artisan');
        $args    = "--log-id={$log->id}";
        if ($this->league) {
            $args .= ' --league=' . escapeshellarg($this->league);
        }
        if ($this->league === 'basket' && $this->basketGroup) {
            $args .= ' --basket-group=' . escapeshellarg($this->basketGroup);
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
}
