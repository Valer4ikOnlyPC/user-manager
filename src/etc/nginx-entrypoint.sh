#!/usr/bin/env sh
set -e

NGINX_CONFIG_PATH="/etc/nginx";
NGINX_CONFIG_FILE="${NGINX_CONFIG_PATH}/nginx.conf";
NGINX_VHOST_CONFIG_FILE="${NGINX_CONFIG_PATH}/conf.d/default.conf";

NGINX_USER=$(getent passwd "$NGINX_USER_ID" | cut -d: -f1);
NGINX_GROUP=$(getent group "$NGINX_GROUP_ID" | cut -d: -f1);

NGINX_USER="${NGINX_USER:-nginx}";
NGINX_GROUP="${NGINX_GROUP:-nginx}";
NGINX_VHOST_ROOT="${NGINX_VHOST_ROOT:-/usr/data/app}";
NGINX_SERVER_NAME="${NGINX_SERVER_NAME:-localhost}";

sed -i "s#%NGINX_USER%#${NGINX_USER}#g" "$NGINX_CONFIG_FILE";
sed -i "s#%NGINX_GROUP%#${NGINX_GROUP}#g" "$NGINX_CONFIG_FILE";
sed -i "s#%NGINX_VHOST_ROOT%#${NGINX_VHOST_ROOT}#g" "$NGINX_VHOST_CONFIG_FILE";
sed -i "s#%NGINX_SERVER_NAME%#${NGINX_SERVER_NAME}#g" "$NGINX_VHOST_CONFIG_FILE";

exec "$@"
