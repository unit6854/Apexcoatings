#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════
#  Apex Coatings & Engraving — Deploy Script
#  Uploads changed theme files to apexcoatingstn.com via FTP
#
#  Usage:
#    ./deploy.sh           — upload files changed since last deploy
#    ./deploy.sh --all     — force upload ALL theme files
#    ./deploy.sh --dry     — preview what would be uploaded (no upload)
# ═══════════════════════════════════════════════════════════════

FTP_USER="apexprve"
FTP_PASS="JJibwREbwPRx"
FTP_HOST="apexcoatingstn.com"
REMOTE_THEME="/public_html/wp-content/themes/apex-block-theme"
LOCAL_THEME="/c/Users/unit_/Local Sites/apex-coatings-and-engraving/app/public/wp-content/themes/apex-block-theme"
STAMP="$LOCAL_THEME/.last-deploy"

# ── Parse flags ─────────────────────────────────────────────────
FORCE_ALL=false
DRY_RUN=false
for arg in "$@"; do
  case $arg in
    --all) FORCE_ALL=true ;;
    --dry) DRY_RUN=true ;;
  esac
done

# ── Header ──────────────────────────────────────────────────────
echo ""
echo "  ╔═══════════════════════════════════════╗"
echo "  ║  Apex Coatings — Theme Deploy         ║"
echo "  ║  → apexcoatingstn.com                 ║"
echo "  ╚═══════════════════════════════════════╝"
echo ""

if $DRY_RUN; then
  echo "  [DRY RUN — no files will be uploaded]"
  echo ""
fi

# ── Collect changed files ────────────────────────────────────────
if $FORCE_ALL || [[ ! -f "$STAMP" ]]; then
  if $FORCE_ALL; then
    echo "  Mode: FULL deploy (--all flag)"
  else
    echo "  Mode: FULL deploy (first run — no stamp found)"
  fi
  mapfile -d '' FILES < <(find "$LOCAL_THEME" -type f \
    ! -path "*/.git/*" \
    ! -path "*/.github/*" \
    ! -name ".last-deploy" \
    ! -name "*.DS_Store" \
    ! -name "Thumbs.db" \
    ! -name "*.swp" \
    ! -name "*.swo" \
    ! -name "*~" \
    ! -name "(colon*" \
    -print0)
else
  echo "  Mode: INCREMENTAL (changed since $(date -r "$STAMP" '+%Y-%m-%d %H:%M:%S'))"
  mapfile -d '' FILES < <(find "$LOCAL_THEME" -newer "$STAMP" -type f \
    ! -path "*/.git/*" \
    ! -path "*/.github/*" \
    ! -name ".last-deploy" \
    ! -name "*.DS_Store" \
    ! -name "Thumbs.db" \
    ! -name "*.swp" \
    ! -name "*.swo" \
    ! -name "*~" \
    ! -name "(colon*" \
    -print0)
fi
echo ""

# ── Nothing to do ────────────────────────────────────────────────
if [[ ${#FILES[@]} -eq 0 ]]; then
  echo "  ✓ Nothing changed since last deploy. You're up to date!"
  echo ""
  exit 0
fi

echo "  Files to upload: ${#FILES[@]}"
echo ""

# ── Upload ──────────────────────────────────────────────────────
COUNT=0
ERRORS=0
FAIL_LIST=()

for file in "${FILES[@]}"; do
  relative="${file#$LOCAL_THEME/}"
  remote="ftp://$FTP_USER:$FTP_PASS@$FTP_HOST$REMOTE_THEME/$relative"

  if $DRY_RUN; then
    echo "  [dry] $relative"
    ((COUNT++))
  else
    echo -n "  ↑ $relative ... "
    if curl -sk --ftp-create-dirs -T "$file" "$remote"; then
      echo "✓"
      ((COUNT++))
    else
      echo "✗ FAILED"
      FAIL_LIST+=("$relative")
      ((ERRORS++))
    fi
  fi
done

# ── Update stamp & summary ───────────────────────────────────────
echo ""
if ! $DRY_RUN; then
  touch "$STAMP"
fi

if [[ $ERRORS -eq 0 ]]; then
  if $DRY_RUN; then
    echo "  [DRY RUN] Would have uploaded $COUNT file(s)."
  else
    echo "  ✓ Deployed $COUNT file(s) to apexcoatingstn.com"
  fi
else
  echo "  ⚠  Deployed $COUNT file(s) — $ERRORS FAILED:"
  for f in "${FAIL_LIST[@]}"; do
    echo "     ✗ $f"
  done
fi
echo ""
