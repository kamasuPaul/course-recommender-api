FROM php:8.0-apache
#install system depencies
RUN apt-get update && apt-get install -y git curl zip unzip
# install php extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath
# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# copy project files
COPY . /var/www/
# setup working directory
WORKDIR /var/www/
# run composer install
RUN composer install
# run composer dump-autoload
RUN composer dump-autoload -o
# generate env file
RUN touch .env
# copy .env.example to .env
COPY .env.example .env
# generate app key
RUN php artisan key:generate
# clear application config cache
RUN php artisan config:clear
# regenerate config cache
# RUN php artisan config:cache
# make the laravel storage directory writabe
RUN chmod -R 777 storage
# change owner of /var/www to www-data
# RUN chown -R www-data:www-data /var/www


# setup apache
# enable mod_rewrite
RUN a2enmod rewrite
# copy apache config
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
# expose port 80
EXPOSE 80
