# Guía de Instalación y Ejecución - ComfortFood

Este proyecto ha sido desarrollado con **Laravel 12**, **Livewire 4** y **Flux UI**. Siga estos pasos para poner la aplicación en marcha sin necesidad de un archivo SQL externo.

## 1. Requisitos Previos

- PHP >= 8.2 (Recomendado XAMPP con PHP actualizado)
- Composer
- Node.js & NPM
- MySQL o MariaDB (XAMPP Control Panel)

## 2. Configuración Inicial

1.  **Directorio**: Sitúese en la carpeta raíz del proyecto (`ComfortFood_front`).
2.  **Dependencias PHP**:
    ```bash
    composer install
    ```
3.  **Dependencias Frontend**:
    ```bash
    npm install
    npm run dev
    ```
4.  **Archivo de Entorno**:
    Cree una copia del archivo `.env`:

    ```bash
    copy .env.example .env
    ```

    _Asegúrese de configurar sus credenciales de base de datos en el `.env` (DB_DATABASE, DB_USERNAME, etc.)._

5.  **Generar la Clave de Aplicación** _(obligatorio, sin esto la app no arranca)_:

    ```bash
    php artisan key:generate
    ```

6.  **Enlace Simbólico de Storage**:
    ```bash
    php artisan storage:link
    ```

## 3. Base de Datos (Sin necesidad de .sql)

Este proyecto utiliza **Migrations** y **Seeders** para generar la estructura y los datos automáticamente.
Ejecute:

```bash
php artisan migrate --seed
```

_Este comando creará todas las tablas y poblará la base de datos con:_

- 1 Administrador.
- 1 Cliente.
- 1 Restaurante.
- **8 Restaurantes aleatorios** adicionales.
- **Más de 40 Menús** distribuidos entre todos los restaurantes.

## 4. Ejecución

Para visualizar la app debe tener abiertos dos terminales:

**Terminal 1 (Servidor PHP):**

```bash
php artisan serve
```

**Terminal 2 (Assets CSS/JS en tiempo real):**

```bash
npm run dev
```

> **Diferencia entre `npm run dev` y `npm run build`:**
>
> - `npm run dev` → Activa Vite en modo desarrollo (hot reload, recomendado para probar la app).
> - `npm run build` → Compila los assets una sola vez y genera la carpeta `public/build/`. Usar si no se quiere mantener el terminal abierto.

---

## Cuentas de Prueba

- **Admin**: `admin@gmail.com` / `password123`
- **Cliente**: `cliente@gmail.com` / `password123`
- **Restaurante**: `restaurante@gmail.com` / `password123`
- **Cuentas Aleatorias**: Use cualquier email generado en la tabla `usuario` con la contraseña `password123`.
