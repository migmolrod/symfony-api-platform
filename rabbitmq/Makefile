#!/bin/bash

RABBIT = symfony-api-platform-rabbitmq
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

ssh: ## SSHs into the backend container
	U_ID=${UID} docker exec --user ${UID} -it ${RABBIT} bash
