#! /bin/bash

# build the plugin
sh build.sh

# clone lokuswp
git clone https://github.com/lokuswp/lokuswp.git

# run Dockerfile
docker build --no-cache . -t wordpress:latest

# run docker-compose
docker-compose up -d
