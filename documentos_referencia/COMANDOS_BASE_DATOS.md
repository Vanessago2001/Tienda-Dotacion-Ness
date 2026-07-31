# Comandos para dejar la Base de Datos igual al proyecto

Guía paso a paso con TODOS los comandos que debes ejecutar para que tu base de
datos y el entorno queden funcionando con este proyecto (POS en Laravel 12).

Ejecuta los comandos desde la raíz del proyecto:
`/mnt/storage/ANDRES/felipejunio`

> Nota: en tu equipo debes tener **PHP 8.2+**, **Composer**, **MySQL/MariaDB** y
> **Node.js** instalados. En este entorno de edición no estaban disponibles, por
> eso estos comandos los corres tú.

---

## 1. Base de datos MySQL

El archivo `.env` ya está configurado así:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=proyecto_laravel
DB_USERNAME=root
DB_PASSWORD=270820+
```

Crea la base de datos (si no existe). En la terminal de MySQL o en phpMyAdmin:

```sql
CREATE DATABASE proyecto_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

O desde la terminal del sistema:

```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS proyecto_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

---

## 2. Dependencias de PHP y autoloader  ⚠️ IMPORTANTE

```bash
composer install
composer dump-autoload
```

> **¿Por qué `composer dump-autoload` es obligatorio aquí?**
> Se renombraron varios modelos para cumplir el estándar **PSR-4**
> (por ejemplo `product.php` → `Product.php`, `customer.php` → `Customer.php`).
> `composer dump-autoload` regenera el mapa de clases para que Laravel encuentre
> los modelos con su nombre correcto. Sin este comando, en Linux (sistema
> sensible a mayúsculas/minúsculas) podría aparecer el error
> *"Class App\Models\Product not found"*.

---

## 3. Clave de la aplicación

La clave (`APP_KEY`) ya existe en el `.env`. Si necesitaras regenerarla:

```bash
php artisan key:generate
```

---

## 4. Ejecutar las migraciones (crear todas las tablas)

**Opción A — Instalación desde cero (recomendada la primera vez):**

```bash
php artisan migrate:fresh
```

**Opción B — Aplicar solo las migraciones pendientes (si ya tienes datos):**

```bash
php artisan migrate
```

Esto crea, en orden, las tablas del sistema:

| Tabla | Módulo |
|-------|--------|
| `users`, `cache`, `jobs` | Base / Autenticación (Breeze) |
| `persons` | CRUD Personas |
| `products` (+ `barcode`) | Productos / Inventario |
| `suppliers` | Proveedores |
| `customers` (+ ajustes phone/document) | Clientes |
| `reports` | Reportes |
| `categories` | Categorías |
| `invoices`, `companies` | Facturación base / Empresa |
| `posts` | Publicaciones (CRUD) |
| `users.photo` | Foto de perfil de usuario |
| `clientes` | (tabla del módulo ventas — ver nota al final) |
| `ventas`, `venta_detalles` | Caja con productos (POS) |
| `movimiento_cajas` | Gastos / Movimientos de caja |
| `facturas`, `factura_detalles` | Módulo de Facturas |
| `notas_credito`, `nota_credito_detalles` | Anulación / Nota crédito |
| `caja_apertura_cierres` | Apertura y Cierre de Caja |

---

## 5. Enlace simbólico de almacenamiento (fotos)

```bash
php artisan storage:link
```

> Crea el enlace `public/storage → storage/app/public` para que las imágenes
> (fotos de usuario, productos, etc.) se vean con `asset('storage/...')`.
> En este proyecto el enlace **ya fue creado**, pero si clonas el repo en otro
> equipo debes ejecutarlo de nuevo. Sin él, las imágenes dan error 404.

---

## 6. Crear usuarios de prueba (uno por rol)

Los roles del proyecto son: **admin, vendedor, contador, visitante, cajero**.

La forma recomendada es con el **seeder** (evita el Tinker, que en PowerShell/Windows
falla al pegar varias líneas):

```bash
php artisan db:seed --class=UserSeeder
```

Esto crea (o actualiza) 5 usuarios, **todos activos**, con contraseña `12345678`:

| Correo | Rol |
|--------|-----|
| admin@test.com | admin |
| vendedor@test.com | vendedor |
| contador@test.com | contador |
| cajero@test.com | cajero |
| visitante@test.com | visitante |

> El `UserSeeder` es **idempotente**: puedes correrlo varias veces sin duplicar, y
> además **reactiva** cualquiera de estos usuarios que estuviera inactivo.
>
> **Importante:** después de crear los usuarios, corre
> `php artisan db:seed --class=PermissionSeeder` para que reciban sus permisos
> según el rol (ver nota del "ORDEN MAESTRO").

---

## 7. Frontend (Breeze + Tailwind)

```bash
npm install
npm run build      # producción
# o, para desarrollo con recarga:
npm run dev
```

---

## 8. Levantar el servidor

```bash
php artisan serve
```

Abre: http://127.0.0.1:8000

---

## 9. Si algo no refresca (limpiar cachés)

```bash
php artisan optimize:clear
```

---

## ⭐ ORDEN MAESTRO — aplicar TODOS los cambios a una base de datos existente

Si ya tienes la base de datos funcionando y solo quieres **ponerla al día con todo
lo nuevo** (permisos, historial, roles, inventario, auditoría, reportes), no hace
falta correr las secciones 10, 11 y 12 por separado: `php artisan migrate` aplica
todas las migraciones pendientes de una sola vez. Ejecuta **exactamente en este
orden**:

```bash
cd /mnt/storage/ANDRES/felipejunio

