#!/bin/bash
# Auto-runs Pint on PHP files after Edit/Write.
# Outputs a warning if style issues are found — does NOT block Claude.

INPUT=$(cat)
FILE=$(echo "$INPUT" | python3 -c "
import json, sys
try:
    d = json.load(sys.stdin)
    print(d.get('tool_input', {}).get('file_path', ''))
except:
    print('')
" 2>/dev/null)

# Only act on PHP files inside app/
if [[ "$FILE" == *.php ]] && [[ "$FILE" == *app/* ]]; then
    if ! vendor/bin/pint --test "$FILE" > /dev/null 2>&1; then
        echo "⚠ Pint style issue detected in: $FILE"
        echo "  Run: vendor/bin/pint $FILE"
    fi
fi
