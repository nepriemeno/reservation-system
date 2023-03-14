DOCKER:
````
docker compose -f docker/docker-compose.dev.yaml --env-file docker/.env.dev up --build -d 
````
````
docker exec -it php sh
````
PHP CS FIXER:
````
PHP_CS_FIXER_IGNORE_ENV=1 tools/php-cs-fixer/vendor/bin/php-cs-fixer fix src
````