# 1. Registrar todos los modelos nuevos en el autoloader
composer dump-autoload

# 2. Aplicar TODAS las migraciones pendientes (permissions, ventas.user_id,
#    roles, users.active, movimiento_inventarios, auditorias, facturas.anulada_por)
php artisan migrate

# 3. Sembrar roles base y permisos (idempotente, no duplica)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder

# 4. Limpiar cachés
php artisan optimize:clear
```

> ⚠️ **Orden importante:** el `PermissionSeeder` asigna los permisos por defecto a
> los usuarios que **ya existen**. Si acabas de crear usuarios nuevos (por Tinker o
> desde la app), vuelve a correr `php artisan db:seed --class=PermissionSeeder`
> **después** para que reciban sus permisos según el rol.

Las secciones 10, 11 y 12 de abajo son el detalle de cada entrega (por si quieres
entender qué introdujo cada una), pero el bloque de arriba las cubre todas.

---

## 10. Módulo de Permisos por Usuario (cajeros) ⭐ NUEVO

Se agregó un sistema de **permisos individuales por usuario**: el administrador
puede darle a cada cajero permisos distintos (por ejemplo, que un cajero pueda
anular facturas y otro no). El admin siempre tiene todos los permisos.

Esto crea las tablas `permissions` y `permission_user`, y un catálogo de 8
permisos. Ejecuta **en este orden**:

```bash
# 1. Registrar los modelos nuevos (Permission) en el autoloader
composer dump-autoload

# 2. Crear las tablas permissions y permission_user
php artisan migrate

# 3. Cargar el catálogo de permisos y asignar los de siempre según el rol
php artisan db:seed --class=PermissionSeeder

# 4. Limpiar cachés
php artisan optimize:clear
```

> El seeder es **idempotente**: puedes ejecutarlo varias veces sin duplicar
> datos. Si en cambio reinstalas desde cero con `php artisan migrate:fresh --seed`,
> el `PermissionSeeder` ya queda incluido en el `DatabaseSeeder`, así que no
> necesitas correrlo aparte.

Después, entra como **admin** → menú **Usuarios** → *Editar permisos* de cada
cajero y marca las casillas.

Permisos por defecto asignados por el seeder (para no perder el comportamiento
actual): vendedor y contador conservan lo que tenían; el cajero arranca con
`gestionar-facturas` + `abrir-cerrar-caja`.

---

## 11. Módulo de Historial de Ventas ⭐ NUEVO

Se agregó el **historial completo de ventas** con filtros (fecha, estado, método
de pago, cliente), detalle de cada venta con fotos de producto, e impresión /
anulación de la factura. Además, ahora cada venta guarda **qué cajero la
registró** (nueva columna `user_id` en la tabla `ventas`).

Ejecuta **en este orden**:

```bash
# 1. Nueva columna user_id (cajero) en la tabla ventas
php artisan migrate

# 2. Registrar el nuevo permiso 'ver-historial' y asignarlo a los roles
php artisan db:seed --class=PermissionSeeder

