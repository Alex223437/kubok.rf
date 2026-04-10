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

        exec("nohup {$php} {$artisan} app:parse-leagues {$league} --log-id={$log->id} > /dev/null 2>&1 &");
    }

    public function render()
    {
        return view('livewire.parser-dashboard', [
            'latestLog' => ParseLog::latest('started_at')->first(),
        ]);
    }
}
