# products

php bin/console app:run-sync - command for sync products
 
### install 
composer install

docker-compose up `- in project directory`

`in new tab or terminal: `

php bin/console doctrine:database:create `- in project directory`

php bin/console doctrine:migrations:migrate `- in project directory`

php -S localhost:8080  `- in "public" directory`