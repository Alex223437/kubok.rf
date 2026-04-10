<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

use Illuminate\Support\Facades\Schedule;
use App\Models\ParseLog;

// Раздельное расписание: каждая лига — свой лог, своё время
// КХЛ:            08:00 / 20:00
// Кубок России:   08:20 / 20:20
// Баскетбол:      08:45 / 20:45  (самый долгий — до ~30 мин с retry)

$scheduledLeagues = [
    ['league' => 'khl',    'times' => ['08:00', '20:00']],
    ['league' => 'rfs',    'times' => ['08:20', '20:20']],
    ['league' => 'basket', 'times' => ['08:45', '20:45']],
];

foreach ($scheduledLeagues as $item) {
    foreach ($item['times'] as $time) {
        Schedule::call(function () use ($item) {
            $log = ParseLog::create([
                'league'     => $item['league'],
                'status'     => 'running',
                'started_at' => now(),
            ]);
            try {
                Artisan::call('app:parse-leagues', [
                    '--league' => $item['league'],
                    '--log-id' => $log->id,
                ]);
            } catch (\Throwable $e) {
                $log->update([
                    'status'      => 'error',
                    'output'      => $e->getMessage(),
                    'finished_at' => now(),
                ]);
            }
        })->dailyAt($time);
    }
}
