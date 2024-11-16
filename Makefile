.PHONY: up down restart exec shell

include .env

up:
	docker-compose up -d --build

down:
	docker-compose down

restart: down up

exec:
	docker exec -it ${PROJECT_PREFIX}-php-fpm $(ARGS)

shell:
	make exec ARGS=bash
