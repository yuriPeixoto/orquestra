Você está criando um Pull Request para o projeto Orquestra.

Contexto adicional (se fornecido): $ARGUMENTS

Execute os seguintes passos:

1. **Verifique o estado atual:**
   ```bash
   git status
   git log --oneline develop..HEAD
   git diff develop...HEAD --stat
   ```

2. **Confirme que está tudo pronto:**
   - `vendor/bin/pint --test` passa sem erros
   - `npm run type-check` passa sem erros
   - Todos os arquivos relevantes foram commitados

3. **Crie o PR** via `gh pr create` com o template abaixo.
   Base branch: `develop`. Target de merge final: `main` (via develop).

Template do PR:
```
gh pr create --base develop --title "<título conciso em inglês>" --body "$(cat <<'EOF'
## Summary

- <bullet point do que foi implementado>
- <bullet point das decisões técnicas relevantes>

## Acceptance Criteria

- [ ] <criterion 1 da issue>
- [ ] <criterion 2 da issue>

## Test Plan

- [ ] Testes unitários/feature passando
- [ ] `vendor/bin/pint --test` passando
- [ ] `npm run type-check` passando
- [ ] Testado manualmente no fluxo principal

## Notes

<qualquer contexto adicional, trade-offs, ou decisões que o reviewer precisa saber>

Closes #<número da issue>
EOF
)"
```

4. **Retorne a URL do PR** criado.
