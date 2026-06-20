<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Poda: uma vez por dia (sazonal, sem necessidade de repetição)
Schedule::command('plants:check-pruning-season')->dailyAt('08:00');

// Cuidados de plantas: 4x/dia — jaLembrado() previne duplicatas por ciclo
Schedule::command('plants:check-care')->at('07:00');
Schedule::command('plants:check-care')->at('12:00');
Schedule::command('plants:check-care')->at('17:00');
Schedule::command('plants:check-care')->at('21:00');

// Streak em risco: 4x/dia — Cache previne duplicatas dentro do mesmo dia
Schedule::command('streak:check-at-risk')->at('09:00');
Schedule::command('streak:check-at-risk')->at('13:00');
Schedule::command('streak:check-at-risk')->at('18:00');
Schedule::command('streak:check-at-risk')->at('22:00');

// CTA de primeira anotação: 4x/dia — jaEnviado() previne duplicatas por versão
Schedule::command('flora:first-annotation-emails')->at('06:00');
Schedule::command('flora:first-annotation-emails')->at('11:00');
Schedule::command('flora:first-annotation-emails')->at('16:00');
Schedule::command('flora:first-annotation-emails')->at('23:00');

// Limpeza de contas: uma vez por dia (madrugada)
Schedule::command('accounts:purge')->dailyAt('03:00');
