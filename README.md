DOCKER:
````
docker compose -f docker/docker-compose.dev.yaml --env-file docker/.env.dev up --build -d 
````
````
docker exec -it php sh
````
PHP CS FIXER:
````
tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src
````
PHPSTAN:
````
tools/phpstan/vendor/bin/phpstan analyse src tests
````
PHPUNIT:
````
tools/phpunit/vendor/bin/phpunit tests
````
JWT:
````
bin/console lexik:jwt:generate-keypair --skip-if-exists
````
