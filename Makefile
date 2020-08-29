include .env

current_time = $(shell date +'%Y%m%d_%H%M%S')

install:
	@make -s db_backup
	rm -rf ./postgres-db
	$(info ----- $@ start -----)
	docker-compose -p $(PROJECT_NAME) up -d --force-recreate --build --remove-orphans
	docker cp .env $(PROJECT_NAME)-php:/var/www/html/
	docker exec -it $(PROJECT_NAME)-php bash -c "composer install"
	docker exec -it $(PROJECT_NAME)-php bash -c "php init"
	docker exec -it $(PROJECT_NAME)-php bash -c "php yii migrate"
	docker exec -it $(PROJECT_NAME)-php bash -c "chown -R www-data.www-data /var/www/html"

start:
	docker-compose -p $(PROJECT_NAME) up -d --force-recreate --build --remove-orphans

remove_all_containers:
	$(info ----- $@ start -----)
	docker-compose -p $(PROJECT_NAME) down

db_shell:
	$(info ----- $@ start -----)
	@docker exec -ti $(PROJECT_NAME)-db psql --pset=expanded=auto -U $(DB_USER) $(DB_PASSWORD)

server_shell:
	$(info ----- $@ start -----)
	@docker exec -it $(PROJECT_NAME)-php bash

db_backup:
	$(info ----- $@ start -----)
	@docker exec $(PROJECT_NAME)-db bash -c "pg_dump --strict-names -U $(DB_USER) $(DB_NAME)" | sed 's/\r//g' > /tmp/$(current_time)_$(PROJECT_NAME)_dump.sql
	@echo "Backup made: /tmp/$(current_time)_$(PROJECT_NAME)_dump.sql"

migrate:
	$(info ----- $@ start -----)
	docker exec -it $(PROJECT_NAME)-php bash -c "php yii migrate"
