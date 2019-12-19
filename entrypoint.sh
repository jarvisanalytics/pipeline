#!/bin/bash
set -e

service docker start

cd /var/www/html

php -S 0.0.0.0:80