run: # output of the greeting
	clear
	php index.php

stan:
	clear
	php ./vendor/bin/phpstan analyse src --level 9

auto:
	clear
	composer dump-autoload

install: # install project
	clear
	composer install

validate: # validate composer.json
	clear
	composer validate

lint:
	clear
	composer exec --verbose phpcs -- --standard=PSR12 src
	composer exec --verbose phpstan

lint-fix:
	clear
	composer exec --verbose phpcbf -- --standard=PSR12 src