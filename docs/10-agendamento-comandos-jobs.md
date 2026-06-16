# 10 — Agendamento, comandos e processos em segundo plano

## 1. Comandos Artisan

Um *command* (`app/Console/Commands/`) é uma classe com `$signature` (nome) e
`handle()` (o que faz). Roda no terminal: `php artisan <signature>`.

Comandos do projeto:

| Comando | Papel |
|---------|-------|
| `plants:check-pruning-season` | Verifica a estação atual e notifica plantas em época de poda |
| `plants:check-care` | Notifica regas/adubações atrasadas |
| `CheckStreakAtRisk` | Avisa quem está prestes a perder a sequência |
| `PurgeDeletedAccounts` | Remove contas após 30 dias de exclusão agendada |

## 2. Anatomia de `plants:check-pruning-season`

```php
foreach (User::with('plants')->get() as $user)
  foreach ($user->plants as $plant)
    if ($plant->isPruningSeason($estacaoAtual) && !$jaNotificado)
        $user->notify(new PruningSeasonNotification($plant, $estacaoAtual));
```

- `getSeason()` deriva a estação do mês (Hemisfério Sul).
- `isPruningSeason()` checa se a estação está em `epoca_poda` (JSON da planta).
- **Deduplicação:** antes de notificar, verifica se já existe uma notificação do
  mesmo tipo, planta e estação nos últimos 60 dias — assim rodar o comando todo
  dia não gera spam.

### Deduplicação sem operador JSON no SQL
A coluna `data` de `notifications` é texto; o operador JSON do PostgreSQL não
funciona nela. A solução: buscar as notificações recentes do tipo e filtrar em
**PHP** (`->get()->contains(fn …)` comparando `data['planta_nome']` e
`data['season']`). (Foi um bug real: usar `where('data->season', …)` quebrava no
Postgres.)

## 3. `plants:check-care` (lembretes de cuidado)

Para cada planta de cada usuário, calcula o status de rega e adubação
(`PlantCare::status`); se `atrasado` e ainda não lembrado **neste ciclo**, envia
`CareReminderNotification`. O "ciclo" é definido comparando a data do último
cuidado: uma vez que o usuário registra o cuidado, a janela reinicia e um novo
lembrete pode ser enviado depois.

## 4. Task Scheduling (cron expresso em PHP)

`routes/console.php`:

```php
Schedule::command('plants:check-pruning-season')->daily();
Schedule::command('plants:check-care')->dailyAt('09:00');
```

O Laravel tem **um** ponto de entrada de cron (`schedule:run`, idealmente a cada
minuto) que decide quais comandos rodar conforme a expressão (`daily`,
`dailyAt`…). **Por quê:** você versiona o agendamento em código, sem editar
crontab do servidor.

### No Railway: `schedule:work`
Como não há crontab gerenciável, o `nixpacks.toml` inicia, junto ao servidor web,
o processo `php artisan schedule:work` (um *loop* que cumpre o papel do cron):

```
… && php artisan schedule:work & php artisan serve --host=0.0.0.0 --port=$PORT
```

O `&` roda o agendador em segundo plano; o `serve` fica em primeiro plano
mantendo o contêiner vivo.

## 5. Filas (conceito) e por que aqui é síncrono

O Laravel suporta **filas** (`jobs`) para processar tarefas pesadas fora do ciclo
da requisição. Neste projeto, as notificações **não** implementam `ShouldQueue`,
então são enviadas **sincronamente** (na hora). Decisão consciente: não há um
*worker* de fila garantido no Railway, e os envios são leves o suficiente. Para
proteger a experiência, os envios em listeners ficam em try/catch.

> **Conceito — trabalho assíncrono:** mover tarefas demoradas (e-mail, push,
> processamento) para uma fila evita que o usuário espere. O trade-off é precisar
> de um processo *worker* rodando continuamente.

## 6. Como eu faria na mão

- **Comando:** um script PHP de CLI (`php meu_script.php`) lendo argumentos.
- **Agendamento:** uma entrada no `crontab` do SO (`0 9 * * * php …`).
- **Loop alternativo (sem cron):** um processo que dorme e executa em intervalo
  (é o que `schedule:work` faz).
- **Fila:** uma tabela de "tarefas pendentes" + um *worker* que faz *polling*,
  executa e marca como concluída.
