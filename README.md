# School Management API

Una API completa para la gestión escolar desarrollada con Laravel 10 y documentación Swagger/OpenAPI.

## Características

- **Autenticación JWT**: Sistema de autenticación seguro con tokens JWT
- **Gestión de Usuarios**: Administradores, profesores y estudiantes
- **Gestión de Cursos**: Creación y administración de cursos académicos
- **Matrículas**: Sistema de inscripción de estudiantes en cursos
- **Calificaciones**: Registro y seguimiento de calificaciones
- **Asistencia**: Control de asistencia estudiantil
- **Documentación Swagger**: API completamente documentada
- **Datos de Prueba**: Seeders con usuarios y datos de ejemplo

## Estructura del Proyecto

```
api-school/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── StudentController.php
│   │   │   ├── CourseController.php
│   │   │   └── Controller.php (con documentación Swagger)
│   │   └── Middleware/
│   ├── Models/
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   ├── Course.php
│   │   ├── Enrollment.php
│   │   ├── Grade.php
│   │   └── Attendance.php
│   └── Providers/
├── config/
│   ├── auth.php
│   ├── jwt.php
│   └── l5-swagger.php
├── database/
│   ├── migrations/
│   └── seeders/
├── routes/
│   └── api.php
└── composer.json
```

## Instalación

### Prerrequisitos

- PHP 8.1 o superior
- Composer
- MySQL/PostgreSQL
- Node.js (opcional para desarrollo frontend)

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd api-school
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar variables de entorno**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configurar la base de datos**
   Editar el archivo `.env` con los datos de tu base de datos:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=api_school
   DB_USERNAME=tu_usuario
   DB_PASSWORD=tu_contraseña
   ```

5. **Generar clave JWT**
   ```bash
   php artisan jwt:secret
   ```

6. **Ejecutar migraciones y seeders**
   ```bash
   php artisan migrate --seed
   ```

7. **Generar documentación Swagger**
   ```bash
   php artisan l5-swagger:generate
   ```

8. **Iniciar el servidor**
   ```bash
   php artisan serve
   ```

## Uso de la API

### Autenticación

La API utiliza autenticación JWT. Para acceder a los endpoints protegidos, incluye el token en el header:

```
Authorization: Bearer <tu_token_jwt>
```

### Endpoints Principales

#### Autenticación
- `POST /api/auth/login` - Iniciar sesión
- `POST /api/auth/register` - Registrar usuario
- `POST /api/auth/logout` - Cerrar sesión
- `GET /api/auth/me` - Obtener usuario actual

#### Estudiantes
- `GET /api/students` - Listar estudiantes
- `POST /api/students` - Crear estudiante
- `GET /api/students/{id}` - Obtener estudiante
- `PUT /api/students/{id}` - Actualizar estudiante
- `DELETE /api/students/{id}` - Eliminar estudiante

#### Cursos
- `GET /api/courses` - Listar cursos
- `POST /api/courses` - Crear curso
- `GET /api/courses/{id}` - Obtener curso
- `PUT /api/courses/{id}` - Actualizar curso
- `DELETE /api/courses/{id}` - Eliminar curso

#### Dashboard
- `GET /api/dashboard/stats` - Estadísticas generales

### Documentación Swagger

Una vez que el servidor esté ejecutándose, puedes acceder a la documentación interactiva de Swagger en:

```
http://localhost:8000/api/documentation
```

## Usuarios de Prueba

El seeder crea los siguientes usuarios para pruebas:

### Administrador
- **Email**: admin@school.com
- **Contraseña**: password
- **Rol**: admin

### Profesores
- **Email**: john.teacher@school.com
- **Contraseña**: password
- **Rol**: teacher

- **Email**: sarah.teacher@school.com
- **Contraseña**: password
- **Rol**: teacher

### Estudiantes
- **Email**: alice.student@school.com
- **Contraseña**: password
- **Rol**: student

- **Email**: bob.student@school.com
- **Contraseña**: password
- **Rol**: student

## Estructura de la Base de Datos

### Tablas Principales

1. **users** - Usuarios del sistema (admin, teacher, student)
2. **students** - Información específica de estudiantes
3. **teachers** - Información específica de profesores
4. **courses** - Cursos académicos
5. **enrollments** - Matrículas de estudiantes en cursos
6. **grades** - Calificaciones de estudiantes
7. **attendance** - Registro de asistencia

### Relaciones

- Un usuario puede ser estudiante o profesor
- Los estudiantes se matriculan en cursos
- Los profesores enseñan cursos
- Se registran calificaciones y asistencia por curso

## Tecnologías Utilizadas

- **Laravel 10** - Framework PHP
- **JWT Auth** - Autenticación con tokens
- **L5-Swagger** - Documentación API
- **MySQL/PostgreSQL** - Base de datos
- **Eloquent ORM** - Mapeo objeto-relacional

## Desarrollo

### Comandos Útiles

```bash
# Generar documentación Swagger
php artisan l5-swagger:generate

# Limpiar caché
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Ejecutar tests
php artisan test

# Crear migración
php artisan make:migration create_nueva_tabla

# Crear modelo
php artisan make:model Modelo

# Crear controlador
php artisan make:controller ControladorController
```

### Estructura de Respuestas API

Todas las respuestas siguen un formato consistente:

```json
{
    "status": "success|error",
    "message": "Mensaje descriptivo",
    "data": { ... }
}
```

## Contribución

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## Soporte

Para soporte técnico o preguntas, contacta al equipo de desarrollo o crea un issue en el repositorio.