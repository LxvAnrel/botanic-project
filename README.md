# Flora — Catálogo de Plantas ETEC

Sistema web para catalogar plantas: catálogo com busca e filtros, quiz de recomendação, diário de cuidados e notificações.

**Stack:** Laravel 13 · PHP 8.3 · Livewire · Tailwind CSS · SQLite

## Como rodar localmente

**Pré-requisitos:** PHP 8.3+ e Composer instalados.

```bash
# 1. Instalar dependências PHP (se a pasta vendor não estiver presente)
composer install

# 2. Rodar o servidor
php artisan serve
```

Acesse **http://localhost:8000**

> Os assets front-end já estão compilados em `public/build/` — não é necessário rodar npm.
> O banco SQLite já está em `database/database.sqlite` com os dados.

## Caso precise recriar o banco

```bash
php artisan migrate:fresh --seed
```

## Credenciais de admin (padrão após seed)

Verifique o arquivo `database/seeders/` para os usuários criados pelo seeder.
