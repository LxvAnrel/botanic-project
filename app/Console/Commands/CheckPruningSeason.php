<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\PruningSeasonNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckPruningSeason extends Command
{
    protected $signature = 'plants:check-pruning-season';
    protected $description = 'Verifica a época de poda e envia notificações aos usuários';

    public function handle()
    {
        $this->info('Verificando épocas de poda das plantas...');

        $currentSeason = $this->getSeason();
        $this->info("Estação atual: " . ucfirst($currentSeason));

        $users = User::with('plants')->get();

        foreach ($users as $user) {
            foreach ($user->plants as $plant) {
                if (! $plant->isPruningSeason($currentSeason)) {
                    continue;
                }

                if ($this->alreadyNotified($user, $plant, $currentSeason)) {
                    $this->line("Já notificado: {$user->name} sobre {$plant->nome_popular}");
                    continue;
                }

                $user->notify(new PruningSeasonNotification($plant, $currentSeason));
                $this->info("Notificação enviada para {$user->name} sobre {$plant->nome_popular}");
            }
        }

        $this->info('Verificação concluída!');
    }

    /**
     * Evita reenviar a mesma notificação de poda (mesma planta + estação)
     * em uma janela recente, mesmo que o comando rode todos os dias.
     */
    private function alreadyNotified(User $user, $plant, string $season): bool
    {
        // Evita o operador JSON no SQL (incompativel com coluna `text` no Postgres):
        // busca as notificacoes recentes desse tipo e compara o payload em PHP.
        return $user->notifications()
            ->where('type', PruningSeasonNotification::class)
            ->where('created_at', '>=', now()->subDays(60))
            ->get()
            ->contains(function ($notification) use ($plant, $season) {
                $data = $notification->data;

                return ($data['planta_nome'] ?? null) === $plant->nome_popular
                    && ($data['season'] ?? null) === $season;
            });
    }

    private function getSeason()
    {
        $month = Carbon::now()->month;

        // Hemisfério Sul (Brasil)
        if ($month >= 3 && $month <= 5) {
            return 'outono';
        } elseif ($month >= 6 && $month <= 8) {
            return 'inverno';
        } elseif ($month >= 9 && $month <= 11) {
            return 'primavera';
        } else {
            return 'verão';
        }
    }
}
