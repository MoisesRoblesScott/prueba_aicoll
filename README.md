# API Empresas - Laravel

Este proyecto es una API RESTful para la gestión de empresas, desarrollada con Laravel. Permite listar, crear, consultar, actualizar y eliminar empresas, manejando validaciones y respuestas en español.

## Características

- CRUD de empresas (listar, crear, consultar por NIT, actualizar, eliminar)
- Validaciones robustas y mensajes en español
- Arquitectura basada en controladores y servicios
- Uso de migraciones y Eloquent ORM
- Configuración lista para desarrollo local

## Requisitos

- PHP >= 8.2
- Composer
- MySQL, configurando `.env`

## Instalación

1. **Clona el repositorio:**
   ```sh
   git clone https://github.com/MoisesRoblesScott/apis-empresas
   cd api-empresas
   ```

2. **Instala dependencias de PHP:**
   ```sh
   composer install
   ```
3. **Configura el entorno:**
   - Copia el archivo de ejemplo y edita según tu entorno:
     ```sh
     cp .env.example .env
     ```
   - Ajusta las variables de entorno en `.env` (por ejemplo, `DB_CONNECTION`, `DB_DATABASE`, etc.).

4. **Genera la clave de la aplicación:**
   ```sh
   php artisan key:generate
   ```

5. **Ejecuta las migraciones:**
   ```sh
   php artisan migrate
   ```

## Ejecución del proyecto

1. **Levanta el servidor de desarrollo:**
   ```sh
   php artisan serve
   ```

2. **Accede a la API:**
   - Por defecto estará en `http://localhost:8000/api/empresas`

## Endpoints principales

- `GET /api/empresas` — Listar empresas
- `POST /api/empresas` — Crear empresa
- `GET /api/empresas/{nit}` — Consultar empresa por NIT
- `PUT /api/empresas/{nit}` — Actualizar empresa
- `DELETE /api/empresas/{nit}` — Eliminar empresa (solo si está inactiva)

## Pruebas

Para ejecutar los tests (si tienes definidos):

```sh
php artisan test
```

## Estructura del proyecto

- `app/Http/Controllers/EmpresaController.php` — Controlador principal de empresas
- `app/services/EmpresaService.php` — Lógica de negocio de empresas
- `routes/api.php` — Definición de rutas de la API
- `database/migrations/` — Migraciones de base de datos
- `lang/es/` — Traducciones y mensajes de validación en español
