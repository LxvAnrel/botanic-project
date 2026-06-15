<?php

namespace App\Notifications;

use App\Models\Plant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class PruningSeasonNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Plant $plant,
        public string $season,
    ) {
    }

    /**
     * Canais de entrega. WebPush so e usado se o usuario tiver inscricao;
     * o canal ignora silenciosamente quem nao tem.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        $channels = ['database', 'mail'];

        if (method_exists($notifiable, 'pushSubscriptions') && $notifiable->pushSubscriptions()->exists()) {
            $channels[] = WebPushChannel::class;
        }

        return $channels;
    }

    public function toArray(object $notifiable): array
    {
        return [
            'titulo' => '🌿 Época de Poda: ' . ucfirst($this->plant->nome_popular),
            'mensagem' => "Sua planta {$this->plant->nome_popular} está na época ideal de poda para {$this->season}. Verifique os detalhes!",
            'planta_nome' => $this->plant->nome_popular,
            'season' => $this->season,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->from(config('flora.mail.alertas.address'), config('flora.mail.alertas.name'))
            ->subject('✂️ Hora de podar: ' . $this->plant->nome_popular)
            ->greeting("Olá, {$notifiable->name} 🌿")
            ->line("Chegou a época ideal de poda da sua **{$this->plant->nome_popular}** ({$this->season}).")
            ->line('A poda na estação certa mantém a planta saudável, com crescimento equilibrado e mais florida.')
            ->action('Ver meus alertas', route('alertas'))
            ->line('Cuide bem do seu cantinho verde!')
            ->salutation('Com carinho, Equipe Flora');
    }

    public function toWebPush(object $notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title('✂️ Hora de podar: ' . $this->plant->nome_popular)
            ->body("Sua {$this->plant->nome_popular} está na época ideal de poda ({$this->season}).")
            ->icon('/favicon.ico')
            ->data(['url' => route('alertas')])
            ->options(['TTL' => 1209600]);
    }
}
