#!/usr/bin/env sh
set -eu

# Ensure APP_KEY is in Laravel-supported base64 format with valid length.
if ! php -r '$k=getenv("APP_KEY"); if(!is_string($k)||!str_starts_with($k,"base64:")) exit(1); $d=base64_decode(substr($k,7), true); if($d===false) exit(1); $l=strlen($d); exit(($l===16||$l===32)?0:1);'; then
  export APP_KEY="$(php artisan key:generate --show --no-interaction)"
  echo "Generated runtime APP_KEY because configured key was missing or invalid." >&2
fi

# Clear potentially stale cached configuration from previous image versions.
php artisan config:clear || true

# Wait for database and apply schema before serving traffic.
MAX_RETRIES=30
RETRY_DELAY=3
COUNT=1

until php artisan migrate --force; do
  if [ "$COUNT" -ge "$MAX_RETRIES" ]; then
    echo "Migration failed after ${MAX_RETRIES} attempts." >&2
    exit 1
  fi

  echo "Database not ready yet (attempt ${COUNT}/${MAX_RETRIES}). Retrying in ${RETRY_DELAY}s..." >&2
  COUNT=$((COUNT + 1))
  sleep "$RETRY_DELAY"
done

php artisan db:seed --force || true

exec php artisan serve --host 0.0.0.0 --port "${PORT:-10000}"
