# Orquestra
Technical Operations & Governance Platform

---

## 1. Product Vision

Orquestra is a multi-tenant SaaS platform designed to support technical teams in structuring operational workflows, documenting architectural decisions, and aligning initiatives with strategic goals.

It combines technical governance and operational coordination in a single structured environment.

Orquestra is not a task manager.
It is a governance layer for engineering execution.

---

## 2. Target Audience

- Engineering Managers
- Technical Project Managers
- Tech Leads
- Small and Medium Engineering Teams
- Remote-first technical teams

---

## 3. Problem Statement

Technical teams frequently struggle with:

- Unstructured architectural decisions
- Roadmap misalignment
- Lack of initiative traceability
- Invisible technical debt
- Missing operational metrics
- Knowledge fragmentation across tools

Orquestra aims to centralize governance without increasing bureaucracy.

---

## 4. Core Value Proposition

- Structured decision tracking (ADR registry)
- Initiative governance with historical traceability
- Multi-tenant architecture for team isolation
- Operational metrics tied to initiatives
- Documentation-first development philosophy

---

## 5. Why Orquestra is Not a Task Manager

This distinction is not cosmetic. It shapes every feature priority decision.

A task manager answers: **what needs to be done?**
Orquestra answers: **are we making good decisions, and can we prove it?**

| Task Manager | Orquestra |
|---|---|
| Creates tasks, moves status | Tracks *why* a decision was made, who decided, what alternatives were considered |
| Assigns work to people | Establishes decision authority and accountability per workspace |
| Shows deadline vs. completion | Calculates initiative health: activity recency, open decisions, overdue governance |
| Activity log is optional | Full audit trail is mandatory — every change is traceable via Spatie Activity Log |
| Dashboard shows task counts | Governance reports: X decisions this quarter, Y unresolved, Z initiatives at risk |
| No concept of process compliance | Compliance view: did this initiative follow the required governance checklist? |
| Technical debt is invisible | Decisions and initiatives can be explicitly tagged as technical debt, with age tracking |
| Kanban is the product | Kanban is the surface — governance intelligence is the product |

The Kanban board exists because initiatives have statuses. But health scoring, decision lifecycle, process templates, audit trails, and governance reports are what make Orquestra a governance tool.

The integration story reinforces this: a governance decision in Orquestra can generate a tracked work item in Aegis, which a client system (e.g., a Fleet Management platform) then consumes and acts on. Task managers don't close loops across systems.

---

## 6. Architecture Model

**Architecture Style:** Modular Monolith

**Rationale:**
- Reduced operational complexity
- Clear domain boundaries
- Easier maintainability
- Lower infrastructure overhead
- Prepared for future extraction if needed (not planned initially)

---

## 7. Multi-Tenant Strategy

**Tenant Model:** Workspace-based isolation

**Implementation Strategy:**
- `workspace_id` present in all tenant-scoped tables
- Global middleware enforcing tenant context
- Authorization layer scoped per workspace

No database-per-tenant strategy at this stage.

---

## 8. Technology Stack

### Backend
- Laravel 12
- PostgreSQL
- Redis
- Spatie Permission
- Spatie Activity Log
- Pest (testing)

### Frontend
- Inertia.js
- React
- TypeScript
- TanStack Query
- Zustand
- TailwindCSS

### Infrastructure
- Local PHP installation
- Local Postgres
- JetBrains PHPStorm + DataGrip
- Claude Code
- GitHub Actions (CI)
- ESLint + Prettier
- PHP CS Fixer

---

## 9. Initial Domain Modules

```
app/Modules/
├── Auth/
├── Workspaces/
├── Teams/
├── Initiatives/
├── Decisions/         # ADR registry
├── Reporting/
├── Billing/           # future
└── FeatureFlags/      # future
```

Each module must:
- Encapsulate domain logic
- Expose clear service layer
- Avoid cross-module tight coupling

---

## 10. Initial MVP Scope (Phase 1)

- User authentication
- Workspace creation
- Team creation
- Initiative creation
- Basic Kanban board
- Decision registry (simple ADR entry)
- Basic dashboard (initiative overview)

> No billing in Phase 1.

---

## 11. Documentation Standards

Orquestra follows documentation-first governance.

**Required documents:**
- `vision.md`
- `stakeholders.md`
- `roadmap.md`
- `risks.md`
- `architecture.md`
- `adr/001-modular-monolith.md`

**Commit message pattern:**
```
feat(scope): description
refactor(scope): description
docs(scope): description
chore(scope): description
```

> NEVER commit as Claude Code.

---

## 12. Internationalization Policy

- Interface prepared for i18n (pt-BR / en)
- Primary documentation in English
- `README.pt-BR.md` provided
- Terminology standardized in English

---

## 13. Guiding Principles

> Governance over improvisation.
> Clarity over complexity.
> Structure over volume.
> Product over code.
> Sustainability over speed.

---

*Orquestra Base Document v0.2 — updated 2026-03-09*
