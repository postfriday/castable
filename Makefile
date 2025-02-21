#!/usr/bin/env make
# SHELL = sh -xv

PHP_VERSION := 8.1

TAG := muscobytes/php-cli-$(PHP_VERSION)

DOCKER_RUN := docker run -ti -v "$(shell pwd):/var/www/html"
PHP_IDE_SERVER_NAME := castable

.PHONY: help
help:  ## Shows this help message
	@echo "  Usage: make [target]\n\n  Targets:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "   🔸 \033[36m%-30s\033[0m %s\n", $$1, $$2}'

.PHONY: build
build: ## Build the PHP CLI Docker image
	docker build \
		--file "$(shell pwd)/.docker/php/$(PHP_VERSION)/Dockerfile" \
		--tag $(TAG) \
		.

.PHONY: shell
shell: ## Run an interactive shell inside the container
	$(DOCKER_RUN) -e PHP_IDE_CONFIG="serverName=$(PHP_IDE_SERVER_NAME)" $(TAG) sh

.PHONY: test
test: ## Run PHPUnit tests inside the container
	$(DOCKER_RUN) $(TAG) vendor/bin/phpunit

.PHONY: tag
tag: ## Create a Git tag from the composer.json version
	git tag v$(shell jq -r .version < composer.json)

.PHONY: untag
untag: ## Remove the Git tag from the composer.json version
	git tag -d v$(shell jq -r .version < composer.json)
