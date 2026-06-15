# 🌿 Plataforma Botânica Interativa - Documentação Completa

## 📌 Visão Geral do Projeto

A **Plataforma Botânica Interativa** é um sistema web moderno desenvolvido em **Laravel 11** que funciona como um catálogo inteligente de plantas, um diário de cuidados pessoais ("Diário Verde") e um motor de recomendação baseado em quiz.

O objetivo é transformar a consulta estática de informações botânicas em uma experiência interativa, personalizada e útil para usuários que desejam descobrir, gerenciar e cuidar de plantas.

---

## 🎯 Funcionalidades Principais

### 1️⃣ Catálogo Inteligente de Plantas
- Exibição paginada de todas as plantas cadastradas
- **Filtros Dinâmicos em Tempo Real** (sem recarregar página):
  - 🌞 Habitat de luz (Sol Pleno / Meia Sombra / Sombra)
  - 🐾 Pet-friendly (plantas não tóxicas para animais)
  - 📏 Tamanho máximo (pequenas, médias, grandes)
- **Busca por Texto Livre**: nome popular, nome científico ou família
- Detalhes completos de cada planta com informações botânicas

### 2️⃣ Quiz Interativo
- **4 Etapas Personalizadas**:
  1. Você tem pets em casa?
  2. Quanto de luz seu ambiente recebe?
  3. Qual é o tamanho do seu espaço?
  4. Qual é sua experiência com plantas?

- **Sistema de Recomendação Inteligente**: 
  - Pontuação automática baseada em respostas
  - Descarta plantas tóxicas se há pets
  - Prioriza plantas que combinam com o ambiente
  - Retorna a planta com maior compatibilidade

### 3️⃣ Diário Verde (Área do Usuário)
- Adicionar/remover plantas ao acervo pessoal
- Visualizar todas as suas plantas favoritas
- Histórico de quando cada planta foi adicionada
- Acesso apenas para usuários autenticados

### 4️⃣ Notificações de Poda
- Alertas automáticos quando é época de poda das plantas
- Histórico completo de notificações
- Cálculo automático da estação do ano (Primavera/Verão/Outono/Inverno)
- Integração com Task Scheduling do Laravel

### 5️⃣ Autenticação de Usuários
- Registro e login de contas
- Recuperação de senha
- Gerenciamento de perfil pessoal
- Baseado em Laravel Breeze (seguro e testado)

---

## 🏗️ Arquitetura Técnica

### Stack Tecnológico

```
┌─────────────────────────────────────┐
│         Frontend (Vue/Blade)        │
│  - Livewire 4 (componentes reativos)│
│  - Alpine.js (interatividade)       │
│  - Tailwind CSS (design responsivo) │
└─────────────────────────────────────┘
          ⬇️ API Laravel
┌─────────────────────────────────────┐
│      Backend (Laravel 11)           │
│  - Controllers (lógica de negócio)  │
│  - Models Eloquent (banco de dados) │
│  - Routes (endpoints)               │
│  - Commands (tarefas agendadas)     │
└─────────────────────────────────────┘
          ⬇️ SQL
┌─────────────────────────────────────┐
│    Database (SQLite/MySQL)          │
│  - Tabela: plants (catálogo)        │
│  - Tabela: users (usuários)         │
│  - Tabela: plant_user (favoritos)   │
│  - Tabela: sessions (sessões)       │
└─────────────────────────────────────┘
```

### Tecnologias Utilizadas

| Camada | Tecnologia | Versão |
|--------|------------|--------|
| **Framework Web** | Laravel | 12.x |
| **Linguagem Server** | PHP | 8.2+ |
| **Frontend Reativo** | Livewire | 4.3+ |
| **Interatividade** | Alpine.js | 3.13+ |
| **Estilos** | Tailwind CSS | 4.0+ |
| **Build Tool** | Vite | 7.0+ |
| **Banco de Dados** | SQLite/MySQL | - |
| **ORM** | Eloquent | Nativo do Laravel |
| **Autenticação** | Laravel Breeze | Nativo do Laravel |

---

## 📊 Banco de Dados

### Schema do Banco

#### Tabela: `plants` (Catálogo)
```sql
CREATE TABLE plants (
    id BIGINT PRIMARY KEY,
    nome_popular VARCHAR(255),           -- Ex: "Flor-da-Fortuna"
    nome_cientifico VARCHAR(255) UNIQUE, -- Ex: "Kalanchoe blossfeldiana"
    slug VARCHAR(255) UNIQUE,            -- URL amigável: "flor-da-fortuna"
    familia VARCHAR(255),                -- Ex: "Crassulaceae"
    genero VARCHAR(255),                 -- Ex: "Kalanchoe"
    especie VARCHAR(255),                -- Ex: "K. blossfeldiana"
    origem VARCHAR(255),                 -- Ex: "Madagascar"
    habitat_luz ENUM('sol_pleno', 'meia_sombra', 'sombra'),
    porte_max_cm INT,                    -- Altura máxima em cm
    toxica_pets BOOLEAN,                 -- true = tóxica, false = segura
    epoca_poda JSON,                     -- ["primavera", "verão"]
    beneficios LONGTEXT,                 -- Descrição de benefícios
    maleficios LONGTEXT,                 -- Cuidados e toxicidade
    curiosidades LONGTEXT,               -- Informações interessantes
    image_path VARCHAR(255),             -- Caminho da imagem
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    INDEX idx_nome_popular,
    INDEX idx_habitat_luz,
    INDEX idx_toxica_pets,
    INDEX idx_slug
);
```

#### Tabela: `users` (Usuários)
```sql
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Tabela: `plant_user` (Diário Verde - Relacionamento)
```sql
CREATE TABLE plant_user (
    id BIGINT PRIMARY KEY,
    user_id BIGINT FOREIGN KEY → users.id,
    plant_id BIGINT FOREIGN KEY → plants.id,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    UNIQUE KEY unique_user_plant (user_id, plant_id)
);
```

#### Tabela: `sessions` (Sessões do Laravel)
```sql
CREATE TABLE sessions (
    id VARCHAR(255) PRIMARY KEY,
    user_id BIGINT NULL FOREIGN KEY,
    ip_address VARCHAR(45),
    user_agent LONGTEXT,
    payload LONGTEXT,
    last_activity INT
);
```

---

## 📁 Estrutura de Arquivos

```
plataforma-botanica-interativa/
│
├── app/
│   ├── Models/
│   │   ├── User.php                 ← Model do usuário
│   │   ├── Plant.php                ← Model da planta com scopes
│   │
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── PlantController.php  ← Lógica de plantas
│   │   │   └── DashboardController.php ← Dashboard do usuário
│   │
│   ├── Livewire/
│   │   ├── PlantCatalog.php         ← Componente do catálogo
│   │   └── PlantQuiz.php            ← Componente do quiz
│   │
│   └── Console/
│       └── Commands/
│           └── CheckPruningSeason.php ← Verificar épocas de poda
│
├── database/
│   ├── migrations/
│   │   ├── *_create_users_table.php
│   │   ├── *_create_plants_table.php
│   │   ├── *_create_plant_user_table.php
│   │   └── *_add_slug_to_plants_table.php
│   │
│   └── seeders/
│       ├── DatabaseSeeder.php       ← Executor principal
│       └── PlantSeeder.php          ← Popula com 6 plantas
│
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php        ← Layout principal
│   │   │
│   │   ├── plants/
│   │   │   ├── index.blade.php      ← Catálogo
│   │   │   └── show.blade.php       ← Detalhes da planta
│   │   │
│   │   ├── dashboard/
│   │   │   ├── index.blade.php      ← Diário Verde
│   │   │   ├── alertas.blade.php    ← Notificações
│   │   │   └── perfil.blade.php     ← Perfil do usuário
│   │   │
│   │   ├── livewire/
│   │   │   ├── plant-catalog.blade.php  ← View do catálogo Livewire
│   │   │   └── plant-quiz.blade.php     ← View do quiz Livewire
│   │   │
│   │   └── welcome.blade.php        ← Home
│   │
│   └── css/
│       └── app.css                  ← Estilos globais
│
├── routes/
│   └── web.php                      ← Definição de rotas
│
├── public/
│   └── index.php                    ← Entrada da aplicação
│
├── .env                             ← Variáveis de ambiente
├── composer.json                    ← Dependências PHP
├── package.json                     ← Dependências Node
│
├── README.md                        ← Guia geral
├── SETUP.md                         ← Instruções de setup
├── COMECE_AQUI.md                   ← Quick start
├── PROJETO_RESUMO.md                ← Resumo técnico
├── CHECKLIST_INICIO.txt             ← Checklist visual
└── DOCUMENTACAO_COMPLETA.md         ← Este arquivo
```

---

## 🔄 Fluxos Principais

### 1️⃣ Fluxo de Catálogo e Busca

```
Usuário acessa /catalogo
    ↓
Livewire carrega PlantCatalog
    ↓
Usuário digita busca / aplica filtros
    ↓
Livewire atualiza em tempo real (sem recarregar)
    ↓
Query executa:
   - Search scope (LIKE nome_popular, nome_cientifico, familia)
   - Sunlight scope (WHERE habitat_luz = ?)
   - PetFriendly scope (WHERE toxica_pets = false)
    ↓
Resultados paginados exibem (12 plantas por página)
    ↓
Usuário clica em uma planta
    ↓
Vai para /planta/{slug}
```

### 2️⃣ Fluxo do Quiz

```
Usuário acessa /quiz
    ↓
