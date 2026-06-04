#!/usr/bin/env bash
# Inyecta docs/RULES.md al iniciar la sesión del agente en este proyecto.
set -euo pipefail

RULES_FILE="docs/RULES.md"

if [[ ! -f "$RULES_FILE" ]]; then
  echo '{}'
  exit 0
fi

python3 - "$RULES_FILE" <<'PY'
import json
import sys

rules_path = sys.argv[1]
with open(rules_path, encoding="utf-8") as f:
    content = f.read()

header = (
    "## Reglas del proyecto (docs/RULES.md)\n\n"
    "Aplica estas reglas en cada respuesta e implementación:\n\n"
)

print(
    json.dumps(
        {"additional_context": header + content},
        ensure_ascii=False,
    )
)
PY
