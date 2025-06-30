# 🧠 Decameron API

Este es el backend (la parte lógica del sistema) del proyecto Decameron, construido con **Laravel**.  
A continuación encontrarás una guía paso a paso para que puedas ejecutarlo localmente, explicado como si se lo contáramos a mi abuelita 👵.

---

## 🧰 Requisitos antes de comenzar

### 1. Tener instalado PHP (versión 8.1 o superior)

Puedes verificar si ya lo tienes con:

```bash
php -v
```

Si no lo tienes, puedes descargarlo desde [https://www.php.net/downloads.php](https://www.php.net/downloads.php) o instalarlo con un gestor de paquetes como Homebrew, Chocolatey, apt, etc.

### 2. Tener instalado Composer

Composer es el gestor de dependencias de PHP (como `npm` pero para Laravel).

Para instalarlo: [https://getcomposer.org/download/](https://getcomposer.org/download/)

Luego, verifica con:

```bash
composer -V
```

### 3. Tener instalado PostgreSQL

Este proyecto usa PostgreSQL como base de datos. Puedes descargarlo desde: [https://www.postgresql.org/download/](https://www.postgresql.org/download/)

### 4. Tener instalado Laravel CLI (opcional)

Puedes instalar Laravel globalmente con:

```bash
composer global require laravel/installer
```

---

## 🚀 Instrucciones paso a paso para ejecutar el proyecto

### 1. Clonar el repositorio

```bash
git clone https://github.com/JohanFarfan25/decameron-api.git
```

### 2. Entrar a la carpeta del proyecto

```bash
cd decameron-api
```

### 3. Instalar las dependencias

```bash
composer install
```

### 4. Copiar el archivo de variables de entorno

```bash
cp .env.example .env
```

### 5. Configurar el archivo `.env`

Abre el archivo `.env` y configura los datos de conexión a tu base de datos PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=decameron
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### 6. Generar la clave de la aplicación

```bash
php artisan key:generate
```

### 7. Crear las tablas de la base de datos

```bash
php artisan migrate
```

(Si tienes datos por defecto, puedes correr también `php artisan db:seed`)

### 8. Iniciar el servidor de desarrollo

```bash
php artisan serve
```

Esto levantará el backend en:

```
http://127.0.0.1:8000
```

¡Y listo! Ya tienes funcionando el backend 🎉

---

## 🧼 ¿Cómo lo apago?

Simplemente presiona `Ctrl + C` en la terminal donde esté corriendo.

---

## 🧑‍💻 Tecnologías utilizadas

- 🐘 PHP 8.1+
- 🧱 Laravel
- 🐘 PostgreSQL
- ⚙️ Composer

---

## 🆘 ¿Problemas?

Si tienes errores, asegúrate de que:

1. PHP, Composer y PostgreSQL estén instalados.
2. El archivo `.env` esté bien configurado.
3. La base de datos esté creada antes de correr las migraciones.

También puedes abrir un issue en este repositorio.

---

## ✨ ¡Gracias por usar este backend!
