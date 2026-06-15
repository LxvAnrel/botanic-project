<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Log;

class LogMailSending
{
    public function handle(MessageSending $event): void
    {
        $to = [];
        foreach ($event->message->getTo() as $address) {
            $to[] = $address->getAddress();
        }

        Log::info('[MAIL] Tentando enviar email', [
            'mailer' => config('mail.default'),
            'host' => config('mail.mailers.smtp.host'),
            'from' => config('mail.from.address'),
            'to' => $to,
            'subject' => $event->message->getSubject(),
        ]);
    }
}
