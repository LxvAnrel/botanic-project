# 04 — Autenticação e segurança

## 1. Fluxo de autenticação (Breeze)

Rotas em `routes/auth.php`; controllers em `app/Http/Controllers/Auth/`.

### Registro (`RegisteredUserController@store`)
1. Valida `name`, `email` (único, minúsculo), `password` (confirmado, regras
   padrão de força).
2. Cria o usuário com `Hash::make($password)` (na verdade via cast `hashed`).
3. Dispara o **evento** `Registered`.
4. Faz login (`Auth::login`) e redireciona ao dashboard.

### Login (`AuthenticatedSessionController@store` + `LoginRequest`)
1. `LoginRequest::authenticate()` chama `Auth::attempt(['email','password'])`.
2. O Laravel busca o usuário, compara a senha com o **hash** (bcrypt) e, em
   sucesso, regenera a sessão (proteção contra *session fixation*).
3. Em falha, lança `ValidationException` com a mensagem `auth.failed`
   (traduzida em `lang/pt_BR/auth.php`: "E-mail ou senha incorretos…").

### Recuperação de senha
Fluxo padrão: envia e-mail com link assinado e expirável; o usuário define nova
senha. O e-mail é **rebrandizado** via `ResetPassword::toMailUsing(...)` em
`AppServiceProvider` (remetente `acesso@`, tema Flora).

## 2. Hashing de senha (conceito)

Senhas **nunca** são guardadas em texto. Usa-se **bcrypt** (cast `hashed`), um
hash **lento e com salt** — projetado para resistir a força bruta. Verificação
compara o hash, não a senha. `BCRYPT_ROUNDS=12` controla o custo.

## 3. Sessões

`SESSION_DRIVER=database`: o estado de login fica na tabela `sessions`, indexado
por um cookie de sessão assinado. **Por quê banco:** persiste entre reinícios e
funciona com múltiplas instâncias.

## 4. CSRF (Cross-Site Request Forgery)

Todo formulário POST inclui `@csrf` (um token por sessão). O middleware valida o
token; requisições sem ele são rejeitadas (419). Em chamadas `fetch`, envia-se o
token no header `X-CSRF-TOKEN` (lido da `<meta name="csrf-token">`).
**Conceito:** impede que outro site faça uma requisição autenticada em nome do
usuário, pois não conhece o token.

## 5. Rate limiting no login

`LoginRequest::ensureIsNotRateLimited()` usa o `RateLimiter`: após 5 tentativas
por `(email+IP)`, bloqueia e dispara o evento `Lockout`, retornando
`auth.throttle` ("Muitas tentativas… tente em N segundos"). **Conceito:** mitiga
ataques de força bruta e *credential stuffing*.

## 6. Alerta de login em dispositivo novo

Implementado por evento + listener (ver também [doc 07](07-notificacoes-push-email.md)):

- `KnownDevice::hashFor($userAgent)` = `sha256(user-agent)`. **Decisão de
  projeto:** baseia-se só no *user-agent* (não no IP, que muda em redes móveis)
  para evitar spam de alertas.
- No `Registered`, o `SendWelcomeEmail` já registra o dispositivo do cadastro
  como conhecido — assim o **primeiro login** não dispara alerta.
- No evento `Login`, `CheckLoginDevice`:
  1. calcula o hash; se já existe em `known_devices`, atualiza `last_seen_at`;
  2. se é novo, grava o dispositivo e envia o e-mail `NewDeviceAlertMail`
     (data/hora, IP, navegador/SO derivado do user-agent) com CTA para redefinir
     a senha caso não reconheça.
- O envio é **embrulhado em try/catch**: falha de e-mail nunca quebra o login.

## 7. HTTPS forçado

Em `AppServiceProvider::boot()`, em produção: `URL::forceScheme('https')` (todos
os links gerados usam https). No middleware `SecurityHeaders`, requisições http
são **redirecionadas (301)** para https. Como o Railway termina o TLS num proxy,
`trustProxies(at: '*')` em `bootstrap/app.php` faz o app confiar no cabeçalho
`X-Forwarded-Proto` para saber que a conexão é segura.

## 8. Cabeçalhos de segurança (`SecurityHeaders`)

Middleware global adiciona:
- `X-Frame-Options: SAMEORIGIN` (anti-clickjacking);
- `X-Content-Type-Options: nosniff` (não "adivinhar" MIME);
- `Referrer-Policy: strict-origin-when-cross-origin`;
- `Permissions-Policy` (desliga câmera/microfone/geolocalização);
- `Strict-Transport-Security` (HSTS) quando seguro — força o navegador a só usar
  https por um ano.

## 9. Exclusão de conta com período de reativação (LGPD)

`ProfileController::destroy` não apaga na hora: grava `deletion_scheduled_at`,
envia e-mail com **link assinado** (`URL::temporarySignedRoute`) válido por 30
dias para reativar, e desloga. Um comando agendado
(`PurgeDeletedAccounts`) remove definitivamente após o prazo. **Conceito:** "soft
delete" com janela de arrependimento + direito de exclusão.

## 10. Validação e mensagens

Regras de validação retornam mensagens traduzidas (`lang/pt_BR/validation.php`),
com nomes de campos amigáveis ("nome", "e-mail", "senha"). A UI mostra um banner
de erro (`<x-auth-error>`) e destaca campos inválidos.

## 11. Como eu faria na mão

- **Hash:** `password_hash($pw, PASSWORD_BCRYPT)` e `password_verify`.
- **Sessão:** cookie `HttpOnly`+`Secure`+`SameSite` apontando para um registro
  no banco; regenerar o id no login.
- **CSRF:** gerar token aleatório por sessão, embutir em hidden inputs, comparar
  no POST (comparação *constant-time*).
- **Rate limit:** contador por chave (email+IP) com TTL em cache.
- **Headers:** setar manualmente via `header()`.
- É muito código sensível — daí o valor de usar um framework auditado.
