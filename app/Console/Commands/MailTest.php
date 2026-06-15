<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class MailTest extends Command
{
    protected $signature = 'mail:test {email}';
    protected $description = 'Envia um email de teste e mostra o erro exato se falhar';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info('Mailer: ' . config('mail.default'));
        $this->info('Host: ' . config('mail.mailers.smtp.host') . ':' . config('mail.mailers.smtp.port'));
        $this->info('From: ' . config('mail.from.address'));
        $this->info('Username: ' . config('mail.mailers.smtp.username'));
        $this->info('Password definido? ' . (config('mail.mailers.smtp.password') ? 'sim' : 'NAO'));
        $this->line('Enviando teste para ' . $email . '...');

        try {
            Mail::raw('Teste de envio da Flora. Se você recebeu, o Resend está funcionando!', function ($m) use ($email) {
                $m->to($email)->subject('Teste Flora · ' . now()->format('H:i:s'));
            });
            $this->info('✓ Enviado sem erros. Verifique a caixa de entrada e o painel do Resend.');
        } catch (\Throwable $e) {
            $this->error('✗ FALHOU: ' . $e->getMessage());
            $this->line(get_class($e));
        }

        return self::SUCCESS;
    }
}
