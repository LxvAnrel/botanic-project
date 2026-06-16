<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountDeletionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $reactivationUrl,
        public string $expiresAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('flora.mail.alertas.address'), config('flora.mail.alertas.name')),
            subject: 'Sua conta Flora está agendada para exclusão',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'emails.account-deletion');
    }
}
