# 08 — Gamificação

Toda a lógica vive em `app/Support/Gamification.php` (serviço estático com
constantes de configuração). Tabelas: colunas `xp`, `streak_days`,
`streak_last_date` em `users` + tabela `user_badges`.

## 1. XP (pontos de experiência)

Ações valem XP, definidas em `XP_ACTIONS`:

| Ação | XP |
|------|----|
| `plant_add` (salvar planta) | 10 |
| `care_rega` | 5 |
| `care_adubacao` | 8 |
| `care_poda` | 15 |
| `streak_bonus` (bônus diário) | 3 |

```php
public static function addXp(User $user, string $action): void {
    $amount = self::XP_ACTIONS[$action]['xp'] ?? 0;
    if ($amount > 0) $user->increment('xp', $amount);   // UPDATE atômico
}
```

`increment` faz `UPDATE users SET xp = xp + N` no banco — **atômico**, evitando
condição de corrida se duas ações ocorrerem juntas.

## 2. Níveis (derivados do XP)

`LEVELS` define faixas: Muda (0), Cultivador (100), Jardineiro (300),
Botanista (600), Mestre Verde (1000+).

```php
public static function level(int $xp): array {       // maior faixa cujo min <= xp
    $current = self::LEVELS[0];
    foreach (self::LEVELS as $l) if ($xp >= $l['min']) $current = $l;
    return $current;
}
```

`levelProgress($xp)` calcula a porcentagem dentro do nível atual e qual o próximo
— usado nas barras de progresso (perfil, conquistas, dropdown da navbar).

> **Conceito:** o nível **não** é armazenado; é uma **função pura do XP**. Menos
> estado para manter sincronizado (fonte única de verdade = `xp`).

## 3. Conquistas / badges

`BADGES` lista 11 conquistas com `slug, title, desc, icon, xp, category`.
Categorias: diário, cuidados, streak, especial. Exemplos: "Primeira Folha"
(1 planta), "Colecionador" (5), "Biodiversidade" (5 famílias), "Sequência de
Ouro" (30 dias seguidos).

### Verificação (`checkAllBadges`)
Chamada após ações relevantes. Recalcula condições e concede o que faltar:

```php
$checks = [
  'primeira-folha'  => $plantCount >= 1,
  'colecionador'    => $plantCount >= 5,
  'botanista'       => hasAllHabitats($userPlants),   // sol + meia-sombra + sombra
  'pet-lover'       => $userPlants->where('toxica_pets',false)->count() >= 3,
  'biodiversidade'  => $userPlants->pluck('familia')->unique()->count() >= 5,
  'regador-fiel'    => $streakDays >= 7,
  'sequencia-de-ouro'=> $streakDays >= 30,
  …
];
foreach ($checks as $slug => $ok) if ($ok && awardBadge($user,$slug)) $novas[] = $slug;
```

### Concessão (`awardBadge`) — idempotente
```php
if ($user->badges()->where('badge_slug',$slug)->exists()) return false; // já tem
$user->badges()->create(['badge_slug'=>$slug,'earned_at'=>now()]);
$user->increment('xp', $def['xp']);                       // XP da conquista
if ($user->email_notifications) Mail::to(...)->send(new BadgeEarnedMail(...)); // try/catch
return true;
```

O índice único `(user_id, badge_slug)` + a checagem de existência garantem que a
mesma conquista **nunca** é concedida duas vezes (idempotência).

## 4. Sequência (streak)

Conta dias **consecutivos** com algum cuidado registrado.

```php
public static function updateStreak(User $user): void {
    $hoje = today(); $ontem = yesterday(); $ultimo = $user->streak_last_date;
    if ($ultimo == $hoje)  return;                 // já contou hoje
    if ($ultimo == $ontem) { $user->increment('streak_days'); addXp($user,'streak_bonus'); }
    else                   { $user->streak_days = 1; }   // quebrou a sequência
    $user->streak_last_date = $hoje; $user->save();
}
```

- Cuidou ontem e hoje → +1 na sequência e bônus de XP.
- Pulou um dia → reinicia em 1.
- Já cuidou hoje → não conta de novo (evita inflar).

Um comando agendado (`CheckStreakAtRisk`) avisa por e-mail
(`StreakAtRiskMail`) quem está prestes a perder a sequência.

## 5. Integração com o resto do sistema

Ao registrar um cuidado (`CareController::store`):
```php
Gamification::addXp($user, 'care_'.$tipo);
Gamification::updateStreak($user);
Gamification::checkAllBadges($user);
```
Assim, uma única ação do usuário pode render XP + avançar streak + desbloquear
conquista — e a UI reflete tudo (a página de Conquistas usa
`allBadgesWithStatus` e `levelProgress`).

## 6. Como eu faria na mão

- **XP:** coluna inteira; `UPDATE … SET xp = xp + ?` (atômico).
- **Nível:** função que mapeia faixa por comparação (sem coluna).
- **Badges:** tabela com único `(user, badge)`; verificar condição e inserir só
  se não existir.
- **Streak:** comparar `ultima_data` com ontem/hoje. Toda a lógica é aritmética
  simples — independe de framework.
