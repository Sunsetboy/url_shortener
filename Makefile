up:
	docker-compose up

stop:
	docker-compose down

load_test_symfony:
	docker-compose run k6 run /app/test_symfony.js