#!/bin/sh
set -e

PORT="${PORT:-8080}"

# Suppress Apache FQDN warning.
echo "ServerName localhost" > /etc/apache2/conf-available/railway-servername.conf
a2enconf railway-servername >/dev/null

# Make sure Apache has exactly one MPM loaded at runtime.
a2dismod mpm_event mpm_worker mpm_itk 2>/dev/null || true
a2enmod mpm_prefork rewrite >/dev/null

# Railway provides PORT dynamically.
sed -ri "s/^Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s#<VirtualHost \*:80>#<VirtualHost *:${PORT}>#g" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
