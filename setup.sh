#!/bin/bash

echo "🎓 School Management API - Setup Script"
echo "========================================"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer no está instalado. Por favor instala Composer primero."
    exit 1
fi

# Check if PHP version is 8.1 or higher
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
if [[ $(echo "$PHP_VERSION < 8.1" | bc -l) -eq 1 ]]; then
    echo "❌ Se requiere PHP 8.1 o superior. Versión actual: $PHP_VERSION"
    exit 1
fi

echo "✅ PHP $PHP_VERSION detectado"

# Install dependencies
echo "📦 Instalando dependencias..."
composer install

# Copy environment file
if [ ! -f .env ]; then
    echo "📝 Configurando archivo de entorno..."
    cp .env.example .env
    php artisan key:generate
    echo "✅ Archivo .env creado"
else
    echo "⚠️  El archivo .env ya existe"
fi

# Generate JWT secret
echo "🔐 Generando clave JWT..."
php artisan jwt:secret

# Ask for database configuration
echo ""
echo "🗄️  Configuración de Base de Datos"
echo "=================================="
read -p "Host de la base de datos (default: 127.0.0.1): " DB_HOST
DB_HOST=${DB_HOST:-127.0.0.1}

read -p "Puerto de la base de datos (default: 3306): " DB_PORT
DB_PORT=${DB_PORT:-3306}

read -p "Nombre de la base de datos (default: api_school): " DB_DATABASE
DB_DATABASE=${DB_DATABASE:-api_school}

read -p "Usuario de la base de datos (default: root): " DB_USERNAME
DB_USERNAME=${DB_USERNAME:-root}

read -p "Contraseña de la base de datos: " DB_PASSWORD

# Update .env file with database configuration
sed -i "s/DB_HOST=.*/DB_HOST=$DB_HOST/" .env
sed -i "s/DB_PORT=.*/DB_PORT=$DB_PORT/" .env
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_DATABASE/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USERNAME/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env

echo "✅ Configuración de base de datos actualizada"

# Run migrations and seeders
echo "🚀 Ejecutando migraciones y seeders..."
php artisan migrate --seed

# Generate Swagger documentation
echo "📖 Generando documentación Swagger..."
php artisan l5-swagger:generate

echo ""
echo "🎉 ¡Configuración completada!"
echo "=============================="
echo ""
echo "🌐 Servidor: http://localhost:8000"
echo "📖 Documentación: http://localhost:8000/api/documentation"
echo ""
echo "👤 Usuarios de prueba:"
echo "   Admin: admin@school.com / password"
echo "   Profesor: john.teacher@school.com / password"
echo "   Estudiante: alice.student@school.com / password"
echo ""
echo "🚀 Para iniciar el servidor ejecuta:"
echo "   php artisan serve"
echo ""