<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

use Illuminate\Support\Facades\Schedule;
use App\Models\ParseLog;

Schedule::call(function () {
    $log = ParseLog::create(['status' => 'running', 'started_at' => now()]);
    try {
        Artisan::call('app:parse-leagues');
        $log->update(['status' => 'success', 'output' => Artisan::output(), 'finished_at' => now()]);
    } catch (\Throwable $e) {
        $log->update(['status' => 'error', 'output' => Artisan::output() . "\nОшибка: " . $e->getMessage(), 'finished_at' => now()]);
    }
})->twiceDaily();
