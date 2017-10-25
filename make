#!/bin/bash

echo -e "\nConsider adding a 'composer.lock' file into your source repository.\n"

if [ ! -d 'var' ]; then
  mkdir var
fi

if [ ! -d 'var/cache' ]; then
  mkdir var/cache         
fi

if [ ! -d 'var/logs' ]; then
  mkdir var/logs              
fi

if [ ! -d 'var/pool' ]; then
  mkdir var/pool              
fi

chmod  -Rf 777 var

if [ ! -f 'composer.phar' ]; then
  wget https://getcomposer.org/composer.phar
fi

if [ ! -f 'composer.lock' ]; then
  php composer.phar install
fi

if [ ! -f 'public/.env' ]; then
  cp public/.env.SAMPLE public/.env
fi
