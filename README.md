# AerokodTest

## Установка через Docker Compose

Все команды выполняются в корневой директории:

1. `cp .env.example .env`
1. `docker-compose up --build -d`
2. `docker compose exec -it php composer install`
3. `docker compose exec -it php php artisan install:api`
4. `docker compose exec -it php php artisan key:generate`
5. `docker compose exec -it php php artisan migrate`
6. `docker compose exec -it php php artisan seed:db`
7. `docker compose exec -it php php artisan create:admin`
8. Письма приходят в `laravel.log`, чтобы не создавать mailtrap или почтовую программу


