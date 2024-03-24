build:
	docker-compose build

up:
	docker-compose up

stop:
	docker-compose down

load_test_symfony:
	docker-compose run --rm k6 run /app/test_symfony.js

load_test_go:
	docker-compose run --rm k6 run /app/test_go.js

load_test_fastapi:
	docker-compose run --rm k6 run /app/test_fastapi.js

generate_codes:
	docker-compose run app_symfony bin/console app:generate-keys 10000