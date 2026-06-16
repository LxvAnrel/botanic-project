# 🌿 Flora — Plataforma Botânica Interativa

Sistema web para descobrir, cultivar e cuidar de plantas: catálogo, diário de
cuidados ("Diário Verde") com lembretes, motor de recomendação por quiz e
notificações (push no celular + e-mail).

🌐 Produção: https://www.florabotanic.site

## 📋 Funcionalidades

- **Catálogo** — busca e filtros (luz, porte, pet-friendly) com Livewire
- **Quiz** — recomendação personalizada em poucos passos
- **Diário Verde** — coleção pessoal de plantas favoritadas (sem reload, via AJAX)
- **Cuidados por planta** — registro de rega, adubação e poda com histórico;
  status calculado ("em dia / em breve / atrasada") e próxima data
- **Lembretes automáticos** — push + e-mail quando rega/adubação atrasa, e na época de poda
- **Notificações push** (Web Push) — no celular, com opt-in no cadastro, banner e perfil
- **E-mails transacionais** com identidade visual da marca:
  boas-vindas, alerta de segurança (novo dispositivo), recuperação de senha e alertas
- **Segurança** — HTTPS forçado, alerta de login em dispositivo novo, rate limiting

## 🛠️ Stack

- **Backend**: Laravel 13 (PHP 8.3)
- **Frontend**: Livewire 4 + Blade + Tailwind CSS (vanilla JS para interações)
- **Banco**: PostgreSQL (produção) / SQLite (local)
- **E-mail**: Resend (via API HTTP)
- **Push**: `laravel-notification-channels/webpush` (VAPID)
- **Deploy**: Railway (Nixpacks) + scheduler embutido

## 📦 Instalação local

Pré-requisitos: PHP 8.3+, Composer, Node.js/npm.

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# Banco local em SQLite (simples):
#   no .env: DB_CONNECTION=sqlite
touch database/database.sqlite

php artisan migrate --seed
npm run build
php artisan serve
```

Acesse http://localhost:8000

> Dica local: use `SESSION_DRIVER=file`, `CACHE_STORE=file` e `MAIL_MAILER=log`
> para rodar sem dependências externas.

## ⚙️ Configuração

### E-mail (Resend via API)
O Railway bloqueia portas SMTP, por isso usamos a **API HTTP** do Resend:

```env
MAIL_MAILER=resend
RESEND_API_KEY=re_xxxxxxxx
MAIL_FROM_ADDRESS=ola@florabotanic.site
MAIL_FROM_NAME=Flora
```

Remetentes por finalidade (padrões em `config/flora.php`, sobrescreviveis por env):
`seguranca@` (login), `acesso@` (senha), `alertas@` (poda/cuidados), `ola@` (boas-vindas).
Exige o domínio verificado no Resend.

Teste rápido: `php artisan mail:test seu-email@exemplo.com`

### Web Push (notificações no celular)
Gere as chaves VAPID e configure:

```env
VAPID_SUBJECT="mailto:seu-email@exemplo.com"
VAPID_PUBLIC_KEY=...
VAPID_PRIVATE_KEY=...
```

A chave **privada** nunca vai para o git — defina nas variáveis do servidor.

## ⏰ Agendamento

Comandos rodados pelo scheduler (`routes/console.php`):

```bash
php artisan plants:check-pruning-season   # alertas de poda (diário)
php artisan plants:check-care             # lembretes de rega/adubação atrasadas (09:00)
```

Em produção o scheduler roda junto ao web via `php artisan schedule:work`
(configurado no `nixpacks.toml`).

## 🗂️ Modelos e relacionamentos

```
User
  ├─ plants()          belongsToMany(Plant) — Diário Verde (com timestamps)
  ├─ careLogs()        hasMany(CareLog)      — histórico de cuidados
  ├─ knownDevices()    hasMany(KnownDevice)  — dispositivos para alerta de segurança
  ├─ pushSubscriptions() — Web Push
  └─ notifications()   — notificações no banco

Plant
  ├─ users() / careLogs()
  ├─ intervaloRega() / intervaloAdubacao()  — dias entre cuidados (com heurística)
  └─ scopes: petFriendly(), sunlight(), bySize(), search()
```

## 🔔 Notificações & e-mails

| Evento | Canal | Origem |
|---|---|---|
| Boas-vindas | e-mail | listener `Registered` |
| Login em dispositivo novo | e-mail | listener `Login` + `known_devices` |
| Recuperação de senha | e-mail | `ResetPassword` (rebrandizado) |
| Época de poda | banco + e-mail + push | `PruningSeasonNotification` |
| Rega/adubação atrasada | banco + e-mail + push | `CareReminderNotification` |

Tema de e-mail: `resources/views/vendor/mail/html/themes/flora.css`.

## 🚀 Deploy (Railway)

- Build/Start em `nixpacks.toml` (composer install, npm build, migrate, seed,
  storage:link, scheduler e `php artisan serve`).
- Domínio customizado via CNAME (`www`) + HTTPS automático (Let's Encrypt).
- Variáveis essenciais: `APP_KEY`, `APP_URL`, `DB_*` (Postgres), `MAIL_MAILER=resend`,
  `RESEND_API_KEY`, `VAPID_*`.

## 🐛 Troubleshooting

- **E-mail não envia**: confirme `MAIL_MAILER=resend` e `RESEND_API_KEY`; rode `php artisan mail:test`.
- **Push não chega**: confira as chaves `VAPID_*` e se o navegador concedeu permissão.
- **Assets**: `npm run build` (ou `npm run dev` em desenvolvimento).
- **Views/config desatualizadas**: `php artisan view:clear && php artisan config:clear`.

## 📄 Licença

MIT License.

---

**Desenvolvido com 🌱 para amantes de plantas.**
