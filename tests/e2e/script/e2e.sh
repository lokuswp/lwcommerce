#! /bin/bash

# colored echo
cecho(){
    RED="\033[1;31m"
    GREEN="\033[1;32m"
    YELLOW="\033[1;33m" # <-- [1 means bold
    CYAN="\033[1;36m"
    BLUE="\033[0;34m" # <-- [0 means not bold

    NC="\033[0m" # No Color

    # printf "${(P)1}${2} ${NC}\n" # <-- zsh
    printf "${!1}${2} ${NC}\n" # <-- bash
}

# build the plugin
echo "Building plugin...."
bash build.sh

# clone lokuswp
git clone https://github.com/lokuswp/lokuswp.git

# run Dockerfile
docker build --no-cache . -t wordpress:latest -f tests/e2e/docker/wordpress-php7.4/Dockerfile
docker build --no-cache . -t wordpress:5.9-php7.3-apache -f tests/e2e/docker/wordpress-php7.3/Dockerfile

# run docker-compose
docker-compose -f tests/e2e/docker/wordpress-php7.4/docker-compose.yaml up -d
docker-compose -f tests/e2e/docker/wordpress-php7.3/docker-compose.yaml up -d

# Wait for docker container become healthy
cecho "CYAN" "Wait for docker container become healthy"
while [[ $(curl --connect-timeout 3 -s -o /dev/null -w "%{http_code}" http://localhost:8000) != "302" && $(curl --connect-timeout 3 -s -o /dev/null -w "%{http_code}" http://localhost:8000) != "200" ]]
do
  cecho "RED" "Starting up server"
  sleep 5
done
cecho "GREEN" "Server is up!"
sleep 5

cecho "BLUE" "Starting e2e tests!"