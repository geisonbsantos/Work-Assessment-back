
include .env
optimize: 
	docker-compose exec $(CONTAINER_NAME) php artisan optimize

clear:
	docker-compose exec $(CONTAINER_NAME) php artisan optimize:clear