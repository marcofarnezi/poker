docker-compose up -d --build

docker exec -it symfonydocker_php_1  bash

cp .env_example .env

php bin/console doctrine:migration:migrate

php bin/console doctrine:fixtures:load

php bin/console hand:import

POST: http://localhost:8001/login
payload:

_username: admin
_password: 123456