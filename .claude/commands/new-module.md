Você está criando um novo módulo no Orquestra seguindo a arquitetura modular estabelecida.

Nome do módulo: $ARGUMENTS

Siga exatamente a estrutura dos módulos existentes (ex: Initiatives, Decisions):

1. **Crie a estrutura de diretórios:**
```
app/Modules/<Name>/
├── Domain/
│   └── .gitkeep
├── Application/
│   └── .gitkeep
├── Infrastructure/
│   └── .gitkeep
└── Interfaces/
    └── .gitkeep
```

2. **Leia os módulos existentes** para entender os padrões antes de criar qualquer arquivo:
   - `app/Modules/Initiatives/` — referência completa
   - `app/Modules/Decisions/` — referência mais recente

3. **Apresente o plano** de quais entidades, enums, actions, controller e rotas serão necessários baseado no escopo descrito.

4. **Crie na ordem correta:**
   - Migration
   - Domain Enum (status, types)
   - Infrastructure Model (com `BelongsToWorkspace` se for tenant-scoped)
   - Factory
   - Application Actions (verbos: Create, Update, Delete)
   - Interfaces Request
   - Interfaces Controller
   - Interfaces routes.php
   - Registrar em `routes/web.php`
   - Pages React (Index, Create, Show, Edit)
   - Testes em `tests/Feature/<Module>/`

5. **Adicionar permissões** se o módulo tiver ACL:
   - Adicionar ao enum `PermissionName`
   - Atualizar `RoleAndPermissionSeeder`

6. **Verificar Pint** antes de qualquer commit.

Regras de ouro:
- Nenhum import direto de model de outro módulo no Domain/Infrastructure
- Controllers no Interfaces layer podem orquestrar entre módulos
- Actions são nomeadas como verbos: `CreateX`, `UpdateX`, `DeleteX`
- Workspace relationship via `BelongsToWorkspace` trait, não manualmente
