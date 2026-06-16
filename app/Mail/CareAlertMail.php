<?php

namespace App\Mail;

use App\Models\Plant;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CareAlertMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $plantaNome;

    public function __construct(
        public User  $user,
        Plant        $plant,
        public string $tipo,
        public int   $diasAtraso,
    ) {
        $this->plantaNome = $plant->nome_popular;
    }

    public function envelope(): Envelope
    {
        $labels = ['rega' => 'rega', 'adubacao' => 'adubação', 'poda' => 'poda'];
        $label  = $labels[$this->tipo] ?? $this->tipo;

        return new Envelope(
            from: new Address(config('flora.mail.ola.address'), config('flora.mail.ola.name')),
            subject: "🌿 {$this->plantaNome} precisa de {$label}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.care-alert',
        );
    }
}
