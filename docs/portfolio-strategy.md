# Portfolio Strategy — Yuri Peixoto

> Documento de referência pessoal. Não é código, não é feature — é governança do portfólio.
> Revisado e estruturado em: 2026-03-07

---

## 1. Narrative

O portfólio não é uma lista de projetos. É um **ecossistema com uma história coerente**.

A história: um dev sênior que domina sua stack principal (Laravel + React/TypeScript), e que
deliberadamente expandiu seu repertório para Go e Python ao construir ferramentas que resolvem
problemas reais — problemas que ele mesmo enfrenta no dia a dia profissional.

Orquestra nasceu da dor de equipes de qualidade e processos sem governança técnica estruturada.
Maestro nasceu de um servidor on-premise sem observabilidade real, onde Datadog e Sentry eram
caros demais e dependentes de cloud.

Isso é raro. A maioria dos portfólios públicos é feita de tutoriais renomeados e todo-lists.
Este é diferente.

---

## 2. Política Multilíngue (todos os projetos)

Todos os projetos do portfólio seguem a mesma convenção, já estabelecida no Maestro:

| Artefato | Idioma |
|----------|--------|
| Código-fonte (variáveis, funções, comentários) | Inglês exclusivamente |
| `README.md` | Inglês (primário) |
| `README.pt-BR.md` | Português Brasileiro (secundário) |
| ADRs e documentação técnica interna | Podem iniciar em PT-BR, migrar para EN conforme o projeto escala |
| Issues e PRs no GitHub | Inglês (padrão open source) |
| Commit messages | Inglês (conventional commits) |

**Motivação:** inglês é o padrão de facto em open source. A versão pt-BR garante acessibilidade
para a comunidade brasileira e demonstra cuidado com internacionalização — algo que recrutadores
globais valorizam. Posicionamento público desde o início.

---

## 3. Projetos — Versão Revisada (8 projetos)

