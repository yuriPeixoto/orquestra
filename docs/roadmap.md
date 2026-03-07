# Roadmap

## Phase 1 — Foundation (Current)

### Infrastructure & Tooling
- [x] Project structure (modular monolith)
- [x] Core documentation (vision, architecture, ADRs)
- [x] Linting & formatting (ESLint, Prettier, PHP CS Fixer) — #12
- [x] Testing structure (Pest) — #10

### Auth & Multi-Tenancy
- [x] Authentication module (register, login, password reset) — #1
- [x] Role & permission system (Spatie Permission) — #2
- [x] Workspace entity (creation, membership) — #3
- [x] Workspace middleware (tenant context enforcement) — #4

### Core Domain
- [x] Team module (creation, membership, role validation) — #5
- [x] Initiative entity (CRUD, workspace-scoped) — #6
- [x] Basic Kanban board UI (drag & drop, status update) — #7
- [ ] Decision entity — ADR registry (CRUD + link to initiative + UI) — #8
- [ ] Basic dashboard (initiative count by status, recent decisions, team count) — #9

### Documentation
- [ ] ADR policy & template — #11

---

## Phase 2 — Governance Core

- Activity logs (Spatie Activity Log)
- Metrics snapshot
- Reporting overview
- Initiative health indicators

---

## Phase 3 — Operational Intelligence

- Initiative health scoring
- Technical debt tracking
- Basic analytics
- Historical reporting

---

## Phase 4 — Advanced Governance

- Feature flags module
- Billing module
- API exposure
- Public documentation

---

## Long-Term Vision

- Public API
- CLI integration
- Advanced metrics engine
- Possible extraction of modules into services
