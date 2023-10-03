main:
	php -S localhost:3000 -t public/ & sass --watch resources/assets/sass:public/css

server:
	php -S 0.0.0.0:3000 -t public/

testing:
	vendor/bin/phpunit