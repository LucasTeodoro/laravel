#!/bin/sh
# teste_db.sh

set -e

host="$1"
shift
cmd="$@"

until MYSQL_PASSWORD=$MYSQL_PASSWORD mysql -h "$host" -u "root" -c '\q'; do
  >&2 echo "Mysql is unavailable - sleeping"
  sleep 1
done

>&2 echo "Mysql is up - executing command"
exec $cmd