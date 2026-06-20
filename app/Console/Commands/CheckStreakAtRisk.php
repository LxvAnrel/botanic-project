<?php

namespace App\Console\Commands;

use App\Mail\StreakAtRiskMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class CheckStreakAtRisk extends Command
{
    protected $signature   = 'streak:check-at-risk';
    protected $description = 'Avisa usuários que têm streak ativo mas não registraram cuidado hoje';

    public function handle(): void
    {
        $today = Carbon::today();

        User::where('streak_days', '>', 0)
            ->whereNotNull('streak_last_date')
            ->where('streak_last_date', '<', $today)
            ->whereNull('deletion_scheduled_at')
            ->each(function (User $user) {
                $hasLogToday = $user->careLogs()
                    ->whereDate('data', Carbon::today())
                    ->exists();

                if ($hasLogToday) {
                    return;
                }

                if (! $user->email_notifications) {
                    return;
                }

                $cacheKey = 'streak_at_risk_' . $user->id . '_' . Carbon::today()->format('Ymd');
                if (Cache::has($cacheKey)) {
                    return;
                }

                try {
                    Mail::to($user->email)->send(new StreakAtRiskMail($user));
                    Cache::put($cacheKey, true, Carbon::tomorrow());
                    $this->info("Streak at risk sent to {$user->email}");
                } catch (\Throwable $e) {
                    $this->warn("Failed for {$user->email}: " . $e->getMessage());
                }
            });

        $this->info('Streak at risk check done.');
    }
}
