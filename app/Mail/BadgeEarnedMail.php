<?php

namespace App\Mail;

use App\Models\User;
use App\Support\Gamification;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BadgeEarnedMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $badge;
    public int   $totalXp;
    public int   $badgesEarned;
    public int   $streakDays;
    public string $levelLabel;
    public int   $levelPercent;

    public function __construct(public User $user, string $badgeSlug)
    {
        $def = collect(Gamification::BADGES)->firstWhere('slug', $badgeSlug);
        $this->badge        = $def ?? ['icon' => '🏆', 'title' => $badgeSlug, 'desc' => '', 'xp' => 0];
        $this->totalXp      = $user->xp ?? 0;
        $this->badgesEarned = $user->badges()->count();
        $this->streakDays   = $user->streak_days ?? 0;
        $progress           = Gamification::levelProgress($this->totalXp);
        $this->levelLabel   = $progress['level']['icon'] . ' ' . $progress['level']['label'];
        $this->levelPercent = $progress['percent'];
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(config('flora.mail.ola.address'), config('flora.mail.ola.name')),
            subject: '🏆 Nova conquista: ' . $this->badge['title'],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.badge-earned',
        );
    }
}
