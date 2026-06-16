<?php

namespace App\Console\Commands;

use App\Mail\AccountDeletedMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PurgeDeletedAccounts extends Command
{
    protected $signature = 'accounts:purge';
    protected $description = 'Exclui permanentemente contas agendadas há mais de 30 dias';

    public function handle(): void
    {
        $cutoff = now()->subDays(30);

        User::whereNotNull('deletion_scheduled_at')
            ->where('deletion_scheduled_at', '<=', $cutoff)
            ->each(function (User $user) {
                $name  = $user->name;
                $email = $user->email;

                $user->delete();

                try {
                    Mail::to($email)->send(new AccountDeletedMail($name, $email));
                } catch (\Throwable $e) {
                    $this->warn("Email de exclusão não enviado para {$email}: " . $e->getMessage());
                }

                $this->info("Conta excluída: {$email}");
            });

        $this->info('Purge concluído.');
    }
}
