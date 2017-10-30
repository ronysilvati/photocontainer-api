FROM phpdockerio/php71-fpm:latest

# Install selected extensions and other stuff
RUN apt-get update \
    && apt-get -y --no-install-recommends install wget php7.1-mysql php7.1-gd php7.1-intl php7.1-mbstring php7.1-xsl \
    && apt-get -y --no-install-recommends install php-yaml php-xdebug php-mcrypt \
    && apt-get clean; rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

WORKDIR "/application"
RUN composer global require hirak/prestissimo

RUN echo "xdebug.remote_enable=on" >> /etc/php/7.1/cli/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /etc/php/7.1/cli/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_host=localhost" >> /etc/php/7.1/cli/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_port=9000" >> /etc/php/7.1/cli/conf.d/20-xdebug.ini \
    && echo "xdebug.remote_handler=dbgp" >> /etc/php/7.1/cli/conf.d/20-xdebug.ini