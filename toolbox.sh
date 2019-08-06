#!/bin/bash

yii () {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  local COMMAND=$@
  echo "docker exec -it $CONTAINER php .././$COMMAND"
}

shell() {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  echo "docker exec -it $CONTAINER bash"
}

docker-purge() {
  docker system prune
  docker system prune -a
  docker images purge
  docker volume prune
}

case $1 in
  "yii")
    yii "$@"
    ;;
  "shell")
    shell
    ;;
  "docker-purge")
  docker-purge
esac