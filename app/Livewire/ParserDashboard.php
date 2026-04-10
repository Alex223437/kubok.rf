<?php

namespace App\Livewire;

use App\Models\ParseLog;
use Illuminate\Support\Facades\Artisan;
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

        try {
            $args = $this->league ? ['--league' => $this->league] : [];
            Artisan::call('app:parse-leagues', $args);
            $output = Artisan::output();

            $log->update([
                'status'      => 'success',
                'output'      => $output,
                'finished_at' => now(),
            ]);
        } catch (\Throwable $e) {
            $log->update([
                'status'      => 'error',
                'output'      => ($output ?? '') . "\nОшибка: " . $e->getMessage(),
                'finished_at' => now(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.parser-dashboard', [
            'latestLog' => ParseLog::latest('started_at')->first(),
        ]);
    }
}