> Eliminados: **Forge** (#06 — redundante com Orquestra) e **Helix** (#05 — demo sem problema real).
> **Aegis** movido do grupo satellite para o core.

### Core Ecosystem (5 projetos interligados)

| # | Nome | Papel no Ecossistema | Stack Principal |
|---|------|----------------------|-----------------|
| 01 | **Orquestra** | Plataforma SaaS de governança técnica | Laravel + React/TS |
| 02 | **Maestro** | Observabilidade e telemetria on-premise | Go + Python + ClickHouse |
| 04 | **Sentinel** | Feature Flags + SDKs (TS e PHP) | Go (server) + TypeScript SDK |
| 07 | **Aegis** | Ticket & Incident Management | Python + FastAPI + React |
| 10 | **Pulse** | CI/CD & Deployment Dashboard | Go + React |

### Satellite Projects — Hobby & Domain (3 projetos independentes)

| # | Nome | Foco | Stack Principal |
|---|------|------|-----------------|
| 03 | **DataScope** | Dados públicos brasileiros (ETL + visualização) | Python + FastAPI + Pandas |
| 08 | **Mythos** | RPG Campaign Manager com export VTT | Django + React |
| 09 | **PokéOps** | Competitive Pokémon Platform | Python + FastAPI + Redis |

---

## 4. Ecosystem Architecture

```
┌──────────────────────────────────────────────────────────────────┐
│                        CORE ECOSYSTEM                             │
│                                                                    │
│  ┌─────────────┐   events/logs    ┌──────────────────┐          │
│  │  Orquestra  │ ───────────────▶ │     Maestro      │          │
│  │  (Laravel)  │                  │   (Go + Python)  │          │
│  └──────┬──────┘                  └────────┬─────────┘          │
│         │                                   │                     │
│         │ feature flags          anomalies  │  metrics           │
│         ▼                                   ▼                     │
│  ┌─────────────┐                  ┌──────────────────┐          │
│  │   Sentinel  │                  │      Pulse       │          │
│  │  (Go + SDK) │                  │      (Go)        │          │
│  └─────────────┘                  └──────────────────┘          │
│                                            │                      │
│                          incident tickets  │                      │
│                                            ▼                      │
│                                  ┌──────────────────┐           │
│                                  │      Aegis       │           │
│                                  │  (Python + FA)   │           │
│                                  └──────────────────┘           │
└──────────────────────────────────────────────────────────────────┘

Satellite: DataScope, Mythos, PokéOps  (standalone, sem integração ao core)
```

### Integration Contracts

Todos os projetos funcionam **standalone**. As integrações são opcionais e baseadas em contratos
claros (HTTP/webhook), não em acoplamento de código.

| De | Para | Tipo | Descrição |
|----|------|------|-----------|
| Orquestra | Maestro | HTTP webhook / SDK PHP | Eventos de domínio emitidos pela plataforma |
| Maestro | Aegis | HTTP webhook | Anomalias detectadas viram tickets de incidente |
| Maestro | Pulse | API REST | Health metrics como fonte de dados do dashboard |
| Orquestra | Sentinel | SDK PHP | Feature flags por workspace |
| Qualquer app | Maestro | OpenTelemetry (Phase 4) | Protocolo padrão de ingestão de telemetria |

**Caso de uso real multi-projeto:** Maestro detecta pico de memória no servidor on-premise →
cria ticket automaticamente no Aegis → time acessa Aegis, resolve o incidente →
ticket fechado. Funciona para o próprio servidor da empresa, para projetos de clientes
(gestão de frotas, etc.), para qualquer stack. Aegis não conhece Maestro — só recebe webhooks.

---

## 5. Technology Map por Projeto

### 01 — Orquestra
**Stack:** Laravel 12, PHP 8.4, PostgreSQL, Redis, React 18, TypeScript, Inertia.js,
Spatie Permission, Spatie Activity Log

**Roadmap:**
- **Phase 1** *(em andamento)*: Auth, Workspace, Teams, Initiatives, Kanban, ADR registry, Dashboard
- **Phase 2**: Activity Log completo, métricas por workspace, snapshots de reporting
- **Phase 3**: Queue workers + notificações async (Slack / Teams / Discord / email)
- **Phase 4**: API REST pública com tokens (integração programática com Maestro e outros)
- **Phase 5**: Billing/subscription com Stripe

**Melhorias de produto (dores reais de equipes de qualidade):**
- Export de relatórios em PDF/Excel — equipes de QA vivem de planilha
- Health score por iniciativa (prazo, atividade recente, decisões abertas)
- Template de processos (checklist recorrente por tipo de iniciativa)
- Timeline visual de decisões por projeto (ADR registry como linha do tempo)

---

### 02 — Maestro
**Stack:** Go 1.26 (Agent), Python 3.10+ (FastAPI), ClickHouse, Redis Streams,
React + Vite + ECharts

**Correções imediatas:**
- `go.mod`: atualizar `go 1.25.0` → `go 1.26.0`
- Converter ADRs de `.txt` para `.md`
- Adicionar `frontend/node_modules/` ao `.gitignore`

**Roadmap:**
- **Phase 1**: Go Agent funcional (CPU, memória, disco via `gopsutil`) → Redis Streams → ClickHouse → dashboard básico
- **Phase 2**: Alert engine com threshold estático + notificações (Slack/Discord/Telegram)
- **Phase 3**: Dynamic Thresholds com Pandas + Scikit-learn (anomaly detection)
- **Phase 4**: OpenTelemetry compatibility — qualquer app envia telemetria via protocolo padrão
- **Phase 5**: Prometheus metrics endpoint — integração com Grafana existente
- **Phase 6**: eBPF para monitoramento de rede/kernel (diferencial sênior)

**Diferenciais técnicos (implementar, não apenas documentar):**
- Agent como **binário único sem dependências** — deploy em um único arquivo executável
- ClickHouse como diferencial real de currículo (pouquíssimos devs têm)
- On-premise first — dados nunca saem do servidor
- Suporte a Windows (o servidor-problema é Windows)
- Docker Compose para deploy completo em um comando (para o público geral)
- Modo "agentless" via SSH (fase avançada — para servidores sem permissão de instalação)

---

### 04 — Sentinel (Reposicionado)
**Stack:** Go 1.26 (servidor de flags), TypeScript SDK (npm), PHP SDK (Packagist), React (dashboard)

**Por que não Laravel API:**
Um SDK TypeScript publicado no npm é infinitamente mais impressionante em portfólio
do que mais uma API Laravel. Demonstra capacidade de escrever código *para* outros
consumirem — habilidade rarissimamente demonstrada em portfólios públicos.

**Roadmap:**
- Servidor de flags em Go (stateless, eficiente, distribuível como binário)
- `@sentinel/sdk` — TypeScript SDK publicado no npm
- `sentinel/sdk-php` — PHP SDK publicado no Packagist
- Dashboard React para gerenciar flags por ambiente
- A/B testing com coleta de métricas de exposição

---

### 07 — Aegis *(movido para o Core)*
**Stack:** Python 3.10+, FastAPI, PostgreSQL, Pandas, React

**Por que é core e não satellite:**
Aegis fecha o ciclo do ecossistema. Maestro detecta anomalias → Aegis gerencia os incidentes
resultantes. Além disso, como ferramenta de ticket/incident management, é reutilizável em
qualquer contexto: Orquestra (incidents por workspace), projetos privados (gestão de frotas),
clientes externos. Entra via webhook, serve via API — totalmente desacoplado.

**Roadmap:**
- Criação e gerenciamento de tickets (manual + via API/webhook)
- Upload e análise de bases existentes (CSV/JSON de Jira, Zendesk, ServiceNow)
- Análise automática: MTTR, volume por categoria, tendências, gargalos
- Geração de relatório executivo em PDF
- Dashboard com insights automáticos ("Categoria X aumentou 40% este mês")
- Webhook de entrada do Maestro (criação automática de incidentes)

---

### 10 — Pulse
**Stack:** Go 1.26, React, GitHub API, GitLab API, Redis (cache), WebSocket

**Por que Go é obrigatório aqui:**
Polling concorrente de múltiplos pipelines de múltiplos repositórios é o caso de uso
perfeito para goroutines. Natural em Go, problemático em Python.

**Roadmap:**
- Polling de GitHub Actions e GitLab CI via APIs oficiais
- WebSocket para live updates no dashboard (sem refresh)
- Cache com Redis para evitar rate limiting das APIs
- Filtros por organização, repositório, branch, status
- Integração com Maestro (deploy falhou = alerta de observabilidade)

---

### 03 — DataScope
**Stack:** Python 3.10+, FastAPI (não Django), Pandas, Celery + Redis, React + ECharts

**Por que FastAPI e não Django:**
Pipeline ETL + API analítica não precisa do overhead do Django. FastAPI é mais moderno,
assíncrono, e o que o mercado quer ver em Python em 2026.

**Roadmap:**
- Ingestão de dados públicos: IBGE, DataSUS, INEP, Banco Central
- Pipeline ETL com Celery para processamento periódico
- API com endpoints analíticos (crescimento por região, comparativos históricos)
- Dashboard com visualizações geoespaciais (mapas do Brasil)
- Diferencial: impacto social real + dados que qualquer brasileiro reconhece

---

### 08 — Mythos
**Stack:** Django, React, PostgreSQL

**Escopo:**
- Gerenciamento completo de campanhas: sessões, NPCs, locais, itens, facções, lore
- Sistema de notas com markdown, tags e links entre entidades
- Timeline visual de eventos da campanha
- Compartilhamento de sessão com jogadores (read-only)

**VTT Export — diferencial único no mercado:**

A maioria dos gerenciadores de campanha prende seus dados. Mythos exporta.
Isso resolve uma dor real de todo mestre que usa múltiplas ferramentas.

| VTT | Formato de Export | Prioridade |
|-----|------------------|------------|
| **Fantasy Grounds Unity** | `.mod` (XML comprimido em ZIP) | **Alta — uso pessoal** |
| Foundry VTT | `.json` (world/module package) | Média |
| Roll20 | Campaign JSON export | Baixa |

**Fantasy Grounds Unity — detalhes técnicos:**
- Módulos FGU são arquivos `.xml` organizados em estrutura específica,
  comprimidos em `.mod` (ZIP renomeado)
- Schema inclui: `<encounter>`, `<npc>`, `<item>`, `<story>` (notas/lore), `<image>`
- Django gera o XML → faz o ZIP → entrega o `.mod` para download
- NPCs do Mythos → statblocks no FGU (D&D 5e e Pathfinder 2e como systems iniciais)
- Imagens de mapas e tokens exportadas no mesmo pacote

**Foundry VTT:**
- Formato JSON bem documentado na API oficial do Foundry
- Package com `module.json` + assets
- Exporta: journalentries (lore/notas), scenes (mapas), actors (NPCs/personagens), items

---

### 09 — PokéOps
**Stack:** Python 3.10+, FastAPI, PostgreSQL, Redis, React

**Diferencial vs DataScope (ambos Python + dados + visualização):**
- DataScope = ETL batch, dados históricos, impacto social
- PokéOps = real-time, análise competitiva, performance de queries, caching agressivo

**Roadmap:**
- Integração com PokéAPI (cache total — esses dados não mudam)
- Calculadora de times (type coverage, sinergias, counters)
- Análise estatística (EVs, IVs, base stats comparison, damage calculator)
- Tier list dinâmica com dados de uso competitivo (Smogon usage stats)
- Diferencial natural: comunidade Pokémon enorme → potencial orgânico de stars

---

## 6. Stack Summary — O que cada projeto demonstra

| Habilidade | Projetos |
|------------|----------|
| Laravel sênior + Modular Monolith | Orquestra |
| Multi-tenancy real | Orquestra |
| Billing / Stripe | Orquestra (Phase 5) |
| React / TypeScript sênior | Todos (frontend) |
| Go — concorrência, goroutines, channels | Maestro Agent, Pulse, Sentinel server |
| Go — binários standalone deployáveis | Maestro Agent, Sentinel server |
| Python / FastAPI | Maestro API, DataScope, PokéOps, Aegis |
| Django | Mythos |
| ClickHouse (OLAP, time series) | Maestro |
| Redis / Redis Streams | Maestro, PokéOps, Pulse, Sentinel |
| Pandas / análise de dados | Aegis, DataScope, Maestro (Phase 3) |
| ML básico (anomaly detection) | Maestro (Phase 3) |
| SDK development + publicação (npm / Packagist) | Sentinel |
| OpenTelemetry | Maestro (Phase 4) |
| eBPF | Maestro (Phase 6) |
| ETL pipelines | DataScope |
| Modelagem de dados complexa | Mythos, PokéOps |
| VTT Integration (Fantasy Grounds, Foundry) | Mythos |
| ADR / Governance documentation | Orquestra, Maestro |
| CI/CD + testes automatizados | Todos |
| Multilíngue EN + pt-BR | Todos |

---

## 7. Workflow Rhythm

### Fluxo macro por fase

```
Fase A  (atual)
  PRIMARY:    Orquestra — Phase 1 (finalizar issues #9–#12)
  SECONDARY:  Maestro — Phase 1 (Go agent funcional)

Fase B
  PRIMARY:    Maestro — Phase 2 (alerts, notificações)
  SECONDARY:  Orquestra — Phase 2 (activity log, metrics)

Fase C  (PokéOps + Pulse — consolidar Go e Python)
  PRIMARY:    PokéOps (Python puro — aprendizado consolidado)
  SECONDARY:  Pulse (Go puro — aprendizado consolidado)
  MAINTENANCE: Orquestra + Maestro (bugs, melhorias menores)

Fase D
  PRIMARY:    DataScope
  SECONDARY:  Aegis
  MAINTENANCE: todos anteriores

Fase E
  PRIMARY:    Sentinel (Go server + SDKs npm/Packagist)
  SECONDARY:  Mythos (Django + VTT export)
  MAINTENANCE: todos anteriores

Fase F  (showcase)
  READMEs de impacto, screenshots/GIFs de todos os projetos
  Conectar o core: Orquestra → Maestro → Aegis
  Publicar SDKs do Sentinel no npm e Packagist
```

> PokéOps antes de DataScope porque é menor, mais divertido,
> e vai consolidar Python de forma leve antes de um projeto mais pesado.

### Sprint semanal

| Projeto | Issues/sprint |
|---------|--------------|
| Primário | 4–5 issues |
| Secundário | 2–3 issues |

**Regra de ouro:** evitar trocar de linguagem no mesmo dia.
Go/Python têm modo mental diferente de PHP/Laravel.
Sugestão: dias alternados por projeto (adaptar ao que funcionar na prática).

### Definição de "projeto finalizado"

Um projeto passa para modo manutenção quando:

1. Phase 1 entregue com código funcional no GitHub
2. `README.md` impecável em inglês (com screenshots ou GIF de demo)
3. `README.pt-BR.md` equivalente
4. CI/CD configurado e passando
5. Testes cobrindo os fluxos críticos
6. "Como rodar em 5 minutos" documentado e testado por alguém que não é você

Perfeito não existe. Finalizado sim.

---

## 8. O que faz este portfólio se destacar

1. **Problemas reais, não projetos de tutorial** — Orquestra e Maestro têm origem documentada
2. **Código que você claramente entende** — ADRs, testes, documentação técnica
3. **Profundidade em vez de amplitude** — 8 projetos bem feitos > 10 pela metade
4. **Tecnologias não óbvias, bem justificadas** — ClickHouse, eBPF, OpenTelemetry, SDKs
5. **Commits consistentes por meses** — histórico ativo diz mais que dump de código
6. **Testes automatizados** — absurdamente raro em portfólios públicos
7. **Multilíngue desde o início** — posicionamento global, valoriza comunidade local
8. **Ecossistema coeso com integrações reais** — não são projetos soltos, são peças de um sistema
9. **Mix de tools sérias e projetos pessoais** — mostra quem você é além do trabalho

---

## 9. Próximos passos imediatos

### Orquestra
- [ ] Terminar Phase 1: issues #9 (Dashboard), #10 (Pest), #11 (ADR policy), #12 (Linting)
- [ ] Criar `CLAUDE.md` na raiz do projeto
- [ ] README.md de impacto (não apenas setup)
- [ ] Adicionar `README.pt-BR.md`

### Maestro
- [ ] Corrigir `go.mod`: `go 1.25.0` → `go 1.26.0`
- [ ] Converter ADRs de `.txt` para `.md`
- [ ] Adicionar `frontend/node_modules/` ao `.gitignore`
- [ ] Implementar Go Agent real com `gopsutil` (CPU, memória, disco)
- [ ] Criar `CLAUDE.md` na raiz do projeto
- [ ] Estruturar roadmap com phases (como Orquestra já tem)
- [ ] Adicionar `README.pt-BR.md`
