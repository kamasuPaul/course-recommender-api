FROM php:7.4-apache
# install php extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath
# install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
# copy project files
COPY . /var/www/
# setup working directory
WORKDIR /var/www/
# run composer install
RUN composer install
# generate env file
RUN touch .env
# generate app key
RUN php artisan key:generate
# clear application config cache
RUN php artisan config:clear
# regenerate config cache
RUN php artisan config:cache
# make the laravel storage directory writabe
RUN chmod -R 777 storage
# change owner of /var/www to www-data
# RUN chown -R www-data:www-data /var/www

# setup env variables that will come when building the image
ENV APP_ENV=local
ENV APP_DEBUG=true
ENV DB_CONNECTION=mysql
ENV DB_HOST=localhost
ENV DB_DATABASE=laravel
ENV DB_USERNAME=root
ENV DB_PASSWORD=root
ENV DB_PORT=3306

# setup apache
# enable mod_rewrite
RUN a2enmod rewrite
# copy apache config
COPY docker/000-default.conf /etc/apache2/sites-available/000-default.conf
# expose port 80
EXPOSE 80
