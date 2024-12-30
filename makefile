MKFILE_DIR = $(shell echo $(dir $(abspath $(firstword $(MAKEFILE_LIST)))) | sed -e 's,/$$,,')
GIT_DIR = $(shell git rev-parse --show-toplevel)
REL_DIR = $(shell realpath -s --relative-to=$(GIT_DIR) $(MKFILE_DIR))
UID = $(shell id -u)
GID = $(shell id -g)

.PHONEY: *

build:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w/app/$(REL_DIR) \
		composer/composer \
		install

test:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w/app/$(REL_DIR) \
		php:8.4-cli \
			./vendor/bin/phpunit tests

clean:
	rm -rf \
		composer.lock \
		vendor