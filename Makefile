COMPOSER = docker compose run --rm --no-deps --entrypoint=composer app
SYMFONY = docker compose run --rm --entrypoint=php app ./bin/console

.PHONY: migrations
migrations:
	$(SYMFONY) doctrine:migrations:diff

.PHONY: migrate
migrate:
	$(SYMFONY) doctrine:migrations:migrate --no-interaction

.PHONY: php-cs
php-cs:
	$(COMPOSER) run php-cs

.PHONY: test
test:
	docker compose run --rm --entrypoint=php app vendor/bin/phpunit $(ARGS)
