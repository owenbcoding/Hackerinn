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
# Ensure SQLite database exists and run migrations as sail user
touch /var/www/html/database/database.sqlite 2>/dev/null || true
gosu sail php /var/www/html/artisan migrate --force 2>/dev/null || true
# Start supervisord (same as Sail's default start-container)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
