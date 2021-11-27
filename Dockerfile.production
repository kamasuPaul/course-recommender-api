FROM php:7.4-fpm
# args defined in docker-compose.yml
ARG user
ARG uid
#install system depencies
# RUN apt-get update && apt-get install -y \
#     git \
#     curl \
#     libpng-dev \
#     libonig-dev \
#     libxml2-dev \
#     zip \
#     unzip 
#clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
# install php extensions
RUN docker-php-ext-install pdo pdo_mysql bcmath
# get composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#create user to run composer and artisan commands
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user
# set workding directory
WORKDIR /var/www/
# set user
USER $user