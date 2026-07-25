# NextLvlStock

Sistema de gestión y control de inventario desarrollado con Laravel, Vue.js, Docker y MySQL.

## Descripción

NextLvlStock es una aplicación orientada a la administración y control de inventario para empresas de tecnología.

El sistema busca centralizar procesos como la gestión de productos, compras, ventas y movimientos de inventario, permitiendo tener un mejor control sobre la operación.

## Funcionalidades planificadas

La aplicación contará con módulos para administrar:

- Productos
- Categorías
- Marcas
- Bodegas
- Proveedores
- Clientes
- Compras
- Ventas
- Movimientos de inventario
- Roles y permisos

## Tecnologías utilizadas

- Laravel
- Vue.js
- PHP
- MySQL
- Docker
- Docker Compose
- Nginx

## Requisitos

Antes de iniciar el proyecto es necesario tener instalado:

- Docker Desktop
- Git

## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/Ceesar-c/NextLvlStock.git
cd NextLvlStock
```

### 2. Configurar variables de entorno

Crear los archivos .env a partir de los archivos de ejemplo:

- .env.example → .env
- backend/.env.example → backend/.env

Completar las variables de entorno necesarias.

### 3. Construir y levantar los contenedores

Ejecutar desde la raíz del proyecto:

```bash
docker compose up --build
```

### 4. Generar la clave de Laravel

Ingresar al contenedor de PHP:

```bash
docker exec -it nextlvlstock-php bash
```

Ejecutar:

```bash
php artisan key:generate
```

### 5. Ejecutar migraciones

Dentro del contenedor PHP:

```bash
php artisan migrate
```

## Acceso a los servicios

Aplicación: http://localhost

phpMyAdmin: http://localhost:8080

## Estructura del proyecto

```text
NextLvlStock
│
├── backend
│   └── Aplicación Laravel
│
├── docker
│   ├── nginx
│   └── php
│
└── docker-compose.yml
```

## Estado del proyecto

Actualmente se encuentra en fase inicial de desarrollo.

La configuración del entorno de desarrollo está completa utilizando Docker, Laravel y MySQL. A partir de esta base se continuará con la construcción de los módulos principales del sistema.

## Licencia

Proyecto desarrollado con fines educativos y de aprendizaje.