# 🌿 COMECE AQUI - Plataforma Botânica Interativa

Bem-vindo! Este é um guia rápido para colocar o projeto em funcionamento.

## ⚡ 5 Minutos para Começar

### 1. Abra PowerShell na pasta do projeto

```powershell
cd "C:\Users\Uvits\Downloads\plataforma-botanica-interativa"
```

### 2. Instale as dependências

```bash
composer install
npm install
```

### 3. Configure o banco de dados

Edite o arquivo `.env`:

```env
# Para SQLite (mais simples - recomendado para começar):
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# Ou para MySQL (se preferir):
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=plataforma_botanica
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Prepare o banco de dados

```bash
php artisan migrate
php artisan db:seed
```

### 5. Rode o servidor

Em um terminal:
```bash
php artisan serve
```

Em outro terminal:
```bash
npm run dev
```

### 6. Acesse no navegador

```
http://localhost:8000
```

## 📝 Dados de Teste

**Usuário de Teste:**
- Email: `test@example.com`
- Senha: `password`

Ou crie uma nova conta em `/register`

## 🎯 O Que Você Pode Fazer

### 1. Explorar o Catálogo
- Acesse `/catalogo`
- Aplique filtros por luz, tamanho e pet-friendly
- Clique em plantas para ver detalhes completos

### 2. Fazer o Quiz
- Vá para `/quiz`
- Responda 4 perguntas simples
- Receba uma recomendação personalizada

### 3. Gerenciar seu Diário Verde (Autenticado)
- Faça login
- Adicione plantas ao seu acervo pessoal
- Acesse `/dashboard` para ver suas plantas

### 4. Acompanhar Alertas (Autenticado)
- Veja notificações de poda
- Verifique o histórico de alertas em `/alertas`

## 📖 Documentos Importantes

| Arquivo | Descrição |
|---------|-----------|
| **README.md** | Guia completo do projeto |
| **SETUP.md** | Instruções detalhadas de instalação |
| **PROJETO_RESUMO.md** | O que foi desenvolvido |
| **COMECE_AQUI.md** | Este arquivo (quick start) |

## 🛠️ Arquitetura do Projeto

```
Plataforma Botânica
├── Frontend
│   ├── Livewire (componentes reativos)
│   ├── Alpine.js (interatividade)
│   └── Tailwind CSS (estilos)
│
├── Backend
│   ├── Laravel 11
│   ├── Controllers (lógica)
│   ├── Models (banco de dados)
│   └── Commands (tarefas agendadas)
│
└── Database
    ├── plants (catálogo)
    ├── users (usuários)
    └── plant_user (Diário Verde)
```

## 📊 Estrutura de Dados

### Plantas Incluídas
- 🌺 Flor-da-Fortuna (tóxica, sol pleno)
- 🎋 Bambu da Sorte (pet-friendly, meia sombra)
- ⚔️ Espada de São Jorge (tóxica, sombra)
- 🌿 Jiboia (pet-friendly, rasteira)
- 🪲 Peperômia (pet-friendly, pequena)
- 🔴 Croton (tóxica, cores vibrantes)

## 🔧 Comandos Úteis

```bash
# Ver todas as rotas
php artisan route:list

# Acessar console interativo
php artisan tinker

# Resetar banco (cuidado!)
php artisan migrate:refresh --seed

# Criar novo componente Livewire
php artisan make:livewire NovoComponente

# Criar nova migration
php artisan make:migration criar_tabela

# Executar comando agendado manualmente
php artisan plants:check-pruning-season
```

## ⚠️ Erros Comuns

### "Port 8000 is already in use"
```bash
php artisan serve --port=8080
```

### "Class not found"
```bash
composer dump-autoload
```

### "CSS não carregando"
```bash
npm run build
# ou
npm run dev
```

### "Banco não conecta"
1. Verifique .env
2. Se usar MySQL, crie o banco: `CREATE DATABASE plataforma_botanica;`
3. Execute: `php artisan migrate`

## 🚀 Pronto para Produção?

Leia **SETUP.md** para instruções de deploy em servidor real.

## 💡 Dicas

- Use `npm run dev` durante desenvolvimento (auto-reload)
- Veja os logs em `storage/logs/laravel.log`
- Limpe cache com: `php artisan cache:clear`
- Recompile assets: `npm run build`

## 📞 Suporte

Se tiver problemas:
1. Verifique README.md
2. Consulte SETUP.md
3. Veja os documentos de código comentados

## ✨ Funcionalidades Principais

✅ Catálogo de 6 plantas de exemplo
✅ Filtros dinâmicos em tempo real
✅ Quiz inteligente com 4 perguntas
✅ Sistema de pontuação para recomendações
✅ Autenticação de usuários
✅ Diário Verde (plantas favoritas)
✅ Alertas automáticos de poda
✅ Interface responsiva (mobile-friendly)

## 🎯 Próximos Passos (Opcional)

1. Adicione mais plantas ao seeder
2. Implemente upload de imagens
3. Crie testes automatizados
4. Deploy em produção
5. Integre com serviço de email

---

**Divirta-se explorando a Plataforma Botânica! 🌱**

Desenvolvido com ❤️ para amantes de plantas
