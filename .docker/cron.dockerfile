FROM php:8.2-cli

RUN apt-get update && apt-get -y install cron

RUN docker-php-ext-install pdo_mysql

COPY ./.docker/crontab /etc/cron.d/crontab

RUN chmod 0644 /etc/cron.d/crontab

RUN /usr/bin/crontab /etc/cron.d/crontab