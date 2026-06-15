# 🚀 Guia Rápido de Configuração

## Windows (PowerShell)

### 1️⃣ Instalar dependências

```powershell
# Entrar no diretório
cd "C:\Users\Uvits\Downloads\plataforma-botanica-interativa"

# PHP e Composer (se não tiver instalado)
# Baixe em: https://getcomposer.org/download/

# Instalar dependências PHP
composer install

# Instalar dependências Node
npm install
```

### 2️⃣ Configurar banco de dados

#### Opção A: MySQL
```bash
# Criar banco (no MySQL)
CREATE DATABASE plataforma_botanica CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Editar .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plataforma_botanica
DB_USERNAME=root
DB_PASSWORD=sua_senha
```

#### Opção B: SQLite (mais simples)
```bash
# Editar .env
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
```

### 3️⃣ Executar setup

```bash
# Gerar APP_KEY
php artisan key:generate

# Criar tabelas
php artisan migrate

# Popular com dados de exemplo
php artisan db:seed
```

### 4️⃣ Build dos assets

```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 5️⃣ Rodar servidor

```bash
# Em um terminal PowerShell
php artisan serve

# Abre em http://localhost:8000
```

## 🔧 Configuração Avançada

### Agendador de tarefas (Linux/Mac)

```bash
# Adicionar ao crontab
crontab -e

# Adicionar linha:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Agendador de tarefas (Windows)

Usar Task Scheduler:
1. Abrir `Agendador de Tarefas`
2. Criar tarefa que executa a cada 1 minuto:
   ```
   php artisan schedule:run
   ```

## ✅ Verificação Pós-Instalação

```bash
# Testar PHP
php -v

# Testar Composer
composer --version

# Testar Node
node -v
npm -v

# Testar Laravel
php artisan --version

# Testar banco
php artisan tinker
>>> DB::connection()->getPdo();
```

## 🌐 Primeira visita

1. Abra http://localhost:8000
2. Clique em **Registrar** para criar conta
3. Explore o **Catálogo** de plantas
4. Faça o **Quiz** para descobrir sua planta ideal
5. Adicione plantas ao seu **Diário Verde**

## 🆘 Problemas Comuns

### "Class 'Livewire' not found"
```bash
composer require livewire/livewire
php artisan livewire:publish
```

### "SQLSTATE[HY000]: General error"
```bash
php artisan migrate:refresh
php artisan db:seed
```

### Assets não carregando (CSS/JS)
```bash
npm run build
# Ou em desenvolvimento:
npm run dev
```

### Porta 8000 já em uso
```bash
# Usar outra porta:
php artisan serve --port=8080
```

## 📊 Estrutura de pastas importantes

```
├── app/
│   ├── Models/Plant.php          ← Modelo de planta
│   ├── Livewire/                 ← Componentes interativos
│   └── Console/Commands/          ← Comandos agendados
├── database/
│   ├── migrations/               ← Estrutura das tabelas
│   └── seeders/                  ← Dados iniciais
├── resources/
│   ├── views/                    ← Templates HTML
│   └── css/                      ← Estilos
└── routes/
    └── web.php                   ← Definição de rotas
```

## 🎯 Próximos Passos

- [ ] Instalar dependências
- [ ] Configurar banco de dados
- [ ] Executar migrations
- [ ] Popular com seeders
- [ ] Rodar servidor
- [ ] Testar catálogo
- [ ] Fazer quiz
- [ ] Registrar usuário

---

**Pronto! Seu sistema está configurado e pronto para uso! 🎉**
