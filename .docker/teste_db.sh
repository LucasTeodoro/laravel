#!/bin/sh
# teste_db.sh

set -e

host="$1"
shift

until MYSQL_PASSWORD=$MYSQL_PASSWORD mysql -h "$host" -u "root" -c '\q'; do
  sleep 1
done
