# project-ecommerce
Projekt zaliczeniowy dla "Profesjonalna aplikacja mobilna lub webowa"

## Project setup
1. Run `docker compose up -d` and wait until all services are build, healthy and running
2. Run `docker compose exec -it app php` to run shell for php server and inside run 
    ```shell
   bin/console doctrine:schema:create
   bin/console doctrine:migrations:migrate
   ```
3. Open http://localhost/admin to see admin panel. You can use default `admin:password` credentials 
to login to admin panel