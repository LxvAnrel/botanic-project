<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('plants:check-pruning-season')->daily();
Schedule::command('plants:check-care')->dailyAt('09:00');
Schedule::command('streak:check-at-risk')->dailyAt('20:00');
Schedule::command('accounts:purge')->dailyAt('03:00');
Schedule::command('flora:first-annotation-emails')->dailyAt('10:00');