Quiz Livewire inicia (step = 1)
    ↓
Passo 1: Pergunta "Tem pets?"
    ↓
Passo 2: Pergunta "Quanto de luz?"
    ↓
Passo 3: Pergunta "Qual tamanho do espaço?"
    ↓
Passo 4: Pergunta "Sua experiência?"
    ↓
Usuário clica "Obter Recomendação"
    ↓
Backend executa calculateMatch():
   - Para cada planta:
       * Se tem pets + planta tóxica → score = -1000 (descarta)
       * Se luz combina → score += 10
       * Se espaço combina → score += 5
       * Experiência → score += 2 ou 5
   - Ordena por maior score
   - Retorna planta com maior score
    ↓
Exibe resultado com:
   - Foto da planta
   - Nome popular e científico
   - Benefícios e curiosidades
   - Botão "Adicionar ao Diário Verde"
```

### 3️⃣ Fluxo de Diário Verde

```
Usuário faz login
    ↓
Clica em "Adicionar ao Diário Verde" (em planta qualquer)
    ↓
POST /planta/{id}/favorite
    ↓
Cria registro em plant_user:
   - user_id = Auth::user()->id
   - plant_id = $plant->id
    ↓
Acessa /dashboard
    ↓
Exibe lista paginada de suas plantas
    ↓
Pode clicar em cada uma para ver detalhes
    ↓
Pode remover plantas (DELETE plant_user)
```

### 4️⃣ Fluxo de Notificações de Poda

```
Sistema agendado (cron job) executa:
   php artisan plants:check-pruning-season
    ↓
Para cada usuário:
   - Pega todas as plantas do Diário Verde
   - Detecta a estação atual (Carbon)
   - Para cada planta:
       * Se a planta tem poda nesta estação
       * Cria notificação em DB:
         - user_id
         - data JSON com: titulo, mensagem, planta_nome
    ↓
Usuário acessa /alertas
    ↓
Vê histórico de notificações
```

---

## 🔌 Rotas da Aplicação

| Método | Rota | Descrição | Autenticação |
|--------|------|-----------|---|
| GET | `/` | Home page | Pública |
| GET | `/catalogo` | Catálogo de plantas | Pública |
| GET | `/planta/{slug}` | Detalhes da planta | Pública |
| GET | `/quiz` | Quiz interativo | Pública |
| POST | `/planta/{id}/favorite` | Adicionar/remover do Diário Verde | ✅ Autenticado |
| GET | `/dashboard` | Meu Diário Verde | ✅ Autenticado |
| GET | `/alertas` | Notificações de poda | ✅ Autenticado |
| GET | `/perfil` | Perfil do usuário | ✅ Autenticado |
| GET | `/login` | Login | Pública |
| GET | `/register` | Registrar nova conta | Pública |

---

## 🌱 Dados de Exemplo (Seeder)

O projeto vem com **6 plantas pré-cadastradas**:

1. **Flor-da-Fortuna** (Kalanchoe blossfeldiana)
   - Habitat: Sol Pleno
   - Tóxica: ⚠️ Sim
   - Poda: Outono, Inverno

2. **Bambu da Sorte** (Dracaena sanderiana)
   - Habitat: Meia Sombra
   - Tóxica: ✅ Não (Pet-friendly)
   - Poda: Primavera, Verão

3. **Espada de São Jorge** (Sansevieria trifasciata)
   - Habitat: Sombra
   - Tóxica: ⚠️ Sim
   - Poda: Primavera

4. **Jiboia** (Epipremnum pinnatum)
   - Habitat: Meia Sombra
   - Tóxica: ✅ Não (Pet-friendly)
   - Poda: Primavera, Verão

5. **Peperômia** (Peperomia obtusifolia)
   - Habitat: Meia Sombra
   - Tóxica: ✅ Não (Pet-friendly)
   - Poda: Primavera

6. **Croton** (Codiaeum variegatum)
   - Habitat: Sol Pleno
   - Tóxica: ⚠️ Sim
   - Poda: Verão, Outono

---

## 📋 Requisitos Implementados

### Requisitos Funcionais (RF)

| RF | Descrição | Status |
|----|-----------|--------|
| RF01 | Catálogo paginado com todas as plantas | ✅ |
| RF02 | Busca por texto livre | ✅ |
| RF03 | Filtros dinâmicos (luz, tamanho, pet-friendly) | ✅ |
| RF04 | Cadastro, login e recuperação de senha | ✅ |
| RF05 | Adicionar/remover plantas do Diário Verde | ✅ |
| RF06 | Cálculo automático de época de poda | ✅ |
| RF07 | Notificações/alertas de poda | ✅ |
| RF08 | Quiz interativo de múltiplas etapas | ✅ |
| RF09 | Recomendação inteligente (Match) | ✅ |

### Requisitos Não Funcionais (RNF)

| RNF | Descrição | Implementação |
|-----|-----------|---|
| RNF01 | PHP 8.x + Laravel 11 | ✅ PHP 8.2+, Laravel 12.x |
| RNF02 | Livewire + Alpine.js para reatividade | ✅ Livewire 4.3, Alpine.js 3.13 |
| RNF03 | Banco relacional (MySQL/SQLite) | ✅ SQLite padrão, MySQL opcional |
| RNF04 | Layout responsivo com Tailwind CSS | ✅ Tailwind 4.0 |
| RNF05 | Task Scheduling para rotinas | ✅ Laravel Console Commands |

---

## 🛠️ Como Usar

### Instalação Rápida

```bash
# 1. Navegar até o projeto
cd C:\Users\Uvits\Downloads\plataforma-botanica-interativa

