FROM php:7.2.34-cli-buster

ENV PS1='\[\033[1;36m\][\u@\h] \[\033[1;34m\]\w \[\033[1;36m\]# \[\033[0m\]'

ENV PHP_XDEBUG_VERSION 2.7.2
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

RUN install-php-extensions \
    bcmath \
    bz2 \
    dba \
    intl \
    msgpack-${PHP_MSGPACK_VERSION} \
    mysqli \
    opcache \
    pcntl \
    sockets \
    xdebug-${PHP_XDEBUG_VERSION}

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

