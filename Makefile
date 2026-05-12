.PHONY: install start stop test fixtures cs

install:
	composer install
	docker compose up -d
	php bin/console doctrine:database:create --if-not-exists
	php bin/console doctrine:migrations:diff --no-interaction || true
	php bin/console doctrine:migrations:migrate --no-interaction
	php bin/console doctrine:fixtures:load --no-interaction

start:
	symfony server:start -d --port=8000

stop:
	symfony server:stop || true
	docker compose down

test:
	php bin/phpunit

fixtures:
	php bin/console doctrine:fixtures:load --no-interaction

cs:
	php tools/php-cs-fixer.phar fix
