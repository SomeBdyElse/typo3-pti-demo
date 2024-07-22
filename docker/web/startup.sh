#!/bin/bash
if [ -e /tmp/initialized ]
then
    echo "Already initialized"
else
    echo "Initializing"
    /app/Build/initialize.sh
    touch /tmp/initialized
fi

# Replace environment variables in nginx server conf
envsubst '$SERVER_NAME,$NGINX_HOST_FRONTEND' < /etc/nginx/http.d/default.conf.template > /etc/nginx/http.d/default.conf

# Start apache
/usr/bin/supervisord
