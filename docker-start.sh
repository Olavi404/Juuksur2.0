#!/usr/bin/env sh
set -eu

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
