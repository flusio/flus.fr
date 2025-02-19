.DEFAULT_GOAL := help

USER = $(shell id -u):$(shell id -g)

ifdef NODOCKER
	PHP = php
	COMPOSER = composer
else
	PHP = ./docker/bin/php
	COMPOSER = ./docker/bin/composer
endif

ifndef COVERAGE
	COVERAGE = --coverage-html ./coverage
endif

ifdef FILTER
	PHPUNIT_FILTER = --filter=$(FILTER)
else
	PHPUNIT_FILTER =
endif

ifdef FILE
	PHPUNIT_FILE = $(FILE)
else
	PHPUNIT_FILE = ./tests
endif

.PHONY: docker-start
docker-start: ## Start a development server
	@echo "Running webserver on http://localhost:8000"
	docker compose -p flusfr -f docker/docker-compose.yml up

.PHONY: docker-build
docker-build: ## Rebuild the Docker containers
	docker compose -p flusfr -f docker/docker-compose.yml build

.PHONY: docker-clean
docker-clean: ## Clean the Docker stuff
	docker compose -p flusfr -f docker/docker-compose.yml down

.PHONY: install
install: ## Install the dependencies
	$(COMPOSER) install

.PHONY: setup
setup: ## Initialize or migration the application
	$(PHP) ./cli migrations setup --seed

.PHONY: rollback
rollback: ## Reverse the last migration
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
lint: ## Run the linters on the PHP files
	$(PHP) ./vendor/bin/phpstan analyse --memory-limit 1G -c phpstan.neon
	$(PHP) ./vendor/bin/rector process --dry-run --config .rector.php
	$(PHP) ./vendor/bin/phpcs -s --standard=PSR12 ./src ./tests

.PHONY: lint-fix
lint-fix: ## Fix the errors raised by the linter
	$(PHP) ./vendor/bin/rector process --config .rector.php
	$(PHP) ./vendor/bin/phpcbf --standard=PSR12 ./src ./tests

.PHONY: tree
tree:  ## Display the structure of the application
	tree -I 'Minz|stripe-php|fpdf|coverage|vendor' --dirsfirst -CA

.PHONY: help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'
