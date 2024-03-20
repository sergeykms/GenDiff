run-json:
	./bin/gendiff --format stylish tests/DifferTest/fixtures/file1.json tests/DifferTest/fixtures/file2.json

run-json-plain:
	./bin/gendiff --format plain tests/DifferTest/fixtures/file1.json tests/DifferTest/fixtures/file2.json

run-yaml:
	./bin/gendiff tests/DifferTest/fixtures/file1.yaml tests/DifferTest/fixtures/file2.yaml

run-rec-json:
	./bin/gendiff --format stylish tests/DifferTest/fixtures/recurs1.json tests/DifferTest/fixtures/recurs2.json

run-rec-json-plain:
	./bin/gendiff --format plain tests/DifferTest/fixtures/recurs1.json tests/DifferTest/fixtures/recurs2.json

run-rec-json-json:
	./bin/gendiff --format json tests/DifferTest/fixtures/recurs1.json tests/DifferTest/fixtures/recurs2.json

run-rec-yaml:
	./bin/gendiff tests/DifferTest/fixtures/recurs1.yaml tests/DifferTest/fixtures/recurs2.yaml

stan:
	php ./vendor/bin/phpstan analyse

auto:
	composer dump-autoload

install: # install project
	composer install

validate: # validate composer.json
	composer validate

lint:
	composer exec --verbose phpcs -- --standard=PSR12 src tests
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- --standard=PSR12 src tests

help:
	./bin/gendiff -h

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text
