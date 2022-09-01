Demo Chat Application
=====================

This application is a combination of:
* REST API backend written in Symfony and [API Platform](https://api-platform.com/)
* MVC frontend client written in [Angular](https://angular.io/)
* [Mercure](https://mercure.rocks/) for publish updates from server to clients

Installation
------------
1. If not already done, [install Docker Compose](https://docs.docker.com/compose/install/)
2. Run `docker-compose build`
3. Run `docker-compose run webapp composer install`
4. Run `docker-compose run node npm install && ng build`
5. Run `docker-compose up -d`
6. Run `docker-compose exec webapp bin/console doctrine:migrations:migrate`
7. Open `http://localhost` in your favorite web browser