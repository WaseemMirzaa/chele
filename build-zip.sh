#!/usr/bin/env bash
#
# Package the Chelé theme into an installable WordPress ZIP.
# Produces ./chele.zip containing a single top-level chele/ folder, ready for
# Appearance → Themes → Add New → Upload Theme.

set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_DIR="chele"
OUTPUT="chele.zip"

cd "$ROOT"

if [ ! -f "$THEME_DIR/style.css" ]; then
	echo "Error: $THEME_DIR/style.css not found. Run this from the project root." >&2
	exit 1
fi

rm -f "$OUTPUT"

if command -v zip >/dev/null 2>&1; then
	zip -r -q "$OUTPUT" "$THEME_DIR" \
		-x "*.DS_Store" "*/.git/*" "*/node_modules/*" "*.map"
else
	# Fallback to Python if the zip binary is unavailable.
	python3 - "$THEME_DIR" "$OUTPUT" <<'PY'
import os, sys, zipfile
theme, out = sys.argv[1], sys.argv[2]
with zipfile.ZipFile(out, "w", zipfile.ZIP_DEFLATED) as z:
    for base, _dirs, files in os.walk(theme):
        if ".git" in base or "node_modules" in base:
            continue
        for f in files:
            if f == ".DS_Store" or f.endswith(".map"):
                continue
            p = os.path.join(base, f)
            z.write(p, p)
PY
fi

echo "✅ Built $OUTPUT ($(du -h "$OUTPUT" | cut -f1))"
echo "   Upload it via WordPress → Appearance → Themes → Add New → Upload Theme."
