.DEFAULT_GOAL := help

USER = $(shell id -u):$(shell id -g)

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

.PHONY: start
start: ## Start a development server (use Docker)
	@echo "Running webserver on http://localhost:8000"
	docker-compose -f docker/docker-compose.yml up

.PHONY: stop
stop: ## Stop and clean Docker server
	docker-compose -f docker/docker-compose.yml down

.PHONY: init
init: ## Initialize the application
	php ./cli --request /system/init

.PHONY: migrate
migrate: ## Apply pending migrations
	php ./cli --request /system/migrate

.PHONY: rollback
rollback: ## Reverse the last migration
ifdef STEPS
	php ./cli --request /system/rollback -psteps=$(STEPS)
else
	php ./cli --request /system/rollback
endif

.PHONY: test
test: bin/phpunit  ## Run the test suite
	php ./bin/phpunit \
		$(COVERAGE) --whitelist ./src \
		--bootstrap ./tests/bootstrap.php \
		--testdox \
		$(PHPUNIT_FILTER) \
		$(PHPUNIT_FILE)

.PHONY: lint
lint: bin/phpcs  ## Run the linter on the PHP files
	php ./bin/phpcs -s --standard=PSR12 ./src ./tests

.PHONY: lint-fix
lint-fix: bin/phpcbf ## Fix the errors raised by the linter
	php ./bin/phpcbf --standard=PSR12 ./src ./tests

.PHONY: tree
tree:  ## Display the structure of the application
	tree -I 'Minz|Faker|stripe-php|fpdf|coverage' --dirsfirst -CA

.PHONY: help
help:
	@grep -h -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}'

bin/phpunit:
	mkdir -p bin/
	wget -O bin/phpunit https://phar.phpunit.de/phpunit-8.5.2.phar
	echo '984e15fbf116a19ab98b6a642ccfc139a1a88172ffef995a9a27d00c556238f1 bin/phpunit' | sha256sum -c - || rm bin/phpunit

bin/phpcs:
	mkdir -p bin/
	wget -O bin/phpcs https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.5.3/phpcs.phar
	echo 'b44e0ad96138e2697a97959fefb9c6f1491f4a22d5daf08aabed12e9a2869678 bin/phpcs' | sha256sum -c - || rm bin/phpcs

bin/phpcbf:
	mkdir -p bin/
	wget -O bin/phpcbf https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.5.3/phpcbf.phar
	echo 'db20ec9cfd434deba03f6f20c818732d477696589d5aea3df697986b6e723ad7 bin/phpcbf' | sha256sum -c - || rm bin/phpcbf
