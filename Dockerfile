FROM drupal:10.1.5

RUN apt-get update && \
    apt-get upgrade -y && \
    apt-get install -y git

RUN  docker-php-ext-install \
        bcmath \
    && docker-php-ext-enable \
        bcmath

RUN  docker-php-ext-install \
        mysqli \
    && docker-php-ext-enable \
        mysqli

RUN  composer install
