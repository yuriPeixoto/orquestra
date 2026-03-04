# ADR-002: Define Module Internal Layer Conventions

## Status
Accepted

## Context

The modular monolith structure requires clear rules about where each type of logic lives inside a module.

Without explicit conventions, developers will make inconsistent decisions, leading to:

- Logic leaking between layers
- Controllers becoming repositories
- Domain polluted with framework dependencies
- Cross-module coupling via Eloquent models

## Decision

Adopt a four-layer structure inside each module with strict responsibilities per layer.

---

## Layer Responsibilities

### Domain

Contains:
- Domain Models
- Value Objects
- Domain Events

Must NOT contain:
- Controllers
- Framework-dependent code
- Request validation

---

### Application

Contains:
- Actions (Use Cases)
- DTOs
- Application Services

Responsibilities:
- Orchestrate domain logic
- Coordinate repositories
- Enforce business rules

---

### Infrastructure

Contains:
- Eloquent models (if separated from Domain)
- Repository implementations
- Persistence logic
- External integrations

Framework-dependent code is allowed here.

---

### Interfaces

Contains:
- HTTP Controllers
- Form Requests
- API Resources
- Route definitions

This is the delivery layer. No business logic here.

---

## Naming Conventions

- Actions must be named as verbs: `CreateWorkspace`, `RegisterUser`, `CreateInitiative`
- DTOs must be immutable
- No direct cross-module model imports
- Cross-module communication only via:
  - Interfaces
  - Domain Events

## Consequences

### Positive

- Consistent structure across all modules
- Clear ownership of logic per layer
- Easier onboarding for contributors
- Domain remains framework-agnostic

### Negative

- Requires discipline to enforce
- More directories for simple operations

---

## Review Date

To be reviewed after Phase 2 completion.
