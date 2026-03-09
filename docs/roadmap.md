# Roadmap

## Phase 1 — Foundation MVP *(current)*

> Auth, multi-tenancy, core domain entities, basic UI. The building blocks everything else depends on.

### Infrastructure & Tooling
- [x] Project structure (modular monolith)
- [x] Core documentation (vision, architecture, ADRs)
- [ ] Setup Testing Structure (Pest) — #10
- [ ] Setup Linting & Formatting (ESLint, Prettier, Pint) — #12

### Auth & Multi-Tenancy
- [x] Authentication module (register, login, password reset) — #1
- [x] Role & permission system (Spatie Permission, workspace-scoped) — #2
- [x] Workspace entity (creation, membership) — #3
- [x] Workspace middleware (tenant context enforcement) — #4

### Core Domain
- [x] Team module (creation, membership, role validation) — #5
- [x] Initiative entity (CRUD, workspace-scoped) — #6
- [x] Basic Kanban board UI (drag & drop, status update) — #7
- [x] Decision entity — ADR registry (CRUD + link to initiative + UI) — #8
- [x] Basic dashboard (initiative count by status, recent decisions, team count) — #9

### Documentation
- [ ] ADR policy & template — #11

---

## Phase 2 — Governance Intelligence

> This is what makes Orquestra a governance platform, not a task manager.
> Phase 1 builds the data model. Phase 2 makes that data tell a story.

### Activity & Audit
- [ ] Activity log UI — full visible audit trail per entity (initiative, decision, team) — #27
- [ ] Decision status lifecycle (PROPOSED → ACCEPTED / REJECTED / SUPERSEDED → IMPLEMENTED) — #28

### Initiative Health
- [ ] Initiative health score algorithm (activity recency, open decisions, overdue threshold) — #29
- [ ] Health indicators on Kanban board (colour-coded, hover detail) — #30
- [ ] Health indicators on dashboard (score distribution, at-risk initiatives list) — #30 *(same issue)*

### Governance Compliance
- [ ] Decision-to-Initiative mandatory linking (decisions must belong to an initiative) — #31
- [ ] Process templates (governance checklists per initiative type) — #32
- [ ] Governance compliance view per initiative (decisions made, pending, template adherence) — #33

### Public Presence
- [ ] Public landing page at `/` — value proposition, highlights, CTA — #34
  *Supersedes ADR-003 as noted in that document.*

---

## Phase 3 — Visibility & Reporting

> Teams that can't measure their governance can't improve it.
> Reporting is the bridge between data and decisions.

### Reporting Module
- [ ] Reporting module scaffold (PDF/Excel generation infrastructure) — #35
- [ ] Governance report PDF export per workspace — #36
- [ ] CSV/Excel export for initiatives and decisions — #37
- [ ] Advanced governance KPI dashboard (trends, resolution rates, debt age) — #43

### Visibility Features
- [ ] Technical debt tagging (decisions and initiatives flagged as tech debt, with age) — #38
- [ ] ADR timeline view (visual chronological view of decisions per initiative/workspace) — #39
- [ ] Workspace governance health report (aggregate: all initiatives, all decisions) — #43 *(same issue)*

### Async Notifications
- [ ] Queue workers setup (Laravel queues + Redis driver infrastructure) — #40
- [ ] Email notifications (workspace events: decisions created, health score dropped, etc.) — #41
- [ ] Slack / Discord webhook notifications (configurable per workspace) — #42

---

## Phase 4 — API & Integrations

> Orquestra stops being an island. Governance decisions drive action in external systems.

### REST API
- [ ] REST API scaffold (Laravel Sanctum, token-per-workspace, versioned routes) — #44
- [ ] Initiatives API (CRUD + filtering + health score exposure) — #45
- [ ] Decisions API (CRUD + filtering + lifecycle transitions) — #46
- [ ] OpenAPI documentation (Scribe, auto-generated) — #49

### Webhooks & Integrations
- [ ] Webhook emitter (domain events → configurable external endpoint per workspace) — #47
- [ ] Aegis integration — decisions generate tracked work items via Aegis ticket API — #48
  *Requires Aegis Phase 1 (ticket CRUD + REST API) to be complete.*
  *Workflow: decision → "Generate ticket" → Aegis API → client system polls Aegis for their tickets.*
- [ ] Sentinel SDK integration (feature flags per workspace via Sentinel PHP SDK) — #50
  *Requires Sentinel Phase 1 (Go server + PHP SDK) to be complete.*

### Developer Tooling
- [ ] CLI tooling (`orquestra adr list`, `orquestra initiative show`, etc.) — #51

---

## Phase 5 — Monetization

> Orquestra becomes a commercial product.

- [ ] Billing module scaffold (domain model: plans, subscriptions, limits) — #52
- [ ] Stripe subscriptions integration (checkout, billing portal, webhooks) — #53
- [ ] Workspace tier system and usage limits enforcement — #54
- [ ] Billing UI (plans page, invoice history, payment method management) — #55
- [ ] Process template marketplace (community templates, import/fork) — #56

---

## Long-Term Vision

- Public API ecosystem (third-party governance integrations)
- SDK for embedding governance widgets into client applications
- Advanced metrics engine (workspace-level SLOs for governance quality)
- Cross-workspace benchmarking (opt-in: how does your team compare?)
- Possible extraction of high-traffic modules into microservices
