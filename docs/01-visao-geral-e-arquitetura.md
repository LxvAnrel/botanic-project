# 01 — Visão geral e arquitetura

## 1. O que é o sistema

A **Flora** é uma aplicação web (monolito server-side) que funciona como:

- **Catálogo** de plantas com busca e filtros;
- **Quiz** que recomenda uma planta ao usuário;
- **Diário Verde**: coleção pessoal de plantas + registro de cuidados (rega,
  adubação, poda) com lembretes;
- **Gamificação**: pontos de experiência (XP), níveis, conquistas e sequência;
- **Comunidade**: ranking e perfis públicos;
- **Notificações** multicanal (no site, e-mail e push no celular).

É um **trabalho escolar** (ETEC), mas implementado com práticas profissionais.

## 2. Paradigma arquitetural: MVC + camadas auxiliares

O sistema segue o padrão **MVC (Model-View-Controller)**, que separa
responsabilidades:

- **Model** (`app/Models/`): representa os dados e as regras ligadas a eles
  (ex.: `Plant`, `User`, `CareLog`). Conversa com o banco via ORM.
- **View** (`resources/views/`): a apresentação (HTML gerado por templates Blade).
- **Controller** (`app/Http/Controllers/`): recebe a requisição, orquestra
  models e devolve uma view ou resposta.

Sobre o MVC clássico, o projeto adiciona camadas auxiliares comuns no ecossistema Laravel:

| Camada | Pasta | Papel |
|--------|-------|-------|
| **Componentes reativos** | `app/Livewire/` | UI dinâmica sem escrever JavaScript (catálogo, quiz) |
| **Serviços / regras de domínio** | `app/Support/` | Lógica pura reutilizável (`Gamification`, `PlantCare`) |
| **Notifications** | `app/Notifications/` | Mensagens multicanal |
| **Mailables** | `app/Mail/` | E-mails transacionais |
| **Listeners** | `app/Listeners/` | Reagem a eventos (ex.: login, cadastro) |
| **Commands** | `app/Console/Commands/` | Tarefas executadas via terminal/cron |
| **Middleware** | `app/Http/Middleware/` | Filtros aplicados às requisições |

> **Conceito acadêmico — Separação de responsabilidades (SoC):** cada parte tem
> um único motivo para mudar. Isso reduz acoplamento e facilita manutenção e teste.

## 3. Ciclo de vida de uma requisição HTTP (a nível micro)

Quando o navegador acessa, por exemplo, `GET /catalogo`:

1. **Servidor web** (no deploy, `php artisan serve`) recebe a requisição e a
   encaminha para `public/index.php` — o **front controller** (ponto único de entrada).
2. `index.php` carrega o **autoloader** do Composer e o **kernel HTTP** do Laravel
   (`bootstrap/app.php`).
3. O kernel executa a pilha de **middlewares globais** (ex.: `SecurityHeaders`,
   tratamento de sessão, verificação de CSRF em POSTs).
4. O **roteador** (`routes/web.php`) casa a URL com uma rota e identifica o
   controller/closure responsável.
5. Middlewares **de rota** rodam (ex.: `auth` para áreas logadas).
6. O **controller** executa: consulta models (via Eloquent), aplica regras e
   escolhe uma **view**.
7. O motor **Blade** compila o template em PHP puro (cache) e gera o HTML.
8. A resposta volta subindo a pilha de middlewares (que podem adicionar headers)
   até o navegador.

```
Navegador → index.php → Kernel → Middlewares → Router → Controller → Model(ORM) → DB
                                                              │
                                                            View (Blade) → HTML → Navegador
```

> Em páginas com **Livewire** (catálogo/quiz), há um segundo ciclo: interações do
> usuário disparam requisições AJAX que reexecutam **apenas o componente** e
> devolvem HTML parcial. Ver [doc 05](05-catalogo-e-quiz-livewire.md).

## 4. Organização de diretórios (essencial)

```
app/
  Http/Controllers/   → controllers (web e auth)
  Http/Middleware/     → SecurityHeaders (headers + força HTTPS)
  Livewire/            → PlantCatalog, PlantQuiz
  Models/              → Plant, User, CareLog, KnownDevice, UserBadge
  Support/             → Gamification, PlantCare (regras de domínio)
  Notifications/       → PruningSeason, CareReminder
  Mail/                → WelcomeMail, NewDeviceAlertMail, ...
  Listeners/           → SendWelcomeEmail, CheckLoginDevice
  Console/Commands/     → CheckPruningSeason, CheckCareReminders, ...
bootstrap/app.php      → configuração do app (rotas, middleware, agendamento)
config/                → arquivos de configuração (mail, webpush, flora, ...)
database/migrations/   → definição evolutiva do schema do banco
database/seeders/      → dados iniciais (PlantSeeder)
resources/views/       → templates Blade
resources/css|js/      → fontes do front-end (compilados por Vite)
routes/web.php         → rotas web
routes/auth.php        → rotas de autenticação (Breeze)
routes/console.php     → agendamento de comandos
public/                → raiz pública (index.php, assets compilados, sw.js)
lang/pt_BR/            → traduções (mensagens de erro em português)
```

## 5. Princípios de projeto presentes

- **Convenção sobre configuração**: o Laravel infere muita coisa por nomes
  (ex.: model `Plant` ↔ tabela `plants`).
- **Fat models / thin controllers (parcial)**: regras complexas vão para
  `app/Support/` (serviços) em vez de inchar controllers.
- **Idempotência e resiliência**: envio de e-mail nunca derruba login/cadastro
  (try/catch); lembretes têm deduplicação.
- **Progressive enhancement**: o site funciona sem JS para o essencial; push e
  algumas interações são "camadas extras".

## 6. Como eu faria na mão

Para reconstruir esta arquitetura sem Laravel, eu precisaria de:

1. Um **front controller** (`index.php`) que recebe todas as requisições.
2. Um **roteador** que mapeia método+caminho → função.
3. Uma camada de **acesso a dados** (ex.: PDO) encapsulada em classes "model".
4. Um **motor de templates** (ou `include` com escape de HTML para evitar XSS).
5. **Middlewares** como uma lista de funções aplicadas antes/depois do handler.
6. **Sessão** e **CSRF** implementados manualmente (cookie de sessão + token por
   formulário). É exatamente isso que o framework já entrega pronto e testado.
