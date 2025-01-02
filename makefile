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
		-w /app/$(REL_DIR) \
		composer/composer \
		update

test:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		-e XDEBUG_MODE=coverage \
		jitesoft/phpunit \
			./vendor/bin/phpunit \
				--coverage-text \
				--coverage-filter src \
				--show-uncovered-for-coverage-text \
				--path-coverage \
				tests

lint:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		php:8.4-cli \
			./vendor/bin/phpcs \
				-s \
				-p \
				--colors \
				--extensions=php \
				--standard=PSR12 \
				--ignore=/vendor \
				/app

format:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		php:8.4-cli \
			./vendor/bin/phpcbf \
				-p \
				--extensions=php \
				--standard=PSR12 \
				--ignore=/vendor \
				/app

clean:
	rm -rf \
		composer.lock \
		vendor