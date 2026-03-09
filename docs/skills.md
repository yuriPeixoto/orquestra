# Claude Code Skills — Orquestra

Skills are slash commands available inside Claude Code sessions on this project.
They are defined in `.claude/commands/` and invoked with `/skill-name`.

---

## Available Skills

### `/sprint <issue-number(s)>`

**Purpose:** Start a sprint. Reads the issue(s), creates the correct feature branch, consults relevant docs, and presents an implementation plan for approval before writing any code.

**When to use:** At the beginning of every work session on a new issue.

**What it does:**
1. Reads each issue via `gh issue view <number>`
2. Verifies current branch — switches to `develop` and pulls if needed
3. Creates the feature branch: `feature/<issue-slug>` (or a shared slug for related issues)
4. Consults `docs/roadmap.md`, `docs/adr/002-module-layer-conventions.md`, and `CLAUDE.md`
5. Presents a plan: files to create, files to modify, implementation order, tests needed
6. Waits for approval before writing code

**Examples:**
```bash
/sprint 13        # single issue
/sprint 13 14 15  # multiple related issues in one sprint
```

**Rules enforced:**
- Never starts coding without an approved plan
- Always creates a feature branch before touching any file
- Runs `vendor/bin/pint --test` before every commit
- Runs `npm run type-check` before every commit that touches `.tsx` files

---

### `/pr`

**Purpose:** Create a Pull Request from the current feature branch to `develop`, following the project's PR template.

**When to use:** After all commits for the current issue(s) are done and tests pass.

**What it does:**
1. Reads `git diff develop...HEAD` and `git log` to understand all changes
2. Generates a PR title (under 70 chars) and structured body
3. Pushes the branch to origin if not already pushed
4. Creates the PR via `gh pr create` with:
   - Summary (bullet points of what changed and why)
   - Test plan (checklist of what was tested)

**PR body format:**
```markdown
## Summary
- bullet points

## Test plan
- [ ] checklist items

🤖 Generated with Claude Code
```

---

### `/new-module <ModuleName>`

**Purpose:** Scaffold a new module following the 4-layer modular monolith architecture.

**When to use:** When starting implementation of a new domain module (e.g., `Reporting`, `Billing`).

**What it creates:**
```
app/Modules/<ModuleName>/
├── Domain/
│   └── .gitkeep
├── Application/
│   └── .gitkeep
├── Infrastructure/
│   └── .gitkeep
└── Interfaces/
    ├── Http/
    │   ├── Controllers/
    │   └── Requests/
    └── routes.php
```

**What it registers:**
- Registers `routes.php` in `routes/web.php`
- Optionally creates a base migration if a model is needed

**Example:**
```bash
/new-module Reporting
/new-module Billing
/new-module FeatureFlags
```

**Layer responsibilities** (from ADR-002):

| Layer | Contents | Rule |
|---|---|---|
| `Domain/` | Enums, Value Objects, Events | No framework dependencies |
| `Application/` | Actions (use cases) | Named as verbs: `CreateInitiative`, `UpdateDecisionStatus` |
| `Infrastructure/` | Eloquent models, factories, repos | Framework-dependent implementations |
| `Interfaces/` | Controllers, Requests, routes.php | Thin — orchestrates, does not contain logic |

---

## UI/UX Pro Max (external skill)

For any frontend work — components, layouts, pages, design system decisions — activate the **UI/UX Pro Max** skill.

**Source:** https://github.com/nextlevelbuilder/ui-ux-pro-max-skill

**Guarantees:** WCAG AA accessibility, responsive design, coherent design system, 67 styles, 96 palettes.

**When to activate:** Any time a `.tsx` file is created or significantly modified as part of a feature.

---

## Notes

- Skills live in `.claude/commands/` as markdown files
- They are loaded automatically by Claude Code in this project
- If a skill behaves unexpectedly, check `.claude/commands/<skill-name>.md` for the current definition
