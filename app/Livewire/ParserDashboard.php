<?php

namespace App\Livewire;

use App\Models\ParseLog;
use Livewire\Component;

class ParserDashboard extends Component
{
    public string $league = '';

    public function runParser(): void
    {
        $log = ParseLog::create([
            'league'     => $this->league ?: null,
            'status'     => 'running',
            'started_at' => now(),
        ]);

        $php     = PHP_BINARY;
        $artisan = base_path('artisan');
        $league  = $this->league ? '--league=' . escapeshellarg($this->league) : '';

        // Запускаем в фоне и захватываем PID через $!
        $pid = (int) shell_exec("nohup {$php} {$artisan} app:parse-leagues {$league} --log-id={$log->id} > /dev/null 2>&1 & echo \$!");
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
