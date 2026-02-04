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
    ```
4.  **Archivo de Entorno**:
    Cree una copia del archivo `.env`:
    ```bash
    copy .env.example .env
    ```
    _Asegúrese de configurar sus credenciales de base de datos en el `.env` (DB_DATABASE, DB_USERNAME, etc.)._

## 3. Base de Datos (Sin necesidad de .sql)

Este proyecto utiliza **Migrations** y **Seeders** para generar la estructura y los datos automáticamente.
Ejecute:

```bash
php artisan migrate --seed
```

_Este comando creará todas las tablas y poblará la base de datos con:_

- 1 Administrador.
- 1 Cliente específico (Matilde).
- 1 Restaurante específico (La Buena Mesa).
- **8 Restaurantes aleatorios** adicionales.
- **Más de 40 Menús** distribuidos entre todos los restaurantes.
- **Horarios comerciales** (L-V 8:00 - 21:00).

## 4. Ejecución

Para visualizar la app con sus estilos modernos (Glassmorphism), debe tener abiertos dos terminales:

**Terminal 1 (Servidor PHP):**

```bash
php artisan serve
```

**Terminal 2 (Compilación de Estilos):**

```bash
npm run dev
```

---

## Cuentas de Prueba

- **Admin**: `admin@gmail.com` / `12345678m`
- **Cliente**: `matilde@gmail.com` / `password123`
- **Restaurante**: `labuenamesa@gmail.com` / `password123`
- **Cuentas Aleatorias**: Use cualquier email generado en la tabla `usuario` con la contraseña `password123`.
