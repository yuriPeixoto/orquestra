# ADR-003: Root Route (`/`) Redirects to Login in Phase 1

**Status:** Accepted
**Date:** 2026-03-07

## Context

Orquestra is a B2B multi-tenant SaaS platform targeting engineering managers and tech leads.
During Phase 1 (Foundation MVP), there is no billing system, no public onboarding flow,
and no marketing copy ready for a public-facing landing page.

The question was whether `/` should serve a public landing page or redirect directly to authentication.

## Decision

In Phase 1, the root route `/` redirects:
- Authenticated users → `/dashboard`
- Unauthenticated users → `/login`

There is no public landing page in Phase 1.

## Rationale

- **No CTA to support**: A landing page without working billing, plans, or self-serve signup
  is decorative. It adds maintenance overhead with no user value.
- **Audience is internal/invited**: Phase 1 users are known — the platform is not yet
  discoverable or publicly marketed.
- **Pattern used by comparable tools**: Linear, early Basecamp, early Vercel — all started
  with login as the entry point before building a marketing layer.
- **Portfolio value is inside the app**: What impresses reviewers is the dashboard and
  governance features, not a landing page. The GitHub README and live demo communicate
  the product vision.

## Consequences

- New visitors who land on `/` are immediately prompted to log in or register.
- No marketing, pricing, or feature showcase exists at the root URL in Phase 1.
- The `Welcome.tsx` page (Breeze scaffold) is no longer served and can be removed.

## Future

Phase 2 will introduce a public landing page at `/` with:
- Product value proposition
- Feature highlights
- Pricing plans (tied to Billing module)
- Self-serve registration CTA

This ADR will be superseded by ADR-006 (planned) when the landing page is implemented.
