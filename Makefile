DOCKER_COMPOSE = docker compose run --rm --no-deps
PHP = $(DOCKER_COMPOSE) --entrypoint=php app
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
	$(PHP) ./vendor/bin/php-cs-fixer fix

phpstan:
	$(PHP) ./vendor/bin/phpstan analyse src tests --level=max

.PHONY: test
test:
	$(PHP) ./vendor/bin/phpunit --configuration phpunit.dist.xml
