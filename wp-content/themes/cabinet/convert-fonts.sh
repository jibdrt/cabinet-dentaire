#!/usr/bin/env bash
set -euo pipefail

# Default to ./assets/fonts (relative to /theme)
FONT_DIR="${1:-./assets/fonts/sebino}"

need() { command -v "$1" >/dev/null 2>&1 || { echo "Erreur: '$1' introuvable."; exit 1; }; }
need fontforge
need woff2_compress

PE_SCRIPT="$(mktemp -t ttf2woff.XXXXXX.pe)"
cat > "$PE_SCRIPT" <<'PEE'
Open($1);
Generate($1:r + ".woff");
PEE

FOUND=0
for f in "$FONT_DIR"/*.ttf "$FONT_DIR"/*.otf; do
  [ -e "$f" ] || continue
  FOUND=1
  echo "→ Conversion: $(basename "$f")"
  fontforge -script "$PE_SCRIPT" "$f"    # WOFF
  woff2_compress "$f"                    # WOFF2
done

rm -f "$PE_SCRIPT"

if [ "$FOUND" -eq 0 ]; then
  echo "Aucune police .ttf/.otf trouvée dans $FONT_DIR"
  exit 0
fi

echo "✅ Terminé. Fichiers générés dans: $FONT_DIR"
