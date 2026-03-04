# Architecture

## Architectural Style

Modular Monolith

---

## Rationale

- Lower operational overhead
- Clear domain separation
- Simplified deployment
- Easier refactoring
- Better long-term maintainability

Prepared for future extraction if necessary.

---

## High-Level Structure

```
app/
└── Modules/
    ├── Auth/
    ├── Workspaces/
    ├── Teams/
    ├── Initiatives/
    ├── Decisions/
    ├── Reporting/
    ├── Billing/      (future)
    └── FeatureFlags/ (future)
```

Each module must:

- Encapsulate domain logic
- Provide service layer
- Avoid direct cross-module coupling
- Communicate via interfaces or events

---

## Multi-Tenant Strategy

Workspace-based tenant isolation.

Implementation:

- workspace_id scoped models
- Middleware enforcing tenant context
- Policy-based authorization
- Role-based permissions per workspace

No database-per-tenant strategy.

---

## Backend Stack

- Laravel 12
- PostgreSQL
- Redis
- Spatie Permission
- Spatie Activity Log
- Pest

---

## Frontend Stack

- Inertia.js
- React
- TypeScript
- TanStack Query
- Zustand
- TailwindCSS

---

## Testing Strategy

- Integration tests for modules
- Domain logic tested via Pest
- Critical flows covered before feature expansion

---

## Documentation Strategy

All architectural decisions must generate an ADR.
