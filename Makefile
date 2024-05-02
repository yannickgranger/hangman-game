#!make

PHPUNIT_BIN = vendor/bin/phpunit
BEHAT_BIN = vendor/bin/behat
DOCKER_COMPOSE = docker compose -f docker-compose.yml -f docker-compose.dev.yml

all: bdd tdd

behat:
	@APP_ENV=test $(BEHAT_BIN)

phpunit:
	@APP_ENV=test $(PHPUNIT_BIN) tests/

clean:
	@rm -rf coverage/

.PHONY: all bdd tdd clean

up:
	${DOCKER_COMPOSE} up -d

down:
	${DOCKER_COMPOSE} down