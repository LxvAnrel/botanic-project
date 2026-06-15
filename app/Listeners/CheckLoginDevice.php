<?php

namespace App\Listeners;

use App\Mail\NewDeviceAlertMail;
use App\Models\KnownDevice;
use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckLoginDevice
{
    public function handle(Login $event): void
    {
        $user = $event->user;

        if (! $user instanceof User) {
            return;
        }

        $request = request();
        $userAgent = $request->userAgent();
        $hash = KnownDevice::hashFor($userAgent);

        $existing = $user->knownDevices()->where('device_hash', $hash)->first();

        if ($existing) {
            $existing->update([
                'ip' => $request->ip(),
                'last_seen_at' => now(),
            ]);

            return;
        }

        $user->knownDevices()->create([
            'device_hash' => $hash,
            'ip' => $request->ip(),
            'user_agent' => $userAgent,
            'last_seen_at' => now(),
        ]);

        Mail::to($user->email)->send(new NewDeviceAlertMail(
            user: $user,
            ip: $request->ip() ?? 'desconhecido',
            device: self::describeDevice($userAgent),
            when: now()->timezone(config('app.timezone'))->format('d/m/Y H:i'),
        ));
    }

    /**
     * Descricao amigavel do navegador/SO a partir do user-agent.
     */
    public static function describeDevice(?string $ua): string
    {
        if (! $ua) {
            return 'Dispositivo desconhecido';
        }

        $browser = match (true) {
            Str::contains($ua, 'Edg') => 'Edge',
            Str::contains($ua, 'OPR') || Str::contains($ua, 'Opera') => 'Opera',
            Str::contains($ua, 'Chrome') => 'Chrome',
            Str::contains($ua, 'Firefox') => 'Firefox',
            Str::contains($ua, 'Safari') => 'Safari',
            default => 'Navegador',
        };

        $os = match (true) {
            Str::contains($ua, 'Windows') => 'Windows',
            Str::contains($ua, ['iPhone', 'iPad', 'iPod']) => 'iOS',
            Str::contains($ua, 'Mac OS') => 'macOS',
            Str::contains($ua, 'Android') => 'Android',
            Str::contains($ua, 'Linux') => 'Linux',
            default => 'sistema desconhecido',
        };

        return "{$browser} em {$os}";
    }
}
