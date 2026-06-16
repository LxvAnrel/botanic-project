# 12 — Glossário de conceitos

Definições acadêmicas dos termos usados na documentação.

### Arquitetura e padrões
- **MVC (Model-View-Controller):** padrão que separa dados (Model),
  apresentação (View) e orquestração (Controller).
- **Front controller:** ponto único de entrada (`public/index.php`) por onde
  passam todas as requisições.
- **Middleware:** função/camada que intercepta a requisição antes/depois do
  handler (ex.: cabeçalhos, autenticação, CSRF).
- **Separação de responsabilidades (SoC):** cada módulo tem uma única razão para
  mudar.
- **Serviço de domínio:** classe que concentra regras de negócio puras
  (`Gamification`, `PlantCare`), sem acoplar a HTTP ou banco.
- **Arquitetura orientada a eventos:** componentes reagem a eventos
  (`Registered`, `Login`) via listeners, desacoplando causa e efeito.

### Dados
- **ORM (Object-Relational Mapping):** mapeia tabelas para objetos (Eloquent).
- **Migration:** alteração versionada do schema do banco.
- **Seeder:** script que popula dados iniciais.
- **Relação N:N:** muitos-para-muitos, resolvida por uma **tabela pivô**.
- **Relação polimórfica:** FK "genérica" guardando *tipo + id* do dono.
- **Mass assignment:** preencher vários campos de uma vez; controlado por
  `$fillable` para evitar escrita indevida.
- **Cast:** conversão automática de tipo (JSON↔array, string↔date, hash).
- **Query scope:** condição de consulta nomeada e reutilizável.
- **Problema N+1:** uma consulta por item de uma lista; resolvido com
  *eager loading* ou agregação.
- **Normalização:** organizar tabelas para reduzir redundância (ex.: log de
  cuidados separado das plantas).
- **Idempotência:** repetir a operação não muda o resultado (ex.: conceder badge
  só uma vez; deduplicação de notificações).

### Segurança
- **Hashing (bcrypt):** transformação irreversível e lenta da senha, com *salt*.
- **Sessão:** estado do usuário entre requisições, via cookie assinado.
- **CSRF:** ataque que força requisições autenticadas de outro site; mitigado por
  token por sessão.
- **XSS:** injeção de script; mitigada pelo escape automático do Blade (`{{ }}`).
- **Rate limiting:** limitar tentativas por janela de tempo (anti-força bruta).
- **HSTS:** cabeçalho que força o navegador a usar somente HTTPS.
- **Session fixation:** ataque mitigado regenerando o id de sessão no login.
- **Privacy by design:** privacidade considerada desde o projeto (perfil público
  é *opt-in*; exclusão com prazo de reativação).

### Front-end
- **Reatividade server-side (Livewire):** UI em função do estado mantido no
  servidor; interações via AJAX que re-renderizam o componente.
- **Utility-first CSS (Tailwind):** estilizar compondo classes utilitárias.
- **Progressive enhancement:** o essencial funciona sem JS; recursos extras
  (push) são camadas adicionais.
- **Cache-busting:** assets com hash no nome para forçar atualização.

### Notificações / mensageria
- **Canal de notificação:** meio de entrega (database, mail, webpush).
- **Mailable:** classe que representa um e-mail.
- **VAPID:** par de chaves que autentica o servidor no Web Push.
- **Service Worker:** script em segundo plano no navegador (recebe push).
- **Transporte de e-mail:** mecanismo de envio (SMTP vs API HTTP).

### Operação
- **12-Factor App:** metodologia; aqui destaca-se config via variáveis de
  ambiente e paridade entre ambientes.
- **Build vs runtime:** etapas determinísticas (build) vs dependentes do ambiente
  (runtime).
- **Armazenamento efêmero:** disco do contêiner descartado a cada deploy.
- **PaaS:** Plataforma como Serviço (Railway) — abstrai servidor/infra.
- **Task scheduling:** cron expresso em código.
- **Fila / worker:** processamento assíncrono de tarefas.
- **Lockfile:** fixa versões exatas de dependências.
- **Soft delete:** marcar como excluído sem apagar imediatamente.

### Domínio do problema (botânico)
- **Habitat de luz:** sol pleno / meia-sombra / sombra — determina rega e
  recomendação.
- **Época de poda:** estações ideais (lista JSON por planta).
- **Intervalo de rega/adubação:** dias entre cuidados; explícito ou por
  heurística da luz.
- **Streak:** dias consecutivos com cuidados registrados.
- **XP / nível:** pontuação acumulada e faixa derivada dela.
