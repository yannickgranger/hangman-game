#!/bin/sh
set -e

# Check if socket exists (adjust path if needed)
if [ ! -S /var/run/php/php-fpm.sock ]; then
  echo "FPM socket not found: /var/run/php/php-fpm.sock"
  exit 1
fi

# Check FPM status
if env -i REQUEST_METHOD=GET SCRIPT_NAME=/ping SCRIPT_FILENAME=/ping cgi-fcgi -bind -connect /var/run/php/php-fpm.sock; then
  exit 0
fi

exit 1

#curl --unix-socket /var/run/php/php-fpm.sock http://localhost:status?full