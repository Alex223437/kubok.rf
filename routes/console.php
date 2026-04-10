<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

use Illuminate\Support\Facades\Schedule;
use App\Models\ParseLog;

// Расписание:
// КХЛ:              08:00 / 20:00
// Кубок России:     08:20 / 20:20
// Баскет Супер:     08:45 / 20:45  (msl + wsl)
// Баскет Высшая:    09:15 / 21:15  (mhl + whl)
// Баскет Премьер:   09:45 / 21:45  (wpremier)

$scheduledLeagues = [
    ['league' => 'khl',    'group' => null,       'times' => ['08:00', '20:00']],
    ['league' => 'rfs',    'group' => null,       'times' => ['08:20', '20:20']],
    ['league' => 'basket', 'group' => 'super',    'times' => ['08:45', '20:45']],
    ['league' => 'basket', 'group' => 'vysshaya', 'times' => ['09:15', '21:15']],
    ['league' => 'basket', 'group' => 'premier',  'times' => ['09:45', '21:45']],
];

foreach ($scheduledLeagues as $item) {
    foreach ($item['times'] as $time) {
        Schedule::call(function () use ($item) {
            $logLeague = $item['league'] . ($item['group'] ? '-' . $item['group'] : '');
            $log = ParseLog::create([
                'league'     => $logLeague,
                'status'     => 'running',
                'started_at' => now(),
            ]);
            $args = ['--league' => $item['league'], '--log-id' => $log->id];
            if ($item['group']) {
                $args['--basket-group'] = $item['group'];
            }
            try {
                Artisan::call('app:parse-leagues', $args);
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
