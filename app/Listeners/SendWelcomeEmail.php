<?php

namespace App\Listeners;

use App\Mail\WelcomeMail;
use App\Models\KnownDevice;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail
{
    public function handle(Registered $event): void
    {
        $user = $event->user;

        Mail::to($user->email)->send(new WelcomeMail($user));

        // Registra o dispositivo do cadastro como conhecido, para nao disparar
        // um alerta de "novo dispositivo" no primeiro login logo apos o registro.
        $request = request();
        KnownDevice::updateOrCreate(
            [
                'user_id' => $user->getAuthIdentifier(),
                'device_hash' => KnownDevice::hashFor($request->userAgent()),
            ],
            [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_seen_at' => now(),
            ]
        );

        // Sinaliza para a UI perguntar sobre notificacoes push no onboarding.
        session()->flash('ask_push', true);
    }
}
