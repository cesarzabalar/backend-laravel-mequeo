# backend-developer-test
Proyecto en Laravel como prueba de Backend Developer

![](https://github.com/cesarzabalar/backend-developer-test/workflows/ci-cd-workflow/badge.svg)

# Requerimientos
* Docker  v20.10.6
* Docker Compose v1.28.5


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
docker-compose exec app php artisan key:generate
```
5. Limpiar la configuración
```bash
docker-compose exec php artisan config:clear
```

# Ejecutar las pruebas
1. Veriicar la configuración del ambiente del archivo .env.testing

2. Correr los unit y features tests
```bash
docker-compose exec app vendor/bin/phpunit
```

3. Generar documento de coverage
```bash
docker-compose exec app vendor/bin/phpunit --coverage-html=coverage
```

# Acceder a la URL del proyecto
`http://localhost:8000`



