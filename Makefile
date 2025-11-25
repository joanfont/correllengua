DOCKER_COMPOSE = docker compose run --rm --no-deps
COMPOSER = $(DOCKER_COMPOSE) --no-deps --entrypoint=composer app
SYMFONY = $(DOCKER_COMPOSE) --entrypoint=php app ./bin/console

.PHONY: shell
shell:
	$(DOCKER_COMPOSE) --entrypoint=/bin/sh app

.PHONY: migrations
migrations:
	$(SYMFONY) doctrine:migrations:diff

.PHONY: migrate
migrate:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction

.PHONY: php-cs
php-cs:
	$(DOCKER_COMPOSE) app ./vendor/bin/php-cs-fixer fix

.PHONY: test
test:
	$(DOCKER_COMPOSE) --entrypoint=php app vendor/bin/phpunit $(ARGS)
