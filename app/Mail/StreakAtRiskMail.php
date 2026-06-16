<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StreakAtRiskMail extends Mailable
{
    use Queueable, SerializesModels;

    public int $streakDays;

    public function __construct(public User $user)
    {
        $this->streakDays = $user->streak_days ?? 0;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('flora.mail.ola.address'), config('flora.mail.ola.name')),
            subject: "🔥 Sua sequência de {$this->streakDays} dias está em risco!",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.streak-at-risk',
        );
    }
}
