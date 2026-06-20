<?php

namespace App\Console\Commands;

use App\Mail\CareAlertMail;
use App\Models\User;
use App\Notifications\CareReminderNotification;
use App\Support\PlantCare;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class CheckCareReminders extends Command
{
    protected $signature = 'plants:check-care';
    protected $description = 'Envia lembretes de rega/adubação atrasadas das plantas no Diário';

    public function handle()
    {
        $this->info('Verificando cuidados atrasados...');

        User::with('plants')->get()->each(function (User $user) {
            foreach ($user->plants as $plant) {
                foreach (['rega' => $plant->intervaloRega(), 'adubacao' => $plant->intervaloAdubacao()] as $tipo => $intervalo) {
                    $ultima = $user->careLogs()
                        ->where('plant_id', $plant->id)
                        ->where('tipo', $tipo)
                        ->max('data');

                    // Sem registro: usa a data em que a planta foi adicionada ao diário
                    if (! $ultima) {
                        $pivotPlant = $user->plants()->wherePivot('plant_id', $plant->id)->first();
                        $ultima = $pivotPlant?->pivot?->created_at?->toDateString();
                        if (! $ultima) {
                            continue;
                        }
                    }

                    $ultimaData = Carbon::parse($ultima);
                    $status = PlantCare::status($ultimaData, $intervalo);

                    if ($status['estado'] !== 'atrasado') {
                        continue;
                    }

                    if ($this->jaLembrado($user, $plant->nome_popular, $tipo, $ultimaData)) {
                        continue;
                    }

                    $diasAtraso = abs($status['dias']);
                    $user->notify(new CareReminderNotification($plant, $tipo, $diasAtraso));

                    if ($user->email_notifications) {
                        try {
                            Mail::to($user->email)->send(new CareAlertMail($user, $plant, $tipo, $diasAtraso));
                        } catch (\Throwable) {}
                    }

                    $this->info("Lembrete de {$tipo} enviado para {$user->name} sobre {$plant->nome_popular}");
                }
            }
        });

        $this->info('Verificação concluída!');
    }

    /**
     * Evita repetir o lembrete no mesmo ciclo: so notifica uma vez ate o
     * usuario registrar o cuidado (o que move a data e reinicia o ciclo).
     */
    private function jaLembrado(User $user, string $plantNome, string $tipo, Carbon $desde): bool
    {
        return $user->notifications()
            ->where('type', CareReminderNotification::class)
            ->where('created_at', '>=', $desde)
            ->get()
            ->contains(function ($n) use ($plantNome, $tipo) {
                $data = $n->data;
                return ($data['tipo'] ?? null) === $tipo
                    && ($data['planta_nome'] ?? null) === $plantNome;
            });
    }
}
