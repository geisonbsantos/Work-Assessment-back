# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Tudo mora no Obsidian (cofre Safe_WA)

Toda a documentação e as regras de trabalho deste projeto ficam no cofre Obsidian
**Safe_WA** — **não** neste arquivo.

- Local: `/mnt/c/Users/USUÁRIO/Desktop/Obsidian/Safe_WA` (caminho em `WA_OBSIDIAN_VAULT`).
- O hook `UserPromptSubmit` (`.claude/hooks/obsidian-consulta.sh`) injeta, a **cada mensagem**,
  o índice das notas do cofre e o conteúdo de **`_Regras.md`** (as regras de trabalho).
- **Antes de responder ou escrever código:** leia as notas pertinentes e siga `_Regras.md`.

Pontos de entrada no cofre:

| Nota | Conteúdo |
| --- | --- |
| `_Regras.md` | Regras de trabalho: consulta obrigatória, fluxo RPI → testes → docs → suíte verde |
| `Índice.md` | Mapa do cofre |
| `PRD/` · `SDD/` | Produto e arquitetura |
| `RPI/` | Uma nota por feature (Requisitos → Planejamento → Implementação) |
| `Referências/Setup e Comandos.md` | Instalação, comandos, Docker, ferramentas |
| `Referências/Convenções de Código.md` | Checklist de como codar aqui |
| `Referências/Padrões de Testes.md` | Padrões e exemplos de teste |
| `Referências/Revisão Técnica — 2026-08-26.md` | Achados da última revisão + RPI-0002/0003/0004 |

Se o cofre não estiver acessível, o hook avisa; ajuste `WA_OBSIDIAN_VAULT` em
`.claude/settings.local.json`.
