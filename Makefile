run-json: # output of the greeting
	./bin/gendiff tests/DifferTest/fixtures/file1.json tests/DifferTest/fixtures/file2.json

run-yaml: # output of the greeting
	./bin/gendiff tests/DifferTest/fixtures/file1.yaml tests/DifferTest/fixtures/file2.yaml

run-rec-json: # output of the greeting
	./bin/gendiff tests/DifferTest/fixtures/recurs1.json tests/DifferTest/fixtures/recurs2.json

stan:
	php ./vendor/bin/phpstan analyse src --level 9

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
