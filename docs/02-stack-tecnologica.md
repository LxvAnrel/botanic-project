# 02 — Stack tecnológica

Cada tecnologia escolhida, o papel que cumpre e o conceito por trás.

## 1. PHP 8.3 (linguagem)

Linguagem de servidor que gera HTML dinamicamente. Versão 8.3 traz tipos mais
estritos, *enums*, *match*, *readonly*, *constructor property promotion* — usados
no código (ex.: `match(true)`, propriedades promovidas em notificações).

## 2. Laravel 13 (framework)

Framework MVC que fornece: roteamento, ORM (Eloquent), motor de templates
(Blade), sistema de migrations, fila, agendamento, notificações, e-mail, eventos,
validação e autenticação. **Por quê:** entrega pronto e testado o que levaria
meses para escrever à mão com segurança (sessão, CSRF, hashing, etc.).

Componentes do Laravel usados:
- **Eloquent ORM** — mapeia tabelas para classes PHP (ver [doc 03](03-modelagem-de-dados.md)).
- **Blade** — templates com herança (`@extends`), componentes (`<x-...>`),
  diretivas (`@if`, `@foreach`, `@auth`). Compila para PHP em cache.
- **Migrations/Seeders** — versionamento do banco.
- **Notifications + Mail** — mensageria multicanal.
- **Task Scheduling** — cron expressado em PHP.
- **Validation** — regras de entrada com mensagens traduzíveis.

## 3. Laravel Breeze (kit de autenticação)

Scaffolding mínimo de autenticação (login, registro, recuperação de senha,
verificação de e-mail, confirmação de senha). Gera controllers em
`app/Http/Controllers/Auth/` e rotas em `routes/auth.php`. **Por quê:** segurança
de autenticação é fácil de errar; Breeze segue as melhores práticas.

## 4. Livewire 4 (reatividade server-side)

Permite criar UI dinâmica (filtros, wizard do quiz) **escrevendo PHP**, sem um
framework JS. O estado do componente vive no servidor; interações disparam AJAX
que reexecuta o componente e devolve HTML atualizado. **Por quê:** reduz
complexidade — um único modelo mental (PHP) em vez de PHP + SPA JavaScript.
Detalhes em [doc 05](05-catalogo-e-quiz-livewire.md).

## 5. Tailwind CSS (estilização)

Framework CSS *utility-first*: você compõe o visual com classes utilitárias no
HTML (`flex`, `rounded-2xl`, `text-[#C8A96E]`). **Por quê:** consistência visual,
sem CSS global crescendo sem controle. O projeto define um tema escuro botânico e
componentes vidro (`.glass`) em `resources/css/app.css`.

## 6. Vite (build do front-end)

Empacota e otimiza CSS/JS de `resources/` para `public/build/`. Em produção gera
arquivos com hash (cache-busting). O Blade referencia via `@vite([...])`.

## 7. Alpine.js (micro-interações)

Vem embutido no Livewire. No projeto, a maior parte das micro-interações usa
**JavaScript vanilla** (ex.: `toggleFavorite`, menu mobile, push), por
consistência e por algumas páginas (auth) não carregarem o Livewire.

## 8. PostgreSQL (produção) / SQLite (local)

Banco relacional. Em produção usa-se **PostgreSQL** (robusto, no Railway); em
desenvolvimento, **SQLite** (arquivo único, zero configuração). O Eloquent
abstrai as diferenças de SQL. **Conceito:** o mesmo código roda em ambos porque
o ORM gera SQL específico do *driver*.

## 9. Resend (envio de e-mail via API HTTP)

Serviço transacional de e-mail. Usa-se o **transporte `resend`** do Laravel
(API HTTPS na porta 443) em vez de SMTP, porque a plataforma de hospedagem
(Railway) bloqueia portas SMTP. Ver [doc 07](07-notificacoes-push-email.md).

## 10. Web Push + VAPID (notificação no celular)

Padrão do navegador (*Push API* + *Service Worker*) para enviar notificações
mesmo com o site fechado. **VAPID** (Voluntary Application Server Identification)
é o par de chaves que autentica o servidor junto ao *push service* do navegador.
Pacote: `laravel-notification-channels/webpush`. Ver [doc 07](07-notificacoes-push-email.md).

## 11. Railway + Nixpacks (implantação)

**Railway** é a PaaS onde o app roda. **Nixpacks** é o construtor que lê
`nixpacks.toml`, instala PHP/extensões, roda `composer install` e `npm run build`,
e inicia o app. Ver [doc 11](11-implantacao-railway-dominio.md).

## 12. Composer e npm (gerenciadores de dependência)

- **Composer**: dependências PHP (`composer.json` / `composer.lock`).
- **npm**: dependências JS/CSS (`package.json`), usadas no build do Vite.

> **Conceito — lockfile:** `composer.lock`/`package-lock.json` fixam as versões
> exatas, garantindo que produção instale o mesmo que foi testado.

## Resumo visual

```
Front-end:  Blade + Tailwind (+ Vite) + JS vanilla + Livewire/Alpine
Back-end:   PHP 8.3 + Laravel 13 (Eloquent, Blade, Notifications, Scheduler)
Auth:       Laravel Breeze
Dados:      PostgreSQL (prod) / SQLite (dev)
E-mail:     Resend (API HTTP)
Push:       Web Push + VAPID
Deploy:     Railway + Nixpacks
```
