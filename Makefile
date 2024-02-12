run: # output of the greeting
	./bin/gendiff tests/fixtures/file1.json tests/fixtures/file2.json

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