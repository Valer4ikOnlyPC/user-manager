#!/usr/bin/make
SHELL = /bin/sh

docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)

REGISTRY_HOST = registry.gitlab.com
IMAGES_PREFIX := $(shell basename $(shell dirname $(realpath $(lastword $(MAKEFILE_LIST)))))

PHP_CLI_CONTAINER_NAME = php-cli

docker_bin := $(shell command -v docker 2> /dev/null)
docker_compose_bin := $(shell command -v docker-compose 2> /dev/null)

ifeq "$(REGISTRY_HOST)" "registry.gitlab.com"
	docker_login_hint ?= "\n\
	**************************************************************************************\n\
	* Make your own auth token here: <https://gitlab.com/profile/personal_access_tokens> *\n\
	**************************************************************************************\n"
endif

.PHONY : help login test clean \
		 test-phpstan test-phpunit \
         up down restart shell install
.DEFAULT_GOAL := help

help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-15s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

up: ## Start all containers (in background) for development
	$(docker_compose_bin) up --no-recreate -d

down: ## Stop all started for development containers
	$(docker_compose_bin) down

restart: up ## Restart all started for development containers
	$(docker_compose_bin) restart

clean: ## Remove images from local registry
	-$(docker_compose_bin) down -v
	$(foreach image,$(all_images),$(docker_bin) rmi -f $(image);)

shell: up ## Start shell into container CONTAINER_NAME=""
	$(docker_compose_bin) exec -u $(shell id -u) "$(CONTAINER_NAME)" sh

root: up ## Start shell into container CONTAINER_NAME="" with root
	$(docker_compose_bin) exec -u root "$(CONTAINER_NAME)" sh

install: up ## Install application dependencies into php_cli container
	$(docker_compose_bin) exec -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" composer install --no-interaction --ansi --no-suggest --prefer-dist

composer: up ## Execute composer with ARGS=""
	$(docker_compose_bin) exec -u $(shell id -u) $(PHP_CLI_CONTAINER_NAME) composer $(ARGS)

php: up ## Execute php-cli with ARGS=""
	$(docker_compose_bin) exec -u $(shell id -u) $(PHP_CLI_CONTAINER_NAME) php $(ARGS)

test: test-phpunit test-phpstan test-phpcs ## Execute application tests

test-phpunit: up ## Execute phpunit tests with ARGS=""
	$(docker_compose_bin) exec -u root "$(PHP_CLI_CONTAINER_NAME)" sh -c "php -m | grep xdebug || docker-php-ext-enable xdebug"
	$(docker_compose_bin) exec -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" composer test:phpunit -- $(ARGS)

test-phpstan: up
	$(docker_compose_bin) exec -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" composer test:phpstan

test-phpcs: up ## Execute phpcs tests with ARGS=""
	$(docker_compose_bin) exec -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" composer test:phpcs -- $(ARGS)

fix-phpcs: up ## Execute phpcs tests with ARGS=""
	$(docker_compose_bin) exec -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" composer fix:phpcs -- $(ARGS)

migrate:
	$(docker_compose_bin) exec -T -u $(shell id -u) "$(PHP_CLI_CONTAINER_NAME)" ./user-manager/apps/main/bin/console doctrine:migrations:migrate

