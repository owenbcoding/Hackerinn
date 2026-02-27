#!/usr/bin/env bash
set -e

# Same setup as Sail's start-container
if [ "$SUPERVISOR_PHP_USER" != "root" ] && [ "$SUPERVISOR_PHP_USER" != "sail" ]; then
    echo "You should set SUPERVISOR_PHP_USER to either 'sail' or 'root'." >&2
    exit 1
fi

if [ -n "$WWWUSER" ]; then
    usermod -u "$WWWUSER" sail 2>/dev/null || true
fi

if [ ! -d /.composer ]; then
    mkdir /.composer
fi
chmod -R ugo+rw /.composer

# Wait for MySQL to be ready
echo "Waiting for MySQL to be ready..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if mysqladmin ping -h"${DB_HOST}" -u"${DB_USERNAME}" -p"${DB_PASSWORD}" --silent 2>/dev/null; then
        echo "MySQL is ready!"
        break
    fi
    echo "Attempt $attempt/$max_attempts: MySQL not ready yet, waiting..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "Warning: MySQL did not become ready in time. Continuing anyway..."
fi

# Run migrations as sail user
echo "Running migrations..."
gosu sail php /var/www/html/artisan migrate --force 2>/dev/null || true

# Start supervisord (same as Sail's default start-container)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
