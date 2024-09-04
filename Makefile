build:
	USER_UID=$(shell id -u) USER_GID=$(shell id -g) docker compose build

up:
	USER_UID=$(shell id -u) USER_GID=$(shell id -g) docker compose up -d

stop:
	docker compose stop

shell:
	docker compose exec -it --user "$(shell id -u):$(shell id -g)" php bash
