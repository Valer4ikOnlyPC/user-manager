FROM php:7.2.34-cli-buster

ENV PS1='\[\033[1;36m\][\u@\h] \[\033[1;34m\]\w \[\033[1;36m\]# \[\033[0m\]'

ENV PHP_XDEBUG_VERSION 2.7.2
ENV PHP_MEMCACHE_VERSION 4.0.5.2
ENV PHP_MEMCACHED_VERSION 3.1.5
ENV PHP_REDIS_VERSION 5.3.7
ENV PHP_ZIP_VERSION 1.15.4
ENV PHP_IGBINARY_VERSION 3.2.6
ENV PHP_MAILPARSE_VERSION 3.1.0
ENV PHP_MSGPACK_VERSION 2.1.2

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && sync

RUN echo "deb http://deb.debian.org/debian buster main contrib" > /etc/apt/sources.list \
    && echo "ttf-mscorefonts-installer msttcorefonts/accepted-mscorefonts-eula select true" | debconf-set-selections

RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -yq \
    util-linux \
    bash \
    vim \
    sudo \
    git \
    libxml2 \
    libpixman-1-0 \
    libgif7 \
    libpng16-16 \
    liblcms2-2  \
    fontconfig \
    libtiff5 \
    ttf-mscorefonts-installer \
    libgomp1 \
    cron \
    unzip

RUN fc-cache -f

ADD https://www.princexml.com/download/prince-14.4-debian10-amd64.tar.gz /tmp

RUN cd /tmp \
    && tar -xzf prince-14.4-debian10-amd64.tar.gz \
    && cd prince-14.4-debian10-amd64 \
    && ./install.sh \
    && cd / \
    && rm -rf /tmp/prince-14.4-debian10-amd64 \
    && rm -f /tmp/prince-14.4-debian10-amd64.tar.gz

RUN install-php-extensions \
    bcmath \
    bz2 \
    calendar \
    dba \
    exif \
    gd \
    gettext \
    gmp \
    igbinary-${PHP_IGBINARY_VERSION} \
    imap \
    intl \
    ldap \
    mailparse-${PHP_MAILPARSE_VERSION} \
    mcrypt \
    memcache-${PHP_MEMCACHE_VERSION} \
    memcached-${PHP_MEMCACHED_VERSION} \
    msgpack-${PHP_MSGPACK_VERSION} \
    mysqli \
    opcache \
    pcntl \
    pdo_mysql \
    pspell \
    redis-${PHP_REDIS_VERSION} \
    sockets \
    soap \
    sysvmsg \
    sysvsem \
    sysvshm \
    tidy \
    wddx \
    xdebug-${PHP_XDEBUG_VERSION} \
    xmlrpc \
    xsl \
    zip-${PHP_ZIP_VERSION}

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin/ --filename=composer

RUN rm -f /docker-entrypoint.sh

ENV TZ="Europe/Moscow"

RUN mkfifo -m 0666 /var/log/cron.log \
    && ln -s /var/log/cron.log /var/log/crond.log

RUN cp /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini.original

COPY etc/conf.d/php.ini /usr/local/etc/php/conf.d/php.ini
COPY etc/cli-entrypoint.sh /cli-entrypoint.sh


ENV WORKDIR /usr/data/app

WORKDIR "$WORKDIR"

ENTRYPOINT ["/cli-entrypoint.sh"]

CMD ["cron", "-f", "8"]

