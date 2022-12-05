# Tomáš Stejskal - Adresář

## Development
### Working with database
- Run `docker-compose up` to start docker
- Run `docker-compose exec www /bin/bash` to access app's bash
- Run `cd ..` to go in root folder
- Run `composer install` to install composer dependencies
- Run `php bin/console doctrine:migrations:migrate` to migrate database
- Run `php bin/console doctrine:fixtures:load` to generate data