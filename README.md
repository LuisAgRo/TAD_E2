# BermellónShop 🎨

Tienda online de arte hecho a mano. Proyecto desarrollado con Laravel 13 para la asignatura TAD.

## Requisitos previos

- PHP 8.2+
- Composer
- Node.js y npm
- MySQL

## Instalación

### 1. Clonar el repositorio
```bash
git clone <url-del-repo>
cd shopping_web
```

### 2. Instalar dependencias
```bash
composer install
npm install
```

### 3. Configurar el entorno
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos
Abre `.env` y edita estas líneas con tus credenciales:### 5. Crear la base de datos
En MySQL o phpMyAdmin crea una base de datos llamada `bermellonshop`.

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate
```

### 6. Ejecutar seeder
```bash
php artisan db:seed
```

### 7. Compilar assets
```bash
npm run build
```

### 8. Enlace de almacenamiento para imágenes
```bash
php artisan storage:link
```

### 9. Enlace de almacenamiento para imágenes
```bash
php artisan storage:link
```

### 10. Arrancar el servidor
```bash
php artisan serve
```

Accede en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

## Configuración de email (Mailtrap)

1. Crea cuenta en [mailtrap.io](https://mailtrap.io)
2. Ve a Email Testing → My Sandbox → SMTP
3. Copia las credenciales en tu `.env`:

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bermellonshop.com
MAIL_FROM_NAME="BermellonShop"


## 🔐 Credenciales de prueba

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Admin** | admin@bermellonshop.com | admin1234 |
| **Cliente** | cliente@bermellonshop.com | cliente1234 |

