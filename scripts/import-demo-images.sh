#!/usr/bin/env bash
set -euo pipefail

# Import simple demo images on a fresh server:
# - Copy built-in catalog images into public/upload/demo
# - Point base settings (logo/favicon/placeholder) to those demo files

APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_DIR"

echo "[1/4] Checking required files..."
for f in public/catalog/logo.png public/catalog/favicon.png public/catalog/placeholder.png; do
  if [[ ! -f "$f" ]]; then
    echo "Missing required file: $f"
    exit 1
  fi
done

echo "[2/4] Copying demo files to public/upload/demo..."
mkdir -p public/upload/demo
cp -f public/catalog/logo.png public/upload/demo/logo-demo.png
cp -f public/catalog/favicon.png public/upload/demo/favicon-demo.png
cp -f public/catalog/placeholder.png public/upload/demo/placeholder-demo.png

if [[ -f public/catalog/banner.png ]]; then
  cp -f public/catalog/banner.png public/upload/demo/banner-demo.png
fi

echo "[3/4] Updating settings paths in database..."
php artisan tinker --execute="
DB::table('settings')->updateOrInsert(
  ['type' => 'system', 'space' => 'base', 'name' => 'logo'],
  ['value' => 'upload/demo/logo-demo.png', 'json' => 0]
);
DB::table('settings')->updateOrInsert(
  ['type' => 'system', 'space' => 'base', 'name' => 'favicon'],
  ['value' => 'upload/demo/favicon-demo.png', 'json' => 0]
);
DB::table('settings')->updateOrInsert(
  ['type' => 'system', 'space' => 'base', 'name' => 'placeholder'],
  ['value' => 'upload/demo/placeholder-demo.png', 'json' => 0]
);
"

echo "[4/4] Clearing Laravel cache..."
php artisan optimize:clear >/dev/null

echo "Done. Demo image paths now point to:"
echo "  - upload/demo/logo-demo.png"
echo "  - upload/demo/favicon-demo.png"
echo "  - upload/demo/placeholder-demo.png"
