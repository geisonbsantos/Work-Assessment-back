#!/usr/bin/env bash
#
# UserPromptSubmit hook — Work Assessment
# Injeta o índice do cofre Obsidian (Safe_WA) no contexto a cada mensagem
# do usuário, tornando a consulta à documentação obrigatória antes de responder.
#
# O caminho do cofre vem de WA_OBSIDIAN_VAULT (definido em .claude/settings.local.json);
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
echo "Regra do projeto:"
echo "  1. Antes de responder ou escrever código, leia as notas pertinentes"
echo "     (PRD, SDD, RPI e demais) e fundamente a resposta nelas."
echo "  2. Se a informação necessária não existir no cofre, diga isso"
echo "     explicitamente e proponha criar/atualizar a nota correspondente"
echo "     (PRD/SDD/RPI) antes de prosseguir."
echo "  3. Toda decisão de arquitetura ou requisito novo deve ser registrada"
echo "     no cofre — o código segue a documentação, não o contrário."
exit 0
