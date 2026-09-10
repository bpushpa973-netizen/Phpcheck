FROM php:8.2-apache

# PHP + MySQL support
RUN docker-php-ext-install pdo pdo_mysql mysqli \
    # Apache must have exactly ONE MPM enabled
    && a2dismod mpm_event mpm_worker mpm_prefork mpm_itk 2>/dev/null || true \
    && a2enmod mpm_prefork rewrite

WORKDIR /var/www/html
COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod +x /var/www/html/start.sh

EXPOSE 8080
CMD ["/var/www/html/start.sh"]
