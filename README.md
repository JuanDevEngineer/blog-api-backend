# Blog API Backend

REST API para una aplicación de blog construida en PHP con **Arquitectura Hexagonal**.

## Tecnologías

| Herramienta | Versión | Uso |
|---|---|---|
| PHP | 8.1+ | Lenguaje principal |
| MySQL / MariaDB | 8+ / 10.4+ | Base de datos |
| bramus/router | ~1.6 | Enrutamiento HTTP |
| firebase/php-jwt | latest | Autenticación JWT |
| vlucas/phpdotenv | 5.x | Variables de entorno |
| Apache | cualquiera | Servidor (mod_rewrite) |

---

## Arquitectura

El proyecto implementa **Arquitectura Hexagonal** (Ports & Adapters), organizada en tres capas:

```
src/
├── Domain/                  # Núcleo — sin dependencias externas
│   ├── Entities/            # Blog, User, Category, Role (readonly)
│   ├── Exceptions/          # Excepciones tipadas con código HTTP
│   └── Ports/
│       ├── Input/           # Contratos de casos de uso (22 interfaces)
│       └── Output/          # Contratos de repositorios y servicios (5 interfaces)
│
├── Application/             # Lógica de negocio
│   └── UseCases/            # 22 casos de uso (Auth, Blog, Category, User, Role)
│
└── Infrastructure/          # Detalles técnicos
    ├── Database/            # Conexión PDO (Singleton)
    ├── Persistence/MySQL/   # Repositorios que implementan los Output Ports
    ├── Security/            # JwtTokenService
    ├── Storage/             # LocalFileStorage (subida de imágenes)
    └── Http/
        ├── Controllers/     # Controllers HTTP (solo presentación)
        └── Middleware/      # JwtMiddleware (autenticación)
```

**Flujo de una petición:**

```
HTTP Request
    → JwtMiddleware (verifica token)
    → Controller (parsea request)
    → UseCase (valida y ejecuta lógica de negocio)
    → Repository (accede a la BD via Port)
    → Controller (mapea entidad a respuesta JSON)
HTTP Response
```

---

## Requisitos

- PHP 8.1 o superior
- Extensiones PDO y PDO_MySQL habilitadas
- Apache con `mod_rewrite` activo
- Composer
- MySQL 8+ o MariaDB 10.4+

---

## Instalación

### 1. Clonar el repositorio

```bash
git clone <url-del-repositorio>
cd blog-api-backend
```

### 2. Instalar dependencias

```bash
composer install
```

### 3. Configurar variables de entorno

```bash
cp .env.example .env
```

Editar `.env` con los valores del entorno:

```env
DATA_BASE_HOST=localhost
DATA_BASE_DB=blog_react_php
DATA_BASE_USER=root
DATA_BASE_PASS=tu_password
DATA_BASE_CHARSET=utf8mb4

SECRET_KEY_PRIVATE=clave_secreta_segura_minimo_32_caracteres

APP_URL=http://localhost/blog-api-backend
UPLOAD_PATH=public/uploads/blogs/
```

Generar una clave secreta segura para JWT:

```bash
php -r "echo bin2hex(random_bytes(32));"
```

### 4. Crear la base de datos

```bash
mysql -u root -p < database.sql
```

O importar `database.sql` desde phpMyAdmin / DBeaver.

### 5. Crear el directorio de uploads

```bash
mkdir -p public/uploads/blogs
```

### 6. Configurar Apache

Asegúrate de que `mod_rewrite` está activo y que el `DocumentRoot` apunta a la raíz del proyecto. El `.htaccess` incluido redirige todas las peticiones a `index.php`.

---

## Variables de entorno

| Variable | Descripción | Ejemplo |
|---|---|---|
| `DATA_BASE_HOST` | Host de la BD | `localhost` |
| `DATA_BASE_DB` | Nombre de la BD | `blog_react_php` |
| `DATA_BASE_USER` | Usuario de la BD | `root` |
| `DATA_BASE_PASS` | Password de la BD | `secret` |
| `DATA_BASE_CHARSET` | Charset de la BD | `utf8mb4` |
| `SECRET_KEY_PRIVATE` | Clave secreta JWT (min. 32 chars) | `894884e9...` |
| `APP_URL` | URL base de la aplicación | `http://localhost/blog-api-backend` |
| `UPLOAD_PATH` | Ruta para guardar imágenes | `public/uploads/blogs/` |

---

## Autenticación

La API usa **JWT Bearer Token**. Los endpoints bajo `/api/*` requieren el header:

```
Authorization: Bearer <token>
```

El token se obtiene haciendo login en `POST /auth/sign-in`. Expira en **1 hora**.

---

## Endpoints

### Auth (público)

| Método | Endpoint | Descripción |
|---|---|---|
| `POST` | `/auth/sign-in` | Iniciar sesión |
| `POST` | `/auth/sign-up` | Registrar usuario |

#### POST `/auth/sign-in`
```json
// Request
{
    "email": "admin@blog.com",
    "password": "tu_password"
}

// Response 200
{
    "status": 200,
    "success": true,
    "msg": "",
    "data": {
        "user": {
            "name": "Administrador",
            "email": "admin@blog.com",
            "rol": "ADMIN"
        },
        "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
    }
}
```

