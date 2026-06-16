<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $userName,
        public string $userEmail,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('flora.mail.alertas.address'), config('flora.mail.alertas.name')),
            subject: 'Sua conta Flora foi excluída',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.account-deleted');
    }
}
