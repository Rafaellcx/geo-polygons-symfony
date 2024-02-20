FROM postgres:14-bullseye

LABEL maintainer="PostGIS Project - https://postgis.net"

ENV POSTGIS_MAJOR 3
ENV POSTGIS_VERSION 3.2.1+dfsg-1.pgdg110+1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        postgresql-14-postgis-3 \
        postgresql-14-postgis-3-scripts \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /docker-entrypoint-initdb.d
COPY ./docker/postgres/initdb-postgis.sh /docker-entrypoint-initdb.d/10_postgis.sh
COPY ./docker/postgres/update-postgis.sh /usr/local/bin
