# ADR-001: Adopt Modular Monolith Architecture

## Status
Accepted

## Context

Orquestra requires structured domain separation while maintaining low operational complexity.

A microservices architecture would increase:

- Infrastructure overhead
- Deployment complexity
- Cognitive load
- Maintenance cost

Given the current product stage, microservices would be premature.

---

## Decision

Adopt a Modular Monolith architecture.

Modules will be separated by domain boundaries within the same application codebase.

---

## Consequences

### Positive

- Clear domain ownership
- Simplified deployment
- Reduced infrastructure complexity
- Easier debugging

### Negative

- Requires discipline to avoid cross-module coupling
- Scaling strategies may require refactoring in the future

---

## Review Date

To be reviewed after Phase 3 completion.
