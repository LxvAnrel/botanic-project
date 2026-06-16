# 07 — Notificações: push e e-mail

## 1. O sistema de notificações do Laravel (multicanal)

Uma **Notification** (`app/Notifications/`) é uma mensagem que pode ser entregue
por vários **canais** ao mesmo tempo. O método `via()` decide os canais; métodos
`toX()` definem o conteúdo por canal.

`PruningSeasonNotification` e `CareReminderNotification`:

```php
public function via($notifiable): array {
    $canais = ['database', 'mail'];
    if ($notifiable->pushSubscriptions()->exists()) $canais[] = WebPushChannel::class;
    return $canais;
}
public function toArray($n)   { return ['titulo'=>…,'mensagem'=>…,'planta_nome'=>…]; } // canal database
public function toMail($n)    { return (new MailMessage)->from(config('flora.mail.alertas...'))->subject(…)->line(…)->action(…); }
public function toWebPush($n,$x) { return (new WebPushMessage)->title(…)->body(…)->data(['url'=>route('alertas')]); }
```

- **`database`** grava na tabela `notifications` (aparece em `/alertas`).
- **`mail`** envia e-mail.
- **`WebPushChannel`** só entra se o usuário tiver inscrição — o canal ignora
  quem não tem.

Disparo: `$user->notify(new PruningSeasonNotification($plant, $estacao))`.

## 2. Notificações no banco e a página de alertas

`DashboardController::alertas` lista `$user->notifications()` (com filtro por
tipo), marca como lida (`markAsRead`), marca todas, ou exclui. O *badge* de não
lidas vem de `unreadNotifications()->count()`. Cada notificação guarda seu
payload em `data` (JSON), lido na view.

## 3. E-mail: Mailables e o transporte

### Mailables
`app/Mail/*` — cada e-mail transacional é uma classe (`WelcomeMail`,
`NewDeviceAlertMail`, `BadgeEarnedMail`, etc.) com `envelope()` (assunto,
remetente) e `content()` (view). Vários usam **views HTML table-based** (e-mail
exige tabelas e CSS inline para compatibilidade entre clientes).

### Remetentes por finalidade
`config/flora.php` centraliza endereços: `seguranca@` (login), `acesso@`
(senha), `alertas@` (poda/cuidados), `ola@` (boas-vindas), `contato@` (legal).
Todos do mesmo domínio verificado — **uma** verificação cobre todos.

### Transporte: Resend via API HTTP
`MAIL_MAILER=resend` usa o transporte nativo do Laravel, que fala com a **API
HTTPS** do Resend (porta 443). **Por quê não SMTP:** o Railway bloqueia portas
SMTP (testes deram *connection timed out* na 587). A API HTTP contorna isso.

### Resiliência
Envios em listeners (boas-vindas, dispositivo novo) ficam em **try/catch**: se o
e-mail falhar, apenas registra um aviso no log — **nunca** quebra login/cadastro.

### Tema visual dos e-mails padrão
`config/mail.php` define `markdown.theme = 'flora'`; o CSS está em
`resources/views/vendor/mail/html/themes/flora.css` (fundo escuro, dourado).
Assim, e-mails markdown do Laravel (ex.: reset de senha) saem com a identidade do site.

## 4. Eventos e listeners

O Laravel **descobre automaticamente** listeners em `app/Listeners/` pelo tipo do
evento no método `handle()`:

- `Registered` → `SendWelcomeEmail`: envia boas-vindas, registra o dispositivo do
  cadastro (para não alertar no 1º login) e marca `flash('ask_push')` para o
  onboarding de notificações.
- `Login` → `CheckLoginDevice`: detecta dispositivo novo e envia alerta de
  segurança (ver [doc 04](04-autenticacao-e-seguranca.md)).

> **Conceito — arquitetura orientada a eventos:** o controller só dispara o
> evento (`event(new Registered($user))`); quem reage são os listeners. Isso
> desacopla "o que aconteceu" de "o que fazer a respeito".

## 5. Web Push (notificação no celular) — a nível micro

### Peças
1. **VAPID** (`config/webpush.php` lê `VAPID_PUBLIC_KEY/PRIVATE_KEY/SUBJECT`):
   par de chaves EC P-256 que **autentica o servidor** junto ao *push service*.
2. **Service Worker** (`public/sw.js`): script que roda em segundo plano no
   navegador; escuta o evento `push` (mostra a notificação) e `notificationclick`
   (abre `/alertas`).
3. **Inscrição** (`public/js/push.js`):
   - pede permissão (`Notification.requestPermission()`);
   - registra o SW e chama `pushManager.subscribe()` com a **chave pública VAPID**;
   - envia a inscrição (`endpoint`, chaves `p256dh`/`auth`) para
     `POST /push/subscribe` → `PushSubscriptionController@store` →
     `updatePushSubscription()` grava em `push_subscriptions`.
4. **Envio**: `WebPushChannel` usa a biblioteca `minishlink/web-push`, que cifra
   o payload (criptografia de mensagem do padrão Web Push) e o entrega ao
   *endpoint* do navegador, assinando com as chaves VAPID.

### Fluxo completo
```
[Perfil] Ativar → permissão → subscribe(VAPID público) → POST /push/subscribe → DB
…
Comando agendado → $user->notify(...) → WebPushChannel → push service → Service Worker → notificação
clique → abre /alertas
```

### Requisitos e limitações
- **HTTPS obrigatório** (o site já força https).
- **iOS** só recebe push se o site for instalado como **PWA** ("Adicionar à Tela
  de Início"); Android/Chrome funciona direto.
- A ativação é **por dispositivo/navegador**.

## 6. Onboarding e UI de push

- Logo após o cadastro, um **modal** pergunta se o usuário quer ativar
  (disparado pelo `flash('ask_push')`).
- Um **banner** no Diário e um **card no Perfil** permitem ativar/desativar a
  qualquer momento; o JS checa o estado atual (`pushManager.getSubscription()`).

## 7. Como eu faria na mão

- **E-mail:** falar com a API do provedor por HTTP (`POST /emails` com
  remetente/destinatário/HTML e a API key no header). É o que o transporte
  `resend` faz por baixo.
- **Push:** gerar chaves VAPID (OpenSSL, curva prime256v1, exportar X/Y/d em
  base64url), servir um `sw.js`, usar a Push API no front e, no back, cifrar o
  payload conforme o RFC 8291 e enviar ao endpoint com cabeçalho `Authorization`
  VAPID. A biblioteca `web-push` encapsula essa criptografia.
