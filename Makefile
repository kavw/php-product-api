MAKEFILE_PATH := $(abspath $(lastword $(MAKEFILE_LIST)))
PROJ_DIR := $(patsubst %/,%, $(dir $(MAKEFILE_PATH)))

ifeq ($(COMPOSE_FILES),)
	COMPOSE_FILES := -f "$(PROJ_DIR)/docker-compose.yml"
else
	COMPOSE_FILES += -f "$(PROJ_DIR)/docker-compose.yml"
endif


ARCH :=
ifneq ($(OS), Windows_NT)
	ARCH := $(shell uname -m)
endif


ENV_SRC := $(PROJ_DIR)/example.env

ENV := $(PROJ_DIR)/.env
EXE := docker-compose --env-file $(ENV) $(COMPOSE_FILES)
CLI := $(EXE) run --rm php-cli

$(ENV):
	[ -f "$(ENV)" ] || cp "$(ENV_SRC)" "$@"

.PHONY: upenv
upenv:
	cp -f "$(ENV_SRC)" "$(ENV)"

.PHONY: build
build: $(ENV)
	$(EXE) build

.PHONY: install
install:
	$(CLI) composer install

.PHONY: migrate
migrate:
	$(CLI) ./app migrations:migrate

.PHONY: init
init: build install migrate
	@echo "Done"
	@echo "Try make up"

.PHONY: up
up: build
	$(EXE) up

.PHONY: cli
cli: build
	$(CLI) ash

.PHONY: check
check:
	$(CLI) composer run-script check

.PHONY: config
config:
	$(EXE) config

