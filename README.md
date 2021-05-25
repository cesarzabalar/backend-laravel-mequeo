# backend-developer-test
Proyecto en Laravel como prueba de Backend Developer

![](https://github.com/cesarzabalar/backend-developer-test/workflows/ci-cd-workflow/badge.svg)


# Ejecutar Docker Compose
```bash
docker-compose up -d
```
# Configuración
1. Instalar las dependencias de composer
```bash
docker-compose exec app composer install
```
2. Ejecutar las migraciones de la base de datos
```bash
docker-compose exec app php artisan migrate:fresh --seed
```
3. Ejecutar las credenciales JWT
```bash
docker-compose exec app php artisan passport:install --force
```
4. Generar el Key
```bash
docker-compose exec app php artisan cache:clear
```
5. Generar el Key
```php
\DB::table('table_name')->get();
```

# Acceder a la URL del proyecto
`http://localhost:8000`