#### POST `/auth/sign-up`
```json
// Request
{
    "name": "Juan Dev",
    "email": "juan@mail.com",
    "password": "minimo8chars",
    "confirmPassword": "minimo8chars",
    "numberPhone": "3001234567"
}

// Response 201
{
    "status": 201,
    "success": true,
    "msg": "User registered successfully",
    "data": null
}
```

---

### Blogs (requiere token)

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/blogs` | Listar todos los blogs |
| `GET` | `/api/blogs/{id}` | Obtener blog por ID |
| `POST` | `/api/blogs` | Crear blog |
| `PUT` | `/api/blogs/{id}` | Actualizar blog |
| `DELETE` | `/api/blogs/{id}` | Eliminar blog |

#### POST `/api/blogs` — JSON
```json
{
    "category_id": 1,
    "title": "Mi primer post",
    "slug": "mi-primer-post",
    "text_short": "Resumen del post",
    "text_large": "Contenido completo del post..."
}
```

#### POST `/api/blogs` — Multipart (con imagen)
```
Content-Type: multipart/form-data

category_id = 1
title       = Mi primer post
slug        = mi-primer-post
text_short  = Resumen
text_large  = Contenido completo
blog        = [archivo: jpg, jpeg, png, gif — máx 10 MB]
```

---

### Categories (requiere token)

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/categories` | Listar categorías |
| `GET` | `/api/categories/{id}` | Obtener categoría por ID |
| `POST` | `/api/categories` | Crear categoría |
| `PUT` | `/api/categories/{id}` | Actualizar categoría |
| `DELETE` | `/api/categories/{id}` | Eliminar categoría |

```json
// POST / PUT body
{ "name": "Tecnología" }
```

---

### Users (requiere token)

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/users` | Listar usuarios |
| `GET` | `/api/users/{id}` | Obtener usuario por ID |
| `POST` | `/api/users` | Crear usuario |
| `PUT` | `/api/users/{id}` | Actualizar usuario |
| `DELETE` | `/api/users/{id}` | Eliminar usuario |

```json
// POST body
{
    "name": "Juan Dev",
    "email": "juan@mail.com",
    "password": "minimo8chars",
    "numberPhone": "3001234567",
    "rolId": 2
}

// PUT body
{
    "name": "Juan Dev",
    "numberPhone": "3001234567",
    "rolId": 2,
    "state": 1
}
```

---

### Roles (requiere token)

| Método | Endpoint | Descripción |
|---|---|---|
| `GET` | `/api/roles` | Listar roles |
| `GET` | `/api/roles/{id}` | Obtener rol por ID |
| `POST` | `/api/roles` | Crear rol |
| `PUT` | `/api/roles/{id}` | Actualizar rol |
| `DELETE` | `/api/roles/{id}` | Eliminar rol |

```json
// POST body
{ "name": "EDITOR" }

// PUT body
{ "name": "EDITOR", "state": 1 }
```

---

## Formato de respuesta

Todas las respuestas siguen la misma estructura:

```json
{
    "status": 200,
    "success": true,
    "msg": "Mensaje descriptivo",
    "data": { }
}
```

### Códigos HTTP utilizados

| Código | Significado |
|---|---|
| `200` | OK — operación exitosa |
| `201` | Created — recurso creado |
| `400` | Bad Request — datos inválidos o campos requeridos |
| `401` | Unauthorized — token ausente, inválido o expirado |
| `404` | Not Found — recurso no encontrado |
| `409` | Conflict — recurso duplicado (email o categoría ya existe) |
| `500` | Internal Server Error — error inesperado del servidor |

---

## Estructura del proyecto

```
blog-api-backend/
├── .env                    # Variables de entorno (no commitear)
├── .env.example            # Plantilla de variables de entorno
├── .htaccess               # Configuración Apache / mod_rewrite
├── composer.json
├── database.sql            # Esquema y seed de la base de datos
├── index.php               # Entry point
├── routes.php              # Composition Root + definición de rutas
├── public/
│   ├── .htaccess
│   └── uploads/blogs/      # Imágenes subidas (crear manualmente)
└── src/
    ├── Domain/
    │   ├── Entities/
    │   ├── Exceptions/
    │   └── Ports/
    │       ├── Input/
    │       └── Output/
    ├── Application/
    │   └── UseCases/
    └── Infrastructure/
        ├── Database/
        ├── Http/
        │   ├── Controllers/
        │   └── Middleware/
        ├── Persistence/
        │   └── MySQL/
        ├── Security/
        └── Storage/
```

---

## Base de datos

El archivo `database.sql` incluye la creación de la base de datos, las 4 tablas con sus relaciones, índices y un seed inicial con roles, categorías de ejemplo y un usuario administrador.

Para generar el hash de la contraseña del usuario admin antes de importar:

```bash
php -r "echo password_hash('tu_password', PASSWORD_BCRYPT, ['cost' => 12]);"
```

Reemplaza el placeholder en el `INSERT INTO users` dentro de `database.sql`.

---

## Seguridad

- Contraseñas hasheadas con **bcrypt** (cost 12)
- Autenticación stateless con **JWT HS256** (expiración 1 hora)
- Consultas SQL con **prepared statements** via PDO — prevención de SQL injection
- Secret key JWT exclusivamente en `.env` — nunca en código fuente
- Mensajes de error genéricos en excepciones inesperadas — sin exposición de internals
