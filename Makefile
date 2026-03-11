.PHONY: setup start stop build rebuild migrate seed logs cache-clear production-optimize

setup:
	cp -n .env.example .env || true
	docker compose down
	docker compose build
	docker compose up -d
	docker compose exec app composer install
	docker compose run --rm node
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate --seed

start:
	docker compose up -d

stop:
	docker compose down

build:
	docker compose build

rebuild:
	docker compose down
	docker compose build --no-cache
	docker compose up -d --force-recreate

migrate:
	docker compose exec app php artisan migrate

seed:
	docker compose exec app php artisan db:seed

logs:
	docker compose logs -f

production-optimize:
	docker compose exec app php artisan config:cache
	docker compose exec app php artisan route:cache
	docker compose exec app php artisan view:cache

cache-clear:
	docker compose exec app php artisan config:clear
	docker compose exec app php artisan route:clear
	docker compose exec app php artisan view:clear
	docker compose exec app php artisan cache:clear
