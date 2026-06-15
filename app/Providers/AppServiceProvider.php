<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Em producao, todos os links/URLs gerados usam HTTPS.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        // Email de recuperacao de senha com remetente proprio e tema Flora.
        ResetPassword::toMailUsing(function ($notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->from(config('flora.mail.acesso.address'), config('flora.mail.acesso.name'))
                ->subject('Redefinição de senha · Flora')
                ->greeting('Olá!')
                ->line('Recebemos um pedido para redefinir a senha da sua conta Flora.')
                ->action('Redefinir senha', $url)
                ->line('Este link expira em ' . config('auth.passwords.users.expire', 60) . ' minutos.')
                ->line('Se você não fez este pedido, ignore este email — sua senha continua a mesma.')
                ->salutation('Equipe Flora');
        });
    }
}
