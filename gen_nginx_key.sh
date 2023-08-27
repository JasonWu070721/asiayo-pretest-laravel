#!/bin/bash

PATH="./docker/nginx/ssl"
KEY_FILE=$PATH/ssl.key
CRT_FILE=$PATH/ssl.crt

if [ -f "$KEY_FILE" -a -f "$CRT_FILE" ]; then
    echo "$KEY_FILE and $CRT_FILE both exist."
    exit 0
fi

if [ ! -f /usr/bin/docker -o ! -f /usr/bin/id ]; then
    echo "Docker or ID library missing."
    exit 1
fi

/usr/bin/docker pull nginx

/usr/bin/docker run -v $PWD:/work  -u $(/usr/bin/id -u):$(/usr/bin/id -g) \
    -it nginx openssl req -x509 -nodes -days 365   -newkey rsa:4096 -out /work/$CRT_FILE  \
    -keyout /work/$KEY_FILE -subj "/C=TW/ST=Test/L=Test/O=Test/CN=localhost"

if [ ! -f "$KEY_FILE" -o ! -f "$CRT_FILE" ]; then
    echo "Nginx ssl key generated failed."
    exit 1
fi

echo "Nginx ssl key generated successfully."
exit 0