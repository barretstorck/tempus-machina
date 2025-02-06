MKFILE_DIR = $(shell echo $(dir $(abspath $(firstword $(MAKEFILE_LIST)))) | sed -e 's,/$$,,')
GIT_DIR = $(shell git rev-parse --show-toplevel)
REL_DIR = $(shell realpath -s --relative-to=$(GIT_DIR) $(MKFILE_DIR))
UID = $(shell id -u)
GID = $(shell id -g)
DOCKER_CONTAINER = ghcr.io/barretstorck/php:8.4-cli-alpine-composer-xdebug

.PHONEY: *

help:
	@echo "Available commands:"
	@echo "  build   - Build the project using Composer."
	@echo "  test    - Run tests with PHPUnit."
	@echo "  lint    - Lint PHP code using PHP_CodeSniffer."
	@echo "  format  - Format PHP code using PHP_CodeFixer."
	@echo "  clean   - Clean up generated files."

build:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		$(DOCKER_CONTAINER) \
			composer update --ignore-platform-reqs

test:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		-e XDEBUG_MODE=develop,debug,coverage \
		$(DOCKER_CONTAINER) \
			./vendor/bin/phpunit \
				--testdox \
				--coverage-text \
				--coverage-html code_coverage \
				--coverage-filter src \
				--show-uncovered-for-coverage-text \
				--path-coverage \
				--display-incomplete \
				--display-skipped \
				--display-deprecations \
				--display-phpunit-deprecations \
				--display-errors \
				--display-notices \
				--display-warning \
				tests

lint:
	docker run \
		--rm \
		-u $(UID):$(GID) \
		-v $(GIT_DIR):/app \
		-w /app/$(REL_DIR) \
		$(DOCKER_CONTAINER) \
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
		$(DOCKER_CONTAINER) \
			./vendor/bin/phpcbf \
				-p \
				--extensions=php \
				--standard=PSR12 \
				--ignore=/vendor \
				/app

clean:
	docker image rm $(DOCKER_CONTAINER)
	rm -rf \
		composer.lock \
		code_coverage \
		vendor