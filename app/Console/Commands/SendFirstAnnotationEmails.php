<?php

namespace App\Console\Commands;

use App\Mail\FirstAnnotationMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class SendFirstAnnotationEmails extends Command
{
    protected $signature = 'flora:first-annotation-emails';
    protected $description = 'Envia CTA de primeira anotação para usuários que ainda não têm plantas no diário';

    private const TYPE = 'first_annotation_email';

    public function handle(): void
    {
        $now = Carbon::now();

        // V1: 24h após cadastro — janela de 8h para cobrir disparos a cada 6h sem lacunas
        $this->sendTo(1, $now->copy()->subHours(28), $now->copy()->subHours(20));

        // V2: 7 dias após cadastro
        $this->sendTo(2, $now->copy()->subDays(7)->subHours(4), $now->copy()->subDays(6)->subHours(20));

        // V3: 30 dias após cadastro
        $this->sendTo(3, $now->copy()->subDays(30)->subHours(4), $now->copy()->subDays(29)->subHours(20));

        $this->info('Concluído.');
    }

    private function sendTo(int $version, Carbon $from, Carbon $until): void
    {
        $users = User::whereBetween('created_at', [$from, $until])
            ->where('email_notifications', true)
            ->whereDoesntHave('plants')
            ->get();

        foreach ($users as $user) {
            if ($this->jaEnviado($user, $version)) {
                continue;
            }

            try {
                Mail::to($user->email)->send(new FirstAnnotationMail($user, $version));
                $this->registrar($user, $version);
                $this->info("V{$version} → {$user->email}");
            } catch (\Throwable $e) {
                $this->error("Falha {$user->email}: {$e->getMessage()}");
            }
        }
    }

    private function jaEnviado(User $user, int $version): bool
    {
        return DatabaseNotification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->where('type', self::TYPE)
            ->whereJsonContains('data->version', $version)
            ->exists();
    }

    private function registrar(User $user, int $version): void
    {
        DatabaseNotification::create([
            'id'              => Str::uuid(),
            'type'            => self::TYPE,
            'notifiable_type' => User::class,
            'notifiable_id'   => $user->id,
            'data'            => ['version' => $version],
            'read_at'         => now(), // já marca como lida para não poluir os alertas do usuário
        ]);
    }
}
