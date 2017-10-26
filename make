#!/bin/bash

echo -e "\n*** Instalador da API FotoContainer ***\n"

if [ ! -f 'composer.phar' ]; then
  echo -e "Task: Baixar composer.phar..."
  wget --quiet --show-progress https://getcomposer.org/composer.phar

  echo -e "\nTask: Adicionar pacote global\n"
  php composer.phar global require hirak/prestissimo
fi

if [ ! -d 'var' ]; then
  echo -e "\nTask: Gerar diretório 'var/' \n"
  mkdir var
fi

if [ ! -d 'var/cache' ]; then
  echo -e "Task: Gerar diretório 'var/cache' \n"
  mkdir var/cache         
fi

if [ ! -d 'var/logs' ]; then
  echo -e "Task: Gerar diretório 'var/logs' \n"
  mkdir var/logs              
fi

if [ ! -d 'var/pool' ]; then
  echo -e "Task: Gerar diretório 'var/pool' \n"
  mkdir var/pool              
fi

chmod  -Rf 777 var

if [ ! -f 'public/.env' ]; then
  echo -e "Task: Criar arquivo public/.env \n"
  cp public/.env.SAMPLE public/.env
fi

if [ ! -d 'vendor' ]; then
  echo -e "Task: Instalar dependências\n"
  php composer.phar install --no-dev --no-progress
fi

chmod +x fotocontainer

echo -e "\n*** Finalizada com sucesso ***\n"


exit 0