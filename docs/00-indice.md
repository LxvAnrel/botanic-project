# Estudo Técnico da Plataforma Flora — Índice

Esta documentação descreve, em nível acadêmico e micro, **como cada parte do
sistema Flora funciona** — a ponto de você conseguir reconstruir um sistema
equivalente do zero. Cada arquivo cobre um módulo ou um conjunto de conceitos.

## Ordem de leitura sugerida

| # | Documento | O que você aprende |
|---|-----------|--------------------|
| 01 | [Visão geral e arquitetura](01-visao-geral-e-arquitetura.md) | O que é o sistema, padrão MVC, ciclo de vida de uma requisição HTTP, organização de pastas |
| 02 | [Stack tecnológica](02-stack-tecnologica.md) | Cada tecnologia (Laravel, Livewire, Tailwind, PostgreSQL…), o papel e o porquê de cada uma |
| 03 | [Modelagem de dados](03-modelagem-de-dados.md) | Todas as tabelas, relacionamentos, migrations, normalização, Eloquent ORM |
| 04 | [Autenticação e segurança](04-autenticacao-e-seguranca.md) | Sessões, hashing de senha, CSRF, rate limiting, alerta de dispositivo, HTTPS, headers |
| 05 | [Catálogo e Quiz (Livewire)](05-catalogo-e-quiz-livewire.md) | Programação reativa server-side, componentes, o algoritmo de recomendação |
| 06 | [Diário Verde e cuidados](06-diario-verde-e-cuidados.md) | Relação N:N, registro de cuidados, cálculo de status, interações AJAX sem reload |
| 07 | [Notificações: push e e-mail](07-notificacoes-push-email.md) | Sistema de notificações multicanal, Web Push/VAPID, Mailables, tema de e-mail |
| 08 | [Gamificação](08-gamificacao.md) | XP, níveis, conquistas (badges), sequência (streak) |
| 09 | [Comunidade e perfil público](09-comunidade-e-perfil-publico.md) | Ranking, perfis públicos, armazenamento de avatar |
| 10 | [Agendamento, comandos e jobs](10-agendamento-comandos-jobs.md) | Task scheduling, comandos Artisan, processos em segundo plano |
| 11 | [Implantação (Railway) e domínio](11-implantacao-railway-dominio.md) | Build, variáveis de ambiente, DNS, HTTPS, armazenamento efêmero |
| 12 | [Glossário de conceitos](12-glossario-conceitos.md) | Definições acadêmicas dos termos usados |

## Convenções

- Trechos de código referenciam arquivos reais do projeto (caminho relativo à raiz).
- "A nível micro" = explicamos o fluxo passo a passo, não só o conceito.
- Cada documento tem uma seção **"Como eu faria na mão"** com o raciocínio de
  reimplementação independente do framework.

## Mapa mental do sistema

```
Visitante ─┬─ Catálogo (Livewire) ── detalhe da planta
           ├─ Quiz (Livewire) ── recomendação
           └─ Cadastro/Login (Breeze)
                    │
              Usuário autenticado
                    │
   ┌────────────────┼─────────────────────────────┐
   │                │                              │
 Diário Verde   Cuidados (rega/adubação/poda)   Gamificação (XP/níveis/badges)
   │                │                              │
   └── Notificações (banco + e-mail + push) ◄──────┘
                    │
        Comunidade / Perfil público
```
