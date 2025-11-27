FROM ghcr.io/nhsleadership/nhsl-ubuntu-phpv2:8.3-master AS build
SHELL ["/bin/bash", "-exo", "pipefail", "-c"]

COPY --chown=nhsla:nhsla / /app

USER root
RUN apt-get update && \
  apt-get install -y --no-install-recommends ca-certificates curl gnupg && \
  curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
  NODE_MAJOR=20 && \
  echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
  apt-get update && apt-get install --no-install-recommends nodejs -y

USER 1000

WORKDIR /app
RUN composer install --no-scripts --no-dev && npm install && npm run build

FROM ghcr.io/nhsleadership/nhsl-ubuntu-phpv2:8.3-master
SHELL ["/bin/bash", "-exo", "pipefail", "-c"] 

COPY --chown=nhsla:nhsla docker/rootfs/ /
COPY --from=build --chown=nhsla:nhsla /app /app

RUN chmod +x /etc/cont-init.d/03-laravel.sh && \
  mkdir -p /app/storage && \
  chmod -R 777 /app/storage && \
  chown -R nhsla:nhsla /app/storage && \
  mkdir -p /app/bootstrap/cache && \
  chmod -R 777 /app/bootstrap/cache && \
  chown -R nhsla:nhsla /app/bootstrap/cache && \
  date > /nhsla/build_site_date
