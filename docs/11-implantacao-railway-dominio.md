# 11 — Implantação (Railway) e domínio

## 1. Railway + Nixpacks

**Railway** é a PaaS que hospeda o app. **Nixpacks** é o construtor: lê
`nixpacks.toml`, monta a imagem e roda o app.

`nixpacks.toml`:
```toml
[phases.setup]
nixPkgs = ["php83", "php83Extensions.pdo_pgsql", "...mbstring/xml/curl/zip/tokenizer/fileinfo", "composer"]

[phases.build]
cmds = [
  "composer install --no-dev --optimize-autoloader --no-interaction",
  "npm ci && npm run build",
]

[start]
cmd = "mkdir -p storage/... && php artisan migrate --force && php artisan db:seed --class=PlantSeeder --force && php artisan storage:link --force && php artisan schedule:work & php artisan serve --host=0.0.0.0 --port=$PORT"
```

Fases:
- **setup:** instala runtime (PHP + extensões) e o Composer.
- **build:** instala dependências PHP (sem dev, autoloader otimizado) e compila
  os assets do front (Vite).
- **start:** prepara pastas de storage, **migra** o banco, **semeia** as plantas,
  cria o link de storage, sobe o **agendador** e o **servidor web**.

> **Conceito — build vs runtime:** o que é determinístico (instalar libs,
> compilar CSS) acontece no build; o que depende do ambiente (migrar o banco de
> produção, servir) acontece no start.

## 2. Variáveis de ambiente (12-Factor)

Configuração sensível e específica de ambiente **não** vai no código — vai em
variáveis de ambiente (painel do Railway). `.env.example` documenta as chaves.
Principais:

| Variável | Função |
|----------|--------|
| `APP_KEY` | chave de criptografia/sessão (obrigatória) |
| `APP_URL` | base das URLs geradas |
| `DB_CONNECTION=pgsql` + `DB_*` | conexão PostgreSQL |
| `MAIL_MAILER=resend` + `RESEND_API_KEY` | e-mail via API |
| `VAPID_PUBLIC_KEY/PRIVATE_KEY/SUBJECT` | Web Push |
| `CLOUDINARY_URL` | (opcional) avatares no Cloudinary |
| `SESSION_DRIVER=database`, `CACHE_STORE=database` | sessão/cache no banco |

> **Princípio 12-Factor:** o mesmo artefato roda em qualquer ambiente; só as
> variáveis mudam. Por isso `config/*` lê tudo via `env(...)`.

## 3. Banco gerenciado

O PostgreSQL é um *serviço* do Railway; as `DB_*` apontam para ele (host/porta/
credenciais fornecidos pelo provedor). O `migrate --force` no start garante o
schema atualizado a cada deploy.

## 4. Domínio e HTTPS

- O domínio (`florabotanic.site`, GoDaddy) aponta para o Railway via **CNAME** no
  subdomínio `www` (apontando para o alvo que o Railway fornece). O **apex**
  (raiz) não aceita CNAME no DNS — usa-se *forwarding* da GoDaddy para `www`.
- O **certificado TLS** é emitido **automaticamente** pelo Railway (Let's Encrypt)
  assim que o domínio é validado. Não há emissão manual.
- O app **força HTTPS** (ver [doc 04](04-autenticacao-e-seguranca.md)).

> **Conceito — apex vs subdomínio:** o registro `CNAME` não pode coexistir com os
> registros obrigatórios do apex (SOA/NS); por isso CNAME só em subdomínios. Para
> a raiz usa-se ALIAS/ANAME (quando o provedor suporta) ou redirecionamento.

## 5. Armazenamento efêmero (consequência importante)

O sistema de arquivos do contêiner é **descartado a cada deploy**. Por isso:
- Sessão e cache ficam **no banco** (não em arquivo).
- Avatares ficam **no banco** (não em `storage/`), ver [doc 09](09-comunidade-e-perfil-publico.md).
- Uploads que precisem persistir exigem *object storage* externo ou *volume*.

## 6. Observabilidade

Logs do deploy/runtime ficam no painel do Railway. O endpoint de saúde `/up`
(configurado em `bootstrap/app.php`) permite checagem de disponibilidade.

## 7. Pipeline resumido

```
git push → Railway detecta → Nixpacks build (composer + npm) →
start (migrate + seed + storage:link + scheduler + serve) → app no ar (HTTPS)
```

## 8. Como eu faria na mão (VPS tradicional)

1. Servidor com PHP-FPM + Nginx; `root` apontando para `public/`.
2. `git pull` + `composer install --no-dev` + `npm run build`.
3. `php artisan migrate --force`.
4. Cron: `* * * * * php artisan schedule:run`.
5. Certbot para TLS (Let's Encrypt).
6. Variáveis no `.env` do servidor. O Railway automatiza tudo isso.
