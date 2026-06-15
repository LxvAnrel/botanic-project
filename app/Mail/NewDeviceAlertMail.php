<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewDeviceAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ip,
        public string $device,
        public string $when,
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('flora.mail.seguranca.address'), config('flora.mail.seguranca.name')),
            subject: '🔒 Novo acesso à sua conta Flora',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.new-device',
        );
    }
}
