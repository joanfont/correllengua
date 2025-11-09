#!/bin/sh

EXECUTE_MIGRATIONS=${EXECUTE_MIGRATIONS:-"false"}
if [ "$EXECUTE_MIGRATIONS" = "true" ]; then
  echo "Executing database migrations..."
  ./bin/console doctrine:migrations:migrate --no-interaction --allow-no-migration --all-or-nothing
else
  echo "Skipping database migrations."
fi

exec "$@"
