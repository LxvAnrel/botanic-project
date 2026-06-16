# 03 — Modelagem de dados

## 1. ORM e o conceito de "model"

O **Eloquent** é o ORM (Object-Relational Mapping) do Laravel: cada tabela vira
uma classe que estende `Model`. Uma instância = uma linha; propriedades =
colunas. **Por quê:** trabalhar com objetos PHP em vez de escrever SQL à mão,
com proteção contra injeção de SQL e relacionamentos expressos como métodos.

Convenções: model `Plant` → tabela `plants`; chave primária `id`; timestamps
`created_at`/`updated_at` automáticos.

## 2. Migrations: o schema versionado

Cada arquivo em `database/migrations/` descreve uma alteração no banco (criar
tabela, adicionar coluna). Rodadas em ordem cronológica (prefixo de data).
**Conceito:** o schema é *código versionado* — qualquer ambiente reproduz a mesma
estrutura com `php artisan migrate`. O *rollback* (`down()`) desfaz a alteração.

## 3. Entidades e tabelas

### `users`
Pessoa cadastrada. Colunas relevantes (acumuladas por várias migrations):
`id, name, email, password, email_verified_at, email_notifications, xp,
streak_days, streak_last_date, avatar_path, avatar_data, avatar_mime, bio,
profile_public, deletion_scheduled_at, remember_token, timestamps`.

- `password` é **hash** (cast `hashed`), nunca texto puro.
- `xp`, `streak_*` → gamificação ([doc 08](08-gamificacao.md)).
- `avatar_data`/`avatar_mime` → foto guardada no banco ([doc 09](09-comunidade-e-perfil-publico.md)).
- `profile_public` → controla exposição na comunidade.
- `deletion_scheduled_at` → exclusão com período de reativação (LGPD).

### `plants`
Espécie do catálogo: `id, nome_popular, nome_cientifico (único), slug, familia,
genero, especie, origem, habitat_luz (enum: sol_pleno|meia_sombra|sombra),
dias_entre_regas, dias_entre_adubacoes, porte_max_cm, toxica_pets (bool),
epoca_poda (JSON array de estações), beneficios, maleficios, curiosidades,
image_path, timestamps`.

- `epoca_poda` é **JSON** (cast `array`) — guarda uma lista (`["outono","inverno"]`).
- `slug` é gerado a partir do nome popular no evento `creating` do model.

### `plant_user` (tabela pivô — relação N:N)
Liga usuários a plantas (o Diário Verde): `id, user_id, plant_id, timestamps`,
com **índice único** `(user_id, plant_id)` (um usuário não salva a mesma planta
duas vezes). `timestamps` no pivô permitem mostrar "salva há X dias".

### `care_logs`
Histórico de cuidados: `id, user_id, plant_id, tipo (rega|adubacao|poda),
data (date), timestamps`, índice `(user_id, plant_id, tipo)`.

### `notifications`
Tabela padrão do Laravel (UUID): `id (uuid), type, notifiable_type,
notifiable_id, data (JSON/text), read_at, timestamps`. Guarda as notificações
exibidas em `/alertas`.

### `push_subscriptions`
Inscrições de Web Push (do pacote): `id, subscribable_type/id (morph),
endpoint (único), public_key, auth_token, content_encoding, timestamps`.

### `known_devices`
Dispositivos conhecidos para o alerta de segurança: `id, user_id, device_hash,
ip, user_agent, last_seen_at, timestamps`, único `(user_id, device_hash)`.

### `user_badges`
Conquistas obtidas: `id, user_id, badge_slug, earned_at`, único
`(user_id, badge_slug)`.

### Tabelas de infraestrutura
`cache`, `jobs`, `sessions` (sessão em banco), criadas pelas migrations base.

## 4. Diagrama de relacionamentos (ER simplificado)

```
users 1 ──< plant_user >── 1 plants          (N:N: Diário Verde)
users 1 ──────< care_logs >────── 1 plants    (histórico de cuidados)
users 1 ──< user_badges                        (conquistas)
users 1 ──< known_devices                      (segurança)
users 1 ──< push_subscriptions (morph)         (push)
users 1 ──< notifications (morph "notifiable")  (alertas)
```

## 5. Relacionamentos no Eloquent (a nível micro)

Em `app/Models/User.php`:

```php
public function plants()   // N:N com pivô + timestamps
{ return $this->belongsToMany(Plant::class, 'plant_user')->withTimestamps(); }

public function careLogs() { return $this->hasMany(CareLog::class); }   // 1:N
public function badges()   { return $this->hasMany(UserBadge::class); } // 1:N
public function knownDevices() { return $this->hasMany(KnownDevice::class); }
```

- **`belongsToMany`**: relação muitos-para-muitos; o Eloquent usa a pivô
  `plant_user`. `withTimestamps()` mantém `created_at` no pivô — necessário para
  exibir `$planta->pivot->created_at`. (Esquecer isso foi um bug real corrigido.)
- **`hasMany`**: um usuário tem vários registros de cuidado/conquista.
- **`morphMany`** (push) e **morph** (notifications): relação **polimórfica** —
  a mesma tabela pode pertencer a tipos diferentes de "dono".

> **Conceito — relação polimórfica:** em vez de uma FK fixa, guarda-se o *tipo*
> (`notifiable_type = App\Models\User`) e o *id*. Permite reusar a tabela para
> qualquer model.

## 6. Mass assignment e proteção

`$fillable` no model lista quais colunas podem ser preenchidas em massa
(`Model::create($array)`). **Por quê:** evita *mass assignment vulnerability* —
um formulário malicioso não consegue setar colunas sensíveis não listadas.

## 7. Casts

`casts()` converte tipos ao ler/gravar: `password => hashed`,
`epoca_poda => array` (JSON ↔ array PHP), `streak_last_date => date`,
`data => date` em `CareLog`. **Por quê:** trabalhar com tipos PHP nativos em vez
de strings.

## 8. Escopos de consulta (query scopes)

`Plant` define *scopes* reutilizáveis:

```php
public function scopePetFriendly($q) { return $q->where('toxica_pets', false); }
public function scopeSunlight($q,$l) { return $q->where('habitat_luz',$l); }
public function scopeBySize($q,$cm)  { return $q->where('porte_max_cm','<=',$cm); }
public function scopeSearch($q,$t)   { return $q->where('nome_popular','like',"%$t%")->orWhere(...); }
```

Uso: `Plant::petFriendly()->sunlight('sombra')->get()`. **Conceito:** encapsular
condições nomeadas, deixando o código de consulta legível e DRY.

## 9. Como eu faria na mão

- Escreveria SQL `CREATE TABLE` para cada entidade e usaria **PDO** com
  *prepared statements* (placeholders) para evitar SQL injection.
- Para N:N, criaria a tabela de junção com FK e índice único.
- Para "migrations", manteria scripts numerados aplicados em ordem e uma tabela
  `migrations` registrando o que já rodou (exatamente o que o Laravel faz).
- Os "scopes" virariam métodos que concatenam fragmentos de `WHERE`.
