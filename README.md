# 🌿 Plataforma Botânica Interativa

Um sistema web inteligente que funciona como catálogo de plantas, diário de cuidados ("Diário Verde") e motor de recomendação através de Quiz.

## 📋 Características

✅ **Catálogo Inteligente** - Browse plantas com filtros dinâmicos
✅ **Diário Verde** - Gerencie sua coleção pessoal de plantas  
✅ **Quiz Interativo** - Descubra a planta perfeita para você
✅ **Notificações** - Alertas automáticos de época de poda
✅ **Busca Avançada** - Busque por nome, espécie ou família
✅ **Responsivo** - Interface moderna com Tailwind CSS

## 🛠️ Stack Técnico

- **Backend**: Laravel 11
- **Frontend**: Livewire 4 + Alpine.js
- **Banco de Dados**: MySQL/SQLite
- **UI**: Tailwind CSS
- **Agendamento**: Laravel Task Scheduling

## 📦 Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js e npm

### Passos

1. **Navegue até o projeto**
```bash
cd plataforma-botanica-interativa
```

2. **Instale as dependências PHP**
```bash
composer install
```

3. **Instale as dependências Node**
```bash
npm install
```

4. **Configure o banco de dados**
Edite `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=plataforma_botanica
DB_USERNAME=root
DB_PASSWORD=
```

5. **Execute as migrations e seeders**
```bash
php artisan migrate
php artisan db:seed
```

6. **Build dos assets**
```bash
npm run build
```

7. **Inicie o servidor**
```bash
php artisan serve
```

Acesse em: http://localhost:8000

## 🚀 Principais Funcionalidades

### Catálogo de Plantas
- 100+ plantas cadastradas
- Filtros dinâmicos (luz, tamanho, pet-friendly)
- Busca por nome popular, científico ou família
- Detalhes completos de cada planta

### Quiz Interativo
- 4 passos para descobrir a planta perfeita
- Sistema de pontuação inteligente
- Recomendação personalizada

### Meu Diário Verde
- Adicione plantas ao seu acervo pessoal
- Gerencie suas plantas favoritas
- Acompanhe épocas de poda

### Alertas Inteligentes
- Notificações automáticas de poda
- Histórico de alertas
- Sistema agendado (cron jobs)

## ⚙️ Agendamento (Cron Jobs)

Para ativar as verificações automáticas de poda:

```bash
php artisan plants:check-pruning-season
```

## 🔐 Autenticação

Use Laravel Breeze:
- Registre em `/register`
- Faça login em `/login`
- Gerencie sua conta em `/perfil`

## 📝 Modelos e Relacionamentos

```
User
  ├─ plants() [belongsToMany Plant]
  └─ notifications() [hasMany Notification]

Plant
  ├─ users() [belongsToMany User]
  └─ scopes:
      - petFriendly()
      - sunlight(level)
      - bySize(cm)
      - search(term)
```

## 📚 Componentes Livewire

- `PlantCatalog` - Grid com filtros dinâmicos
- `PlantQuiz` - Wizard interativo de 4 passos

## 🐛 Troubleshooting

**Erro de conexão com banco**
```bash
php artisan migrate
php artisan db:seed
```

**Assets não carregando**
```bash
npm run dev
```

**Composer sem memória**
```bash
php -d memory_limit=2G /usr/bin/composer install
```

## 👨‍💻 Comandos Úteis

```bash
# Criar migration
php artisan make:migration nome_migration

# Seed do banco
php artisan db:seed

# Componente Livewire
php artisan make:livewire NomeComponente

# Listar rotas
php artisan route:list

# Tinker (console interativo)
php artisan tinker
```

## 📄 Licença

MIT License - Use livremente!

---

**Desenvolvido com ❤️ para amantes de plantas 🌱**
