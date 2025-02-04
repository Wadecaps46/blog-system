# Blog system
Un sistema de blog construido con Laravel 11 que implementa autenticación de usuarios y gestión de publicaciones.

## Descripción General
Este proyecto implementa un sistema básico de blog con capacidades de autenticación de usuarios, login y gestión de publicaciones usando Laravel 11. Utiliza SQLite como base de datos e incluye Sanctum para la autenticación API y test.

## Características

- Autenticación de usuarios (registro/inicio de sesión)
- Autenticación basada en tokens usando Laravel Sanctum
- Sistema de gestión de publicaciones
- Organización de publicaciones por categorías
- Endpoints API para todas las funcionalidades principales
- Pruebas

## Stack Técnico

- Laravel 11
- PHP 8.x
- SQLite (para base de datos)
- Laravel Sanctum (para autenticación API)
- PHPUnit (para pruebas)

## Endpoints API
### Autenticación

- POST /api/register - Registro de nuevo usuario
- POST /api/login - Inicio de sesión (devuelve token de autenticación)

### Publicaciones

- POST /api/posts - Crear nueva publicación (requiere autenticación)
- GET /api/posts/{categoryId} - Obtener todas las publicaciones por categoría

### Categorías

- POST /api/c_category - Crear nueva categoría
- GET /api/categories - Listar todas las categorías

## Pruebas
El proyecto incluye pruebas unitarias enfocadas en la autenticación y permisos usando Laravel Sanctum. Para ejecutar las pruebas:

php artisan test

### Casos de prueba principales:

- Funcionalidad de inicio de sesión
- Acceso a rutas protegidas con autenticación
- Permisos de creación de publicaciones

## Notas de Desarrollo

- Utiliza SQLite en lugar de MySQL/PostgreSQL por mis limitaciones del equipo, aunque lo unico que cambiare serian las credenciales
- Implementa medidas básicas de seguridad incluyendo encriptación de contraseñas
- Usa permisos basados en capacidades de Laravel Sanctum para la creación de publicaciones

## Estructura del Proyecto
El proyecto sigue la estructura estándar de Laravel con organización adicional para:

- app/Http/Controllers (User, Post, Category)
- app/Models (User, Post, Category)
- routes/API
- tests/Feature/SanctumTest.php

## Características de Seguridad

- Encriptación de contraseñas
- Autenticación basada en tokens
- Protección de rutas mediante middleware
- Permisos basados en capacidades usando Sanctum

## Inicio Rápido

- Clonar el repositorio
