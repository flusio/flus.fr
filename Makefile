.DEFAULT_GOAL := help

USER = $(shell id -u):$(shell id -g)

DOCKER_COMPOSE = docker compose -f docker/development/docker-compose.yml

ifdef NODOCKER
	PHP = php
	COMPOSER = composer
else
	PHP = ./docker/bin/php
	COMPOSER = ./docker/bin/composer
endif

.PHONY: docker-start
docker-start: PORT ?= 8000
docker-start: ## Start a development server (can take a PORT argument)
	@echo "Running webserver on http://localhost:$(PORT)"
	$(DOCKER_COMPOSE) up

.PHONY: docker-build
docker-build: ## Rebuild the Docker containers
	$(DOCKER_COMPOSE) build --pull

.PHONY: docker-pull
docker-pull: ## Pull the Docker images from the Docker Hub
	$(DOCKER_COMPOSE) pull --ignore-buildable

.PHONY: docker-clean
docker-clean: ## Clean the Docker stuff
	$(DOCKER_COMPOSE) down -v

.PHONY: install
install: ## Install the dependencies
	$(COMPOSER) install

.PHONY: db-setup
db-setup: ## Initialize or migration the application
	$(PHP) ./cli migrations setup --seed

.PHONY: db-rollback
db-rollback: ## Reverse the last migration
ifdef STEPS
	$(PHP) ./cli migrations rollback --steps=$(STEPS)
else
	$(PHP) ./cli migrations rollback
endif

.PHONY: test
test: FILE ?= ./tests
ifdef FILTER
test: override FILTER := --filter=$(FILTER)
endif
test: COVERAGE ?= --coverage-html ./coverage
test: ## Run the test suite (can take FILE, FILTER and COVERAGE arguments)
	$(PHP) ./vendor/bin/phpunit -c .phpunit.xml $(COVERAGE) $(FILTER) $(FILE)

.PHONY: lint
lint: LINTER ?= all
lint: ## Run the linters on the PHP files
ifeq ($(LINTER), $(filter $(LINTER), all phpstan))
	$(PHP) ./vendor/bin/phpstan analyse --memory-limit 1G -c .phpstan.neon
endif
ifeq ($(LINTER), $(filter $(LINTER), all rector))
	$(PHP) ./vendor/bin/rector process --dry-run --config .rector.php
endif
ifeq ($(LINTER), $(filter $(LINTER), all phpcs))
	$(PHP) ./vendor/bin/phpcs
endif

.PHONY: lint-fix
lint-fix: LINTER ?= all
lint-fix: ## Fix the errors raised by the linter
ifeq ($(LINTER), $(filter $(LINTER), all rector))
	$(PHP) ./vendor/bin/rector process --config .rector.php
endif
ifeq ($(LINTER), $(filter $(LINTER), all phpcs))
	$(PHP) ./vendor/bin/phpcbf
endif

.PHONY: tree
tree:  ## Display the structure of the application
	tree -I 'Minz|stripe-php|fpdf|coverage|vendor|carnet' --dirsfirst -CA

.PHONY: help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
