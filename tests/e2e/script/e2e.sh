#! /bin/bash

# build the plugin
echo "Building plugin...."
sh build.sh

# clone lokuswp
git clone https://github.com/lokuswp/lokuswp.git

# run Dockerfile
docker build --no-cache . -t wordpress:latest -f tests/e2e/Dockerfile

# run docker-compose
docker-compose -f tests/e2e/wordpress-php7.4/docker-compose.yaml up -d

# Wait for docker container become healthy
echo "Wait for docker container become healthy for 50s"
sleep 50s