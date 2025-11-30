# joanfont/correllengua

[![Build Status](https://github.com/joanfont/correllengua/actions/workflows/build-and-push-prod.yml/badge.svg?branch=main)](https://github.com/joanfont/correllengua/actions/workflows/build-and-push-prod.yml)

## Overview

This project is a small PHP domain-model application focused on managing participants, registrations and route segments. It implements domain entities and collections to represent participants and their registrations. The codebase is organized following modern PHP practices and uses Composer for dependency management.

## Technologies

- Language & Runtime: PHP 8.5 (CLI, Alpine), Composer (dependency management)
- Framework: Symfony 7.4 (FrameworkBundle, Console, Runtime, Serializer, Validator, Twig, Asset)
- Persistence: Doctrine ORM 3.x and DBAL 3.x, Doctrine Migrations
- Infrastructure & Runtime:
  - RoadRunner PHP application server (baldinof/roadrunner-bundle)
  - Docker and Docker Compose for local dev environment
  - MySQL (database), Redis (messaging/cache)
- Messaging & Async: Symfony Messenger (with Redis transport)
- HTTP & API Docs: NelmioApiDocBundle for OpenAPI documentation
- Files & Data: league/csv for CSV import/export, Flysystem for filesystem abstraction
- Utilities: ramsey/uuid for unique IDs, phpdocumentor/phpstan parsers for docblocks
- Dev & QA: PHPUnit 12, PHP-CS-Fixer, Rector, PHPStan, Symfony Web Profiler & Debug bundles

## How to run

Prerequisites: Docker and Docker Compose installed.

1. Build images

```bash
docker compose build
```

2. Start services (app server, worker, MySQL, Redis, Mailpit)

```bash
docker compose up -d
```

The application will run under RoadRunner and expose HTTP on port 8080.

3. Install dependencies (done in Dockerfile during build for dev/prod accordingly). If you need to re-run inside the container:

```bash
docker compose run --rm --no-deps app composer install --ignore-platform-reqs --prefer-dist --no-interaction --no-scripts
```

4. Database migrations

Using Makefile targets:

```bash
make migrate
```

Or directly via Symfony console inside the container:

```bash
docker compose run --rm --no-deps app php ./bin/console doctrine:migrations:migrate --no-interaction
```

5. Run tests

```bash
make test
```

6. Useful commands

- Generate migration diff

```bash
make migrations
```

- Open a shell in the app container

```bash
make shell
```

- Consume async messages (worker is started via docker-compose; to run manually):

```bash
docker compose run --rm app ./bin/console messenger:consume async -v
```

## Authors

- Joan Font <joanfont@gmail.com>