# 3. Limpiar cachés
php artisan optimize:clear
```

> Las ventas registradas **antes** de esta actualización tendrán el cajero
> vacío ("—") porque no se guardaba ese dato; las nuevas sí lo mostrarán.
>
> El permiso `ver-historial` se asigna por defecto a vendedor, contador y
> cajero. El admin siempre lo tiene. Puedes ajustarlo por usuario desde
> **/usuarios**.

---

## 12. Usuarios, Roles, Inventario, Auditoría y Reportes ⭐ NUEVO

Esta entrega agrega varios módulos y tablas nuevas:

- **Usuarios**: crear, editar, activar/inactivar y eliminar usuarios (los inactivos
  no pueden iniciar sesión). Columna nueva `active` en `users`.
- **Roles**: crear/editar/eliminar roles (son etiquetas; los permisos siguen
  siendo por usuario). Tabla nueva `roles`.
- **Inventario**: entradas, salidas y ajustes con historial. Tabla nueva
  `movimiento_inventarios`. Las ventas y anulaciones registran movimientos
  automáticamente.
- **Auditoría**: registro de accesos, gestión de usuarios/permisos, anulaciones,
  etc. Tabla nueva `auditorias` + columna `anulada_por` en `facturas`.
- **Reportes**: informe de ventas (admin) e informe de caja del día (cajero).

Ejecuta **en este orden**:

```bash
# 1. Registrar los modelos nuevos (Role, MovimientoInventario, Auditoria)
composer dump-autoload

# 2. Crear las tablas y columnas nuevas
php artisan migrate

# 3. Sembrar los roles base y los permisos nuevos (gestionar-inventario, ver-auditoria)
php artisan db:seed --class=RoleSeeder
php artisan db:seed --class=PermissionSeeder

# 4. Limpiar cachés
php artisan optimize:clear
```

> Los permisos nuevos se asignan por defecto: `gestionar-inventario` al vendedor,
> y `ver-auditoria` solo al admin. Ajusta por usuario desde **/usuarios**.
>
> Importante: para **crear usuarios** la tabla `roles` debe estar sembrada
> (paso 3), porque el formulario valida el rol contra ella.

---

## Resumen ultra-rápido (copiar y pegar)

```bash
# 1. Crear BD (una sola vez)
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS proyecto_laravel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Dependencias + autoloader (obligatorio tras el renombrado de modelos)
composer install
composer dump-autoload

# 3. Migraciones + seeders (crea tablas e incluye el catálogo de permisos)
php artisan migrate:fresh --seed

# 4. Enlace de imágenes
php artisan storage:link

# 5. Frontend
npm install && npm run build

# 6. Usuarios de prueba -> ver paso 6 (php artisan tinker)

# 7. Dar permisos por defecto a los usuarios recién creados
#    (el seeder los asigna a los usuarios que YA existen)
php artisan db:seed --class=PermissionSeeder

# 8. Servir
php artisan serve
```

> El paso 7 es clave: `migrate:fresh --seed` ya sembró roles y permisos, pero los
> usuarios de prueba se crean en el paso 6 (después), así que hay que volver a
> correr `PermissionSeeder` para que vendedor, contador y cajero reciban sus
> permisos. El admin funciona siempre (tiene todos los permisos por defecto).

---

## OPCIONAL — Ajuste recomendado (NO obligatorio)

En la tabla `products`, las columnas `price`, `cost` y `stock` son de tipo
**`integer`**. La guía "modulo caja productos" sugiere `decimal(12,2)` para
precios. En Colombia (pesos sin centavos) el `integer` funciona bien, por eso
**no se cambió** para no alterar tus datos. Si quieres precios con decimales,
crea esta migración y ejecútala:

```bash
php artisan make:migration change_price_to_decimal_in_products_table --table=products
```

Contenido:

```php
public function up(): void {
    Schema::table('products', function (Blueprint $table) {
        $table->decimal('price', 12, 2)->change();
        $table->decimal('cost', 12, 2)->change();
    });
}
public function down(): void {
    Schema::table('products', function (Blueprint $table) {
        $table->integer('price')->change();
        $table->integer('cost')->change();
    });
}
```

```bash
php artisan migrate
```

## Nota sobre la tabla `clientes`
Existe una tabla `clientes` (modelo `Cliente`) heredada de las guías, pero el
sistema real usa la tabla `customers` (modelo `Customer`) para ventas y
facturas. La tabla `clientes` queda creada por compatibilidad de migraciones,
pero **no se usa**. No la borres manualmente sin revisar las migraciones de
`ventas`, porque la primera migración de `ventas` la referencia antes de
reapuntar a `customers`.
