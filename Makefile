#!make

APP = app_game
PHPUNIT_BIN = vendor/bin/phpunit
BEHAT_BIN = vendor/bin/behat
PHPSTAN = vendor/bin/phpstan
DOCKER_COMPOSE = docker compose -f docker-compose.yml -f docker-compose.dev.yml

tests: bdd tdd clean

bdd:
	@APP_ENV=test $(BEHAT_BIN)

tdd:
	@APP_ENV=test $(PHPUNIT_BIN) tests/


quality: phpstan

phpstan:
	@${PHPSTAN} analyze src
	@${PHPSTAN} analyze tests

clean:
	@rm -rf coverage/


# docker
app:
	${DOCKER_COMPOSE} exec ${APP} sh

build:
	${DOCKER_COMPOSE} build

up:
	${DOCKER_COMPOSE} up -d

down:
	${DOCKER_COMPOSE} down