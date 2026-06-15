<?php

namespace App\Console\Commands;

use App\Models\Plant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

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
                if ($plant->isPruningSeason($currentSeason)) {
                    $this->createNotification($user, $plant, $currentSeason);
                    $this->info("Notificação criada para {$user->name} sobre {$plant->nome_popular}");
                }
            }
        }

        $this->info('Verificação concluída!');
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

    private function createNotification($user, $plant, $season)
    {
        $data = [
            'titulo' => '🌿 Época de Poda: ' . ucfirst($plant->nome_popular),
            'mensagem' => "Sua planta {$plant->nome_popular} está em sua época ideal de poda para {$season}. Verifique os detalhes!",
            'planta_nome' => $plant->nome_popular,
            'season' => $season,
        ];

        \DB::table('notifications')->insert([
            'id' => \Illuminate\Support\Str::uuid(),
            'notifiable_type' => 'App\\Models\\User',
            'notifiable_id' => $user->id,
            'type' => 'App\\Notifications\\PruningSeasonNotification',
            'data' => json_encode($data),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
