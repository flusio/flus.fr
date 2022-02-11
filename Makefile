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
	docker-compose -p flusfr -f docker/docker-compose.yml up

.PHONY: stop
stop: ## Stop and clean Docker server
	docker-compose -p flusfr -f docker/docker-compose.yml down

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
	XDEBUG_MODE=coverage php ./bin/phpunit \
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
	wget -O bin/phpunit https://phar.phpunit.de/phpunit-9.5.10.phar
	echo 'a34b9db21de3e75ba2e609e68a4da94633f4a99cad8413fd3731a2cd9aa08ca8 bin/phpunit' | sha256sum -c - || rm bin/phpunit

bin/phpcs:
	mkdir -p bin/
	wget -O bin/phpcs https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.6.1/phpcs.phar
	echo 'd0ce68aa469aff7e86935c6156a505c4d6dc90adcf2928d695d8331722ce706b bin/phpcs' | sha256sum -c - || rm bin/phpcs

bin/phpcbf:
	mkdir -p bin/
	wget -O bin/phpcbf https://github.com/squizlabs/PHP_CodeSniffer/releases/download/3.6.1/phpcbf.phar
	echo '4fd260dd0eb4beadd6c68ae12a23e9adb15e155dfa787c9e6ba7104d3fc01471 bin/phpcbf' | sha256sum -c - || rm bin/phpcbf
