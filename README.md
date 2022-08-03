# Zadanie testowe Wiosna

## Instalacja
- clone repo
- got to cloned folder
- run `docker-compose up -d --build`
- go to app container `docker-compose exec app bash`
- run `php bin/console doctrine:migration:migrate`
- run `php bin/console doctrine:fixtures:load`
- go to `http://localhost`
- login with credentials `johndoe@email.com`/`password`