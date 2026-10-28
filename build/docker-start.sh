#!/bin/sh
set -eu

# Keep frequent PHP dependency reads on Docker's Linux filesystem.
vendor_hash="$(sha256sum composer.lock | cut -d ' ' -f 1)"
cached_hash="$(cat vendor/.source-lock 2>/dev/null || true)"
if [ "$vendor_hash" != "$cached_hash" ] || [ ! -f vendor/autoload.php ]; then
    if [ ! -f /opt/akaunting-host-vendor/autoload.php ]; then
        echo 'PHP dependencies are missing. Run composer install in the project directory.' >&2
        exit 1
    fi
    echo 'Synchronizing PHP dependencies into the Docker volume...'
    cp -a /opt/akaunting-host-vendor/. vendor/
    printf '%s\n' "$vendor_hash" > vendor/.source-lock
fi

php artisan module:publish --no-interaction
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache
exec apache2-foreground
