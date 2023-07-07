ARG PHP_VER
ARG PHP_SAPI
FROM php:${PHP_VER}-${PHP_SAPI}-alpine3.16 AS proto

RUN printf "#!/bin/sh\n" > /usr/bin/note \
    && chmod ugo+x /usr/bin/note

RUN apk update \
    && apk add --no-cache \
        postgresql-dev

RUN apk add --no-cache --virtual .build-deps \
      linux-headers $(note "Needed by sockets")\
    && docker-php-ext-install \
      pdo_pgsql \
      sockets

ARG APP_TIMEZONE
RUN apk add tzdata \
        && cp /usr/share/zoneinfo/${APP_TIMEZONE} /etc/localtime \
        && echo ${APP_TIMEZONE} > /etc/timezone \
        && apk del tzdata

ADD ./ini/*ini ${PHP_INI_DIR}/conf.d/
RUN sed -i "s#{{date.timezone}}#${APP_TIMEZONE}#g" ${PHP_INI_DIR}/conf.d/date.ini

ADD ./*sh /var/tools/
RUN chmod u+x /var/tools/*.sh && /var/tools/install-composer.sh

ARG APP_CONTAINER_VAR_DIR
ARG APP_CONTAINER_CACHE_DIR
RUN mkdir -p ${APP_CONTAINER_VAR_DIR} && mkdir -p ${APP_CONTAINER_CACHE_DIR}

# Add user and group for isolated run
ARG APP_CONTAINER_USER
ENV USER ${APP_CONTAINER_USER}
ARG APP_CONTAINER_UID
ENV UID ${APP_CONTAINER_UID}
ARG APP_CONTAINER_GID
ENV GID ${APP_CONTAINER_GID}

RUN addgroup -g ${GID} -S ${USER} && \
    adduser -u ${UID} -S ${USER} -G ${USER} && \
    chmod -R 0755 ${APP_CONTAINER_VAR_DIR} && \
    chown -R ${USER}:${USER} ${APP_CONTAINER_VAR_DIR}

ENV ENV="/home/${APP_CONTAINER_USER}/.ashrc"


FROM proto as basic
USER ${APP_CONTAINER_USER}


FROM ghcr.io/roadrunner-server/roadrunner:2.11.4 AS rr-src

FROM basic AS roadrunner
COPY --from=rr-src /usr/bin/rr /usr/local/bin/rr
USER ${APP_CONTAINER_USER}