# 2. Instalar dependências
composer install
npm install

# 3. Configurar banco de dados
php artisan migrate
php artisan db:seed

# 4. Rodar servidor (Terminal 1)
php artisan serve

# 5. Compilar assets (Terminal 2)
npm run dev

# 6. Acessar
http://localhost:8000
```

### Dados de Teste

- **Email**: test@example.com
- **Senha**: password

Ou registrar uma nova conta em `/register`

### Comandos Úteis

```bash
# Resetar banco de dados
php artisan migrate:refresh --seed

# Executar verificação de poda manualmente
php artisan plants:check-pruning-season

# Listar todas as rotas
php artisan route:list

# Acessar console interativo
php artisan tinker

# Build dos assets
npm run build

# Desenvolvimento com hot reload
npm run dev
```

---

## 🔒 Segurança

### Implementado

- ✅ Hash de senhas com bcrypt
- ✅ CSRF protection (tokens)
- ✅ SQL injection prevention (Eloquent ORM)
- ✅ XSS protection (Blade auto-escape)
- ✅ Autenticação com Laravel Breeze
- ✅ Middleware de autenticação nas rotas privadas
- ✅ Validação de entrada via Laravel

### Recomendações para Produção

- [ ] HTTPS obrigatório
- [ ] Rate limiting nos endpoints
- [ ] Backup automático do banco
- [ ] Monitoramento de logs
- [ ] Firewall web (WAF)
- [ ] Atualizações regularmente do Laravel

---

## 📈 Escalabilidade

### Otimizações Implementadas

- ✅ Índices no banco de dados (nome_popular, habitat_luz, toxica_pets, slug)
- ✅ Paginação de resultados (12 plantas por página)
- ✅ Componentes Livewire para menos requisições
- ✅ Cache de sessões em banco (melhor para múltiplos servidores)

### Próximos Passos (Opcional)

- [ ] Cache Redis para queries frequentes
- [ ] ElasticSearch para busca avançada
- [ ] CDN para imagens de plantas
- [ ] API REST para apps mobile
- [ ] Fila de processamento (Laravel Queue)

---

## 🐛 Troubleshooting

### Erro: "could not find driver" (SQLite)
**Solução**: Habilitar extensão `pdo_sqlite` em `php.ini`

### Erro: "Class not found"
**Solução**: `composer dump-autoload`

### CSS/JS não carregam
**Solução**: `npm run build` (produção) ou `npm run dev` (desenvolvimento)

### Porta 8000 em uso
**Solução**: `php artisan serve --port=8080`

---

## 📚 Documentos Inclusos

- **README.md** - Guia geral do projeto
- **SETUP.md** - Instruções detalhadas de instalação
- **COMECE_AQUI.md** - Quick start (5 minutos)
- **PROJETO_RESUMO.md** - Resumo técnico
- **CHECKLIST_INICIO.txt** - Checklist passo a passo
- **DOCUMENTACAO_COMPLETA.md** - Este arquivo

---

## 🎯 Próximos Passos Opcionais

1. **Adicionar mais plantas** - Expanda o seeder
2. **Upload de imagens** - Implemente Storage de imagens
3. **Testes automatizados** - PHPUnit + Laravel Testing
4. **API REST** - Para aplicativo mobile
5. **Email notifications** - Notificações por email
6. **Social sharing** - Compartilhar plantas em redes sociais
7. **Analytics** - Google Analytics ou Plausible
8. **Dark mode** - Tema escuro com Alpine.js

---

## 👨‍💻 Desenvolvido com

- ❤️ Laravel Community
- 🚀 Livewire (TALL Stack)
- 🎨 Tailwind CSS
- 📱 Alpine.js
- 🗄️ Eloquent ORM

---

## 📞 Licença

MIT License - Use livremente!

---

**Última atualização**: Junho 2026

**Status**: ✅ 100% Funcional e Documentado

**Pronto para**: Desenvolvimento, Customização, Deploy em Produção
