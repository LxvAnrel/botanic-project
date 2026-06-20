<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FirstAnnotationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public int $version = 1,
    ) {}

    public function envelope(): Envelope
    {
        $subjects = [
            1 => 'Seu diário verde ainda está em branco 🌿',
            2 => 'Uma semana. Seu jardim ainda espera. 🌱',
            3 => 'Último chamado para o seu jardim 🍂',
        ];

        return new Envelope(
            from: new Address(config('flora.mail.ola.address'), config('flora.mail.ola.name')),
            subject: $subjects[$this->version] ?? $subjects[1],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: "emails.first-annotation-{$this->version}",
        );
    }
}
