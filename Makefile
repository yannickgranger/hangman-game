#!make

APP = app_game
PHPUNIT_BIN = vendor/bin/phpunit
BEHAT_BIN = vendor/bin/behat
PHPSTAN = vendor/bin/phpstan
CS = vendor/bin/php-cs-fixer
DOCKER_COMPOSE = docker compose -f docker-compose.yml -f docker-compose.dev.yml


# play
play:
	- ${DOCKER_COMPOSE} exec ${APP} sh -c "bin/console app:hangman:play"

# dev
tests: bdd tdd clean

bdd:
	@APP_ENV=test $(BEHAT_BIN)

tdd:
	@APP_ENV=test $(PHPUNIT_BIN) tests/


quality: phpstan

phpstan:
	@${PHPSTAN} analyze src
	@${PHPSTAN} analyze tests

cs:
	@${CS} fix src
	@${CS} fix tests

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
	- @${DOCKER_COMPOSE} down
	- @docker stop $(docker ps -a -q) || true
	- @docker rm $(docker ps -a -q) || true
	- @docker kill $(docker ps -a -q) || true
