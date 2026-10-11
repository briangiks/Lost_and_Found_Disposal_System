<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Runs the quarterly disposal workflow automatically every morning: sends the
// Day-90 notice to Communications/students, and releases any batch whose
// 7-day pickup window has elapsed. For this to actually fire on a real
// server you need the Laravel scheduler's cron entry running, e.g.
// (crontab -e):
//   * * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
// On XAMPP for Windows, use Task Scheduler to run
// `php artisan schedule:run` once a minute instead of cron.
Schedule::command('disposal:process-cycle')
    ->dailyAt('07:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/disposal-cycle.log'));
