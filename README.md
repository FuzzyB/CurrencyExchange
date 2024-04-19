Requirements:
- composer
- docker

To run project:
1. Got into the main directory and run:
   - docker-compose build
   - docker-compose up -d
2. Example implementation is visible on 'localhost'

To run Unit tests:
1. Jump into the docker container
   - docker exec -it currencyexchange-app-1 sh
2. Run:
   - php vendor/bin/phpunit --configuration=phpunit.xml.dist
