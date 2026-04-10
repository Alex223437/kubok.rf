<?php

namespace App\Jobs;

use App\Models\ParseLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class RunParserJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 1800; // 30 минут максимум

    public function __construct(
        public readonly int    $logId,
        public readonly string $league = '',
        public readonly string $basketGroup = '',
    ) {}

    public function handle(): void
    {
        $args = ['--log-id' => $this->logId];
        if ($this->league) {
            $args['--league'] = $this->league;
        }
        if ($this->basketGroup) {
            $args['--basket-group'] = $this->basketGroup;
        }

        try {
            Artisan::call('app:parse-leagues', $args);
            $output = Artisan::output();

            ParseLog::where('id', $this->logId)->update([
                'status'      => 'success',
                'output'      => $output ?: null,
                'finished_at' => now(),
            ]);
        } catch (\Throwable $e) {
            ParseLog::where('id', $this->logId)->update([
                'status'      => 'error',
                'output'      => Artisan::output() . "\nОшибка: " . $e->getMessage(),
                'finished_at' => now(),
            ]);
        }
    }
}
