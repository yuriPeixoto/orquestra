# Orquestra — Claude Context

## What This Project Is

**Governance platform for engineering teams. NOT a task manager.**
Multi-tenant SaaS where the tenant unit is a **Workspace**.
Real origin: client pain in quality/process teams with no structured technical governance.

## Stack

- **Backend**: Laravel 12, PHP 8.4, PostgreSQL, Redis
- **Frontend**: React 18, TypeScript, Inertia.js, TailwindCSS
- **ACL**: Spatie Permission (team-mode, workspace-scoped)
- **Audit**: Spatie Activity Log
- **Testing**: Pest
- **Tooling**: Pint (PHP lint), ESLint (flat config), Prettier, GitHub Actions CI

## Architecture — Modular Monolith

```
app/Modules/{ModuleName}/
├── Domain/          # Enums, Value Objects, Events — NO framework deps
├── Application/     # Actions (use cases) — named as verbs: CreateInitiative
├── Infrastructure/  # Eloquent models, factories
└── Interfaces/      # Controllers, Requests, routes.php
```

Cross-module: communicate via interfaces or domain events only.
No direct Eloquent model imports between modules at Domain/Infrastructure layers.
(Interfaces/Controllers may orchestrate across modules.)

Each module's `routes.php` is registered in `routes/web.php`.

## Multi-Tenancy

- All tenant-scoped models use `BelongsToWorkspace` trait → global scope by `workspace_id`
- Middleware `workspace.context` binds `app('current.workspace')` per request
- Spatie Permission in team-mode: `setPermissionsTeamId($workspace->id)` scopes roles
- Roles: `workspace_owner`, `workspace_member`, `workspace_viewer`, `admin`
- Permission seeder: `database/seeders/RoleAndPermissionSeeder.php`

## Workflow (ALWAYS follow this)

```bash
# Start work
git checkout develop && git pull origin develop
git checkout -b feature/<slug>

# Before every commit
vendor/bin/pint --test app/Modules/...  # must pass
php -l <files>                          # syntax check
npm run type-check                      # must pass

# Commit
git add <specific files>  # never git add -A
git commit -m "feat(scope): description"
git push origin feature/<slug>
```

## Anti-Patterns — Known Pint Rules

```php
// WRONG — concat_space
'in:' . implode(',', $values)
// CORRECT
'in:'.implode(',', $values)

// WRONG — not_operator_with_successor_space
if (!isset($data['key']))
// CORRECT
if (! isset($data['key']))
```

Always run `vendor/bin/pint --test` before committing PHP. CI will fail otherwise.

## Testing

- Pest with `RefreshDatabase`
- SQLite driver NOT installed locally — tests run against PostgreSQL
- `beforeEach`: seed `RoleAndPermissionSeeder`, call `setPermissionsTeamId(null)`
- Test files: `tests/Feature/{Module}/`

## Current State

Phase 1 MVP in progress. See `docs/roadmap.md` for full phase breakdown.
Active branch: check `git branch` — always work from a feature branch.

## Skills Disponíveis

| Skill | Uso |
|-------|-----|
| `/sprint <issue(s)>` | Inicia sprint: lê issues, cria branch, apresenta plano |
| `/pr` | Cria PR com template padrão para develop |
| `/new-module <Name>` | Scaffolda novo módulo com 4 layers completas |

**UI/UX:** Para qualquer trabalho de interface, usar a skill **UI/UX Pro Max**
(https://github.com/nextlevelbuilder/ui-ux-pro-max-skill).
Garante: design system coerente, WCAG AA, responsivo, 67 estilos, 96 paletas.

## Key Docs

- `docs/vision.md` — product vision, principles, and **why this is not a task manager**
- `docs/roadmap.md` — all phases with issue numbers (Phase 1–5 + long-term vision)
- `docs/architecture.md` — architectural overview
- `docs/skills.md` — all available Claude Code skills with usage and examples
- `docs/adr/001-modular-monolith.md` — ADR: modular monolith architecture
- `docs/adr/002-module-layer-conventions.md` — ADR: layer responsibilities and naming
- `docs/adr/003-root-route-is-login.md` — ADR: `/` redirects to login in Phase 1
- `docs/portfolio-strategy.md` — broader portfolio context (personal reference)
