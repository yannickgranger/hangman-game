#!/bin/sh
set -e
UID=1000
env COMPOSER_HOME=/composer
setfacl -R -m u:www-data:rwX -m u:"${UID}":rwX /srv/app/var/cache
setfacl -dR -m u:www-data:rwX -m u:"${UID}":rwX /srv/app/var/cache
setfacl -R -m u:www-data:rwX -m u:"${UID}":rwX /srv/app/var/log
setfacl -dR -m u:www-data:rwX -m u:"${UID}":rwX /srv/app/var/log
setfacl -R -m u:www-data:rwX -m u:"${UID}":rwX /composer
setfacl -dR -m u:www-data:rwX -m u:"${UID}":rwX /composer

composer install --prefer-dist --no-progress --no-interaction
echo "build ok"

exec docker-php-entrypoint "$@"
