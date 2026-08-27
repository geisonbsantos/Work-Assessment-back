#!/usr/bin/env bash
#
# UserPromptSubmit hook — Work Assessment
# A cada mensagem do usuário, injeta no contexto:
#   1. o índice das notas do cofre Obsidian (Safe_WA);
#   2. o conteúdo de _Regras.md — a FONTE DA VERDADE das regras de trabalho.
#
# As regras NÃO são hardcoded aqui: edite _Regras.md no cofre para mudá-las.
# O caminho do cofre vem de WA_OBSIDIAN_VAULT (.claude/settings.local.json);
# o valor abaixo é apenas o padrão para esta máquina.

VAULT="${WA_OBSIDIAN_VAULT:-/mnt/c/Users/USUÁRIO/Desktop/Obsidian/Safe_WA}"

if [ ! -d "$VAULT" ]; then
  echo "[Obsidian] AVISO: cofre não encontrado em: $VAULT"
  echo "[Obsidian] Ajuste WA_OBSIDIAN_VAULT em .claude/settings.local.json se o caminho mudou."
  exit 0
fi

echo "=================================================================="
echo "COFRE OBSIDIAN (Safe_WA) — CONSULTA OBRIGATÓRIA ANTES DE RESPONDER"
echo "Local: $VAULT"
echo "=================================================================="
echo
echo "Notas do cofre (leia as relevantes com Read, usando o caminho completo):"
find "$VAULT" -type f -name '*.md' \
  -not -path '*/.obsidian/*' -not -path '*/.trash/*' \
  | LC_ALL=C sort | sed 's/^/  - /' | head -300
echo
echo "------------------------------------------------------------------"
echo "REGRAS DE TRABALHO — de $VAULT/_Regras.md (edite lá para mudar):"
echo "------------------------------------------------------------------"
if [ -f "$VAULT/_Regras.md" ]; then
  cat "$VAULT/_Regras.md"
else
  echo "[Obsidian] AVISO: _Regras.md não encontrado no cofre. Crie-o para definir as regras."
fi
exit 0
