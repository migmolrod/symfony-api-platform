#!/bin/bash

DOCKER_BE = symfony-api-platform-be
OS := $(shell uname)

ifeq ($(OS),Darwin)
	UID = $(shell id -u)
else ifeq ($(OS),Linux)
	UID = $(shell id -u)
else
	UID = 1000
endif

help: ## Shows this help message
	@echo 'usage: make [target]'
	@echo
	@echo 'targets:'
	@egrep '^(.+)\:\ ##\ (.+)' ${MAKEFILE_LIST} | column -t -c 2 -s ':#'

run: ## Starts all containers
	docker network create symfony-api-platform-network || true
	U_ID=${UID} docker-compose up -d

stop: ## Stops all containers
	U_ID=${UID} docker-compose stop

restart: ## Restarts all containers
	${MAKE} stop && ${MAKE} run

build: ## Rebuilds all containers
	U_ID=${UID} docker-compose build

prepare: ## Runs backend commands
	${MAKE} be-composer

# Backend commands
be-composer: ## Installs composer dependencies :backend command
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} composer install --no-scripts --no-interaction --optimize-autoloader

be-migrations: ## Runs database migrations :backend command
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} bin/console doctrine:migrations:migrate -n

be-logs: ## Tails the Symfony dev log :backend command
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} tail -f var/log/dev.log

be-ssh: ## SSHs into the backend container :backend command
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} bash

be-php-cs: ## Runs code style CS-Fixer with Symfony rules :backend command
	U_ID=${UID} docker exec --user ${UID} -it ${DOCKER_BE} php-cs-fixer fix src --rules=@Symfony
