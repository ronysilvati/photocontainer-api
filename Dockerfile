FROM php:7.1.2-apache

MAINTAINER lcidral@gmail.com

ENV PATH $PATH:/root/.composer/vendor/bin

# PHP extensions come first, as they are less likely to change between Yii releases
RUN apt-get update \
    && apt-get -y install \
            git \
            g++ \
            libicu-dev \
            libmcrypt-dev \
            zlib1g-dev \
            vim \
            nodejs \
            npm \
            curl \
            mysql-client \
            ssmtp \
            libxml2-dev \
            php-soap \
            libfreetype6-dev \
            libjpeg62-turbo-dev \
            libpng12-dev \
            libjpeg-dev \
            php5-curl \
            php5-mysql \
            libmemcached-dev \
            php5-memcached \
            graphviz \
            subversion \
            wget \
            ssh \
            php5-gd \
            libssh2-php \
        --fix-missing --no-install-recommends \

    # set recommended PHP.ini settings
    # see https://secure.php.net/manual/en/opcache.installation.php
    && { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=2'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini \

# ENV - APACHE REWRITE
    && a2enmod rewrite expires \

# ENV - PHP EXTENSIONS
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install mcrypt \
    && docker-php-ext-install zip \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install soap \
    && docker-php-ext-install opcache \
    && docker-php-ext-configure gd --with-png-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-freetype-dir=/usr/include/ \
    && docker-php-ext-install gd \

# DEV - XDEBUG / MEMCACHED
    && pecl install memcached xdebug \
    && echo "extension=memcached.so" > /usr/local/etc/php/conf.d/docker-php-ext-memcached.ini \
    && { \
        echo "zend_extension=xdebug.so"; \
        echo "xdebug.remote_enable=\${XDEBUG_REMOTE_ENABLE}"; \
        echo "xdebug.remote_autostart=\${XDEBUG_REMOTE_AUTOSTART}"; \
        echo "xdebug.remote_connect_back=\${XDEBUG_REMOTE_CONNECT_BACK}"; \
        echo "xdebug.remote_host=\${XDEBUG_REMOTE_HOST}"; \
        echo "xdebug.remote_port=\${XDEBUG_REMOTE_PORT}"; \
        echo "xdebug.xdebug.idekey=\${XDEBUG_IDEKEY}"; \
    } > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \

# PHP.INI
    && { \
        echo "[Date]"; \
        echo 'date.timezone = "America/Sao_Paulo"'; \
        echo "phar.readonly = Off"; \
    } >> /usr/local/etc/php/php.ini \

# DEV - WP-CLI
    && curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp \

#DEV - ROBO
    && wget http://robo.li/robo.phar \
    && chmod +x robo.phar \
    && mv robo.phar /usr/bin/robo \

# DEV - Mailcatcher
    && echo "sendmail_path = /usr/sbin/ssmtp -t" > /usr/local/etc/php/conf.d/docker-php-ext-sendmail.ini \
    && echo "mailhub=mail:1025\nUseTLS=NO\nFromLineOverride=YES" > /etc/ssmtp/ssmtp.conf \

# VIRTUALIZATION - CLEAN
    && apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \

# Fix write permissions with shared folders
    && usermod -u 1000 www-data \

# ENV - BUILD
    && npm install -g bower \
    && npm install -g gulp-install \

# DEV - Composer
    && curl -sS "https://getcomposer.org/installer" | php \
    && mv composer.phar /usr/local/bin/composer \

# QA - TESTS
    && composer global require --no-progress "fxp/composer-asset-plugin:~1.2.0" \
    && composer global require --no-progress "codeception/codeception=2.2.*" \
    && composer global require --no-progress "codeception/specify=*" \
    && composer global require --no-progress "codeception/verify=*" \

# QA - METRICS
#    && composer global require --no-progress "phpmd/phpmd:*" \
#    && composer global require --no-progress "pdepend/pdepend:*" \
    && composer global require --no-progress "squizlabs/php_codesniffer:*" \
    && composer global require --no-progress "phpunit/phpcov:*" \
    && composer global require --no-progress "sebastian/phpcpd:*" \
    && composer global require --no-progress "phploc/phploc:*" \
    && composer global require --no-progress "phpmetrics/phpmetrics" \

# DEPLOY
    && composer global require --no-progress "deployer/deployer:3.3.*"

# ENV - APACHE - Config
COPY apache2.conf /etc/apache2/apache2.conf

RUN ln -s /usr/bin/nodejs /usr/bin/node

WORKDIR /var/www/html

#EXPOSE 80