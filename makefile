install: install-composer install-deps create-directories move-dotenv set-perm

revert-install:
	rm -rf var/
	rm -rf vendor/
	rm -rf composer.phar
	rm public/.env

install-composer:
ifeq ("$(wildcard composer.phar)","")
	@echo "**** Task: Baixar composer.phar..." 
	wget --quiet --show-progress https://getcomposer.org/composer.phar 
	@echo "**** Task: Adicionar pacote global" 
	php composer.phar global require hirak/prestissimo 
endif

create-directories:
ifeq ("$(wildcard var)","")
	@echo "**** Task: Gerar diretórios em 'var/'"
	mkdir var
	mkdir var/cache
	mkdir var/logs
	mkdir var/pool
endif

move-dotenv:
ifeq ("$(wildcard public/.env)","")
	@echo "**** Task: Criar arquivo public/.env"
	cp public/.env.SAMPLE public/.env
	@echo ""
	@echo "-----------------------------------------------------------------------------------------------"
	@echo "| O arquivo public/.env deve ser configurado para que a aplicação seja executada corretamente |"
	@echo "-----------------------------------------------------------------------------------------------"
	@echo ""
endif

install-deps:
ifeq ("$(wildcard vendor)","")
	@echo "**** Task: Instalar dependências"
	php composer.phar install --no-dev --no-progress
endif

set-perm:
	@echo "**** Task: corrigir permissões"
	chmod  -Rf 775 var/
	chmod +x fotocontainer
