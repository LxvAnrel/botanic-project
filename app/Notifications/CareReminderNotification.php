<?php

namespace App\Notifications;

use App\Models\CareLog;
use App\Models\Plant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class CareReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Plant $plant,
        public string $tipo,   // rega | adubacao
        public int $diasAtraso,
    ) {
    }

    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if (method_exists($notifiable, 'pushSubscriptions') && $notifiable->pushSubscriptions()->exists()) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    private function verbo(): string
    {
        return $this->tipo === 'rega' ? 'regar' : 'adubar';
    }

    private function icone(): string
    {
        return $this->tipo === 'rega' ? '💧' : '🌱';
    }

    public function toArray(object $notifiable): array
    {
        $rotulo = CareLog::rotulo($this->tipo);

        return [
            'titulo' => "{$this->icone()} {$rotulo} atrasada: " . $this->plant->nome_popular,
            'mensagem' => "Está na hora de {$this->verbo()} sua {$this->plant->nome_popular} (atrasada {$this->diasAtraso} dia(s)).",
            'planta_nome' => $this->plant->nome_popular,
            'tipo' => $this->tipo,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('flora.mail.alertas.address'), config('flora.mail.alertas.name'))
            ->subject("{$this->icone()} Hora de " . $this->verbo() . ': ' . $this->plant->nome_popular)
            ->greeting("Olá, {$notifiable->name} 🌿")
            ->line("Sua **{$this->plant->nome_popular}** está esperando: a " . CareLog::rotulo($this->tipo) . " está atrasada em {$this->diasAtraso} dia(s).")
            ->action('Abrir meu Diário', route('dashboard'))
            ->line('Depois de cuidar, registre no Diário para reiniciar o lembrete.')
            ->salutation('Com carinho, Equipe Flora');
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title("{$this->icone()} Hora de " . $this->verbo() . ': ' . $this->plant->nome_popular)
            ->body("Atrasada {$this->diasAtraso} dia(s). Toque para abrir seu Diário.")
            ->icon('/favicon.ico')
            ->data(['url' => route('dashboard')])
            ->options(['TTL' => 1209600]);
    }
}
