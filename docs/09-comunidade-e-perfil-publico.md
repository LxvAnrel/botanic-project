# 09 — Comunidade e perfil público

Controller: `app/Http/Controllers/PublicProfileController.php`. Rotas públicas
(`/comunidade`, `/u/{user}`).

## 1. Ranking da comunidade (`community`)

```php
$top = User::where('profile_public', true)
    ->whereNull('deletion_scheduled_at')   // não lista contas em exclusão
    ->orderByDesc('xp')
    ->take(10)->get()
    ->map(function (User $u) {
        $u->levelData    = Gamification::level((int) $u->xp);
        $u->badgesEarned = $u->badges()->count();
        return $u;
    });
```

- Só aparecem usuários que **optaram** por perfil público (`profile_public`).
- Ordenado por XP (decrescente), top 10.
- Cada usuário recebe, em memória, o nível e a contagem de conquistas para a view.

> **Privacidade desde o projeto (privacy by design):** o ranking é *opt-in*. Quem
> não marca o perfil como público não é exposto.

## 2. Perfil público (`show`)

```php
if (! $user->profile_public || $user->deletion_scheduled_at) abort(404);
$badges   = Gamification::allBadgesWithStatus($user);
$progress = Gamification::levelProgress((int) $user->xp);
$plants   = $user->plants()->take(6)->get();
```

- Perfil privado ou em exclusão → **404** (não revela existência).
- Mostra nível/progresso, conquistas (com obtidas x bloqueadas) e até 6 plantas.
- *Route model binding*: `{user}` é resolvido automaticamente para o model `User`.

## 3. Armazenamento de avatar (decisão de arquitetura)

O upload (`ProfileController::uploadAvatar`) valida a imagem (`image`,
`max:2048`, `mimes:jpg,jpeg,png,webp`) e então:

1. **Se `CLOUDINARY_URL` existir** → envia ao **Cloudinary** (serviço externo),
   guarda a URL segura em `avatar_path`.
2. **Senão** → guarda a imagem **no próprio banco**: `avatar_data` (conteúdo em
   base64) + `avatar_mime`.

A leitura é unificada pelo *accessor* `getAvatarUrlAttribute` no `User`:

```php
if ($this->avatar_path && str_starts_with($this->avatar_path,'http')) return $this->avatar_path; // Cloudinary
if ($this->avatar_data) return route('avatar.show', $this);     // servido pelo banco
if ($this->avatar_path) return asset('storage/'.$this->avatar_path); // disco local (dev)
return null;
```

E a rota pública `GET /avatar/{user}` (`ProfileController::showAvatar`) devolve a
imagem decodificada com cabeçalho de cache:

```php
return response(base64_decode($user->avatar_data))
    ->header('Content-Type', $user->avatar_mime ?: 'image/jpeg')
    ->header('Cache-Control', 'public, max-age=86400');
```

### Por que guardar no banco?
A plataforma (Railway) tem **sistema de arquivos efêmero**: arquivos enviados são
apagados a cada *deploy*. Guardar no PostgreSQL (que persiste) resolve sem
depender de serviço externo. O `Cache-Control` evita rebaixar performance (o
navegador cacheia a imagem por 24h).

> **Trade-off:** guardar binário no banco é simples e persistente, porém menos
> escalável que um *object storage* (S3/Cloudinary). Para um projeto escolar com
> avatares ≤ 2 MB, é a escolha pragmática. O código mantém o Cloudinary como
> opção plugável.

## 4. Views envolvidas

- `resources/views/public/community.blade.php` — pódio (top 3) + lista 4–10.
- `resources/views/public/profile.blade.php` — perfil individual.
- Ambas exibem o avatar via `$user->avatar_url` (que abstrai a origem).

## 5. Como eu faria na mão

- **Ranking:** `SELECT … WHERE profile_public = true ORDER BY xp DESC LIMIT 10`.
- **Perfil:** rota `/u/{id}`; 404 se privado.
- **Avatar no banco:** coluna `BYTEA`/`LONGTEXT` + um endpoint que faz `echo` dos
  bytes com `Content-Type`. É literalmente o que `showAvatar` faz.
