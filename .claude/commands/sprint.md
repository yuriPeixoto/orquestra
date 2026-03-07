Você está iniciando uma sprint no projeto Orquestra.

Issues desta sprint: $ARGUMENTS

Execute os seguintes passos:

1. **Leia cada issue** usando `gh issue view <number> --repo yuriPeixoto/orquestra` para entender o escopo completo e os acceptance criteria.

2. **Verifique o branch atual** com `git branch` e `git status`. Se não estiver na develop, mude para ela e faça pull.

3. **Crie o branch da feature** seguindo o padrão:
   - Uma issue: `feature/<slug-da-issue>`
   - Múltiplas issues relacionadas: `feature/<slug-geral>`
   - Comando: `git checkout develop && git pull origin develop && git checkout -b feature/<slug>`

4. **Consulte os docs relevantes** antes de qualquer código:
   - `docs/roadmap.md` para contexto da phase atual
   - `docs/adr/002-module-layer-conventions.md` para confirmar onde cada tipo de código vai
   - CLAUDE.md para anti-patterns e workflow

5. **Apresente um plano de implementação** para cada issue:
   - Arquivos a criar (com qual layer/responsabilidade)
   - Arquivos a modificar
   - Ordem de implementação
   - Testes necessários

6. **Aguarde aprovação** antes de começar a codificar.

Lembre sempre:
- Pint antes de qualquer commit (`vendor/bin/pint --test`)
- `php -l` em todos os arquivos PHP novos
- `npm run type-check` para qualquer tsx alterado
- Nunca `git add -A` — sempre arquivos específicos
- Se a issue envolver frontend, ative a skill UI/UX Pro Max para decisões de design
