#!/bin/bash

# Use to interact with codeception
# ./toolbox.sh codecept run
codecept() {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  local COMMAND=$@
  docker exec -i $CONTAINER bash -c "cd ../ && ./vendor/bin/codecept $COMMAND"
}

# Run code coverage on the application
# ./toolbox.sh coverage
coverage() {
  codecept "run --coverage --coverage-html"
}

# Remove docker related shit
# ./toolbox.sh docker-purge
docker-purge() {
  docker system prune
  docker system prune -a
  docker images purge
  docker volume prune
}

# Engage in an interactive shell with the container
# ./toolbox.sh shell
shell() {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  docker exec -it $CONTAINER bash
}

# Run the codeception unit tests
# ./toolbox.sh unit-tests
unit-tests() {
  codecept "run unit"
}

# Interact with the yii script file on the container
# ./toolbox.sh yii migrate/create ...
yii () {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  local COMMAND=$@
  docker exec -it $CONTAINER php .././yii $COMMAND
}

# Interact with the test environments yii script file
# ./toolbox.sh yii-test migrate
yii-test() {
  local CONTAINER="$(docker ps | grep fpm | awk '{print $1}')"
  local COMMAND=$@
  docker exec -it $CONTAINER php ../tests/bin/yii $COMMAND
}



case $1 in
  "codecept")
    codecept "${@:2}"
    ;;
  "coverage")
    coverage
    ;;
  "docker-purge")
    docker-purge
    ;;
  "shell")
    shell
    ;;
  "unit-tests")
    unit-tests
    ;;
  "yii")
    yii "${@:2}"
    ;;
  "yii-test")
    yii-test "${@:2}"
    ;;
esac