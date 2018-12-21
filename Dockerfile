FROM kibatic/symfony:7.3

RUN apt-get -qq update > /dev/null &&\
    DEBIAN_FRONTEND=noninteractive apt-get -qq -y --no-install-recommends install \
    php7.3-xdebug > /dev/null &&\
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
