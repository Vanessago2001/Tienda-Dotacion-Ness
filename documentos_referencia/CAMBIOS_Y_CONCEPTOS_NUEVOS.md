# Cambios aplicados y conceptos nuevos (para estudiar)

Este documento explica **qué se corrigió** en el proyecto para que cumpla con las
guías de `documentos_referencia`, y **qué conceptos nuevos** (que no estaban
explícitos en las guías) se usaron, para que puedas estudiarlos.

La regla que se siguió: cambios **mínimos** y apegados a las guías. Lo poco que
es "nuevo" está explicado abajo.

---

## PARTE 1 — Bugs corregidos (bloqueaban módulos)

### 1.1 Facturación no generaba la factura
- **Archivo:** `app/Http/Controllers/FacturaController.php` (método `generarDesdeVenta`, línea ~79).
- **Antes:** `'producto_id' => $detalle->producto_id`
- **Después:** `'producto_id' => $detalle->product_id`
- **Por qué:** `$detalle` es un `VentaDetalle`, cuya columna real es `product_id`
  (no `producto_id`). Al leer un campo inexistente devolvía `null`, y como
  `factura_detalles.producto_id` es obligatorio (NOT NULL), fallaba toda la
  transacción. En cascada tampoco se creaba la nota crédito ni se reintegraba el
  stock. **Concepto a estudiar:** diferencia entre la *clave del array*
  (`'producto_id'`, columna destino de `factura_detalles`) y la *propiedad del
  modelo origen* (`$detalle->product_id`, columna de `venta_detalles`).

### 1.2 Módulo de Gastos/Movimientos no cargaba
- **Archivo:** `routes/web.php`.
- **Qué faltaba:** las rutas `movimientos-caja.edit`, `.update`, `.anular`,
  `.destroy`. La vista las invocaba con `route(...)` → `RouteNotFoundException`.
- **Se agregaron** esas 4 rutas apuntando a métodos que ya existían en
  `MovimientoCajaController`.
- **Concepto a estudiar:** *Route Model Binding*. Las rutas usan `{movimiento}` y
  el controlador recibe `MovimientoCaja $movimiento`; Laravel busca el registro
  por id automáticamente.

### 1.3 Ruta `/usuarios` daba 403 a todos
- **Archivo:** `app/Providers/AppServiceProvider.php`.
- **Qué pasaba:** la ruta usaba el permiso `can:gestionar-usuarios`, pero ese
  **Gate no existía**. Un Gate no definido niega el acceso a todos (incluido
  admin).
- **Se agregó** el Gate `gestionar-usuarios` (solo admin).

### 1.4 Las fotos no se veían (404)
- **Qué faltaba:** el enlace `public/storage`.
- **Se creó** el enlace simbólico (equivale a `php artisan storage:link`).

### 1.5 CRUD de Personas y Posts inalcanzables
- **Archivo:** `routes/web.php`.
- **Qué faltaba:** no había rutas para `persons` ni `posts`, aunque sí existían
  controlador y vistas.
- **Se agregó:** `Route::resource('persons', ...)` y `Route::resource('posts', ...)`.

### 1.6 Factura térmica salía sin nombre de cliente ni de productos
- **Archivo:** `resources/views/facturas/imprimir.blade.php`.
- **Antes:** `->nombre` y `->documento` (cliente) y `->nombre` (producto).
- **Después:** `->name` y `->document` (cliente) y `->name` (producto).
- **Por qué:** los modelos `Customer` y `Product` usan los campos en inglés
  (`name`, `document`), no en español.

### 1.7 Vista de visitante mal nombrada
- Se renombró `resources/views/visitante/dashcoard.blade.php`
  → `dashboard.blade.php` (tenía una errata que rompía `VisitanteController`).

---

## PARTE 2 — Problemas de lógica corregidos

### 2.1 Doble conteo de ventas en el cierre de caja
- **Archivo:** `app/Http/Controllers/CajaAperturaCierreController.php`
  (método `calcularResumenCaja`).
- **Se agregó** a la consulta de *entradas adicionales*:
  `->where('concepto', 'not like', 'Venta POS%')`.
- **Por qué:** cada venta del POS también crea un movimiento de caja tipo
  "entrada" con concepto `"Venta POS ..."`. Sin este filtro, esa venta se sumaba
  dos veces (como venta y como entrada). Ahora solo cuenta las entradas
  manuales. Es el **mismo filtro** que ya usaba `MovimientoCajaController`.
- **Concepto a estudiar:** operador `LIKE` de SQL dentro de Eloquent
  (`where('columna', 'not like', 'texto%')`). El `%` es comodín.

### 2.2 Cliente nuevo en caja podía chocar por email duplicado
- **Archivo:** `app/Http/Controllers/CajaProductoController.php` (líneas 93 y 107).
- **Antes:** `'email' => 'caja_' . time() . '@tienda.com'`
- **Después:** `'email' => 'caja_' . uniqid() . '@tienda.com'`
- **Por qué:** `customers.email` es único. `time()` da el mismo valor si hay dos
  ventas en el mismo segundo → error de duplicado. `uniqid()` genera un valor
  único (resolución de microsegundos).
- **Concepto a estudiar (NUEVO):** función PHP **`uniqid()`** vs `time()`.
  `time()` = segundos desde 1970 (se repite dentro del mismo segundo).
  `uniqid()` = identificador basado en microsegundos (prácticamente único).

### 2.3 Formulario de alta de cliente no subía la foto
- **Archivo:** `resources/views/customers/create.blade.php`.
- **Se agregó** `enctype="multipart/form-data"` al `<form>` y se cambió el campo
  de foto de `type="text"` a `type="file" accept="image/*"` (igual que en
  `customers/edit.blade.php`).
- **Concepto a estudiar:** sin `enctype="multipart/form-data"` un formulario HTML
  no puede enviar archivos.

---

## PARTE 3 — Estandarización de nombres de modelos (PSR-4)

Se renombraron los archivos de modelo para que coincidan con el nombre de la
clase (convención **PSR-4** que exige Laravel):

| Antes (archivo) | Después (archivo) | Clase |
|-----------------|-------------------|-------|
| `product.php`  | `Product.php`  | `Product`  |
| `customer.php` | `Customer.php` | `Customer` |
| `category.php` | `Category.php` | `Category` |
| `supplier.php` | `Supplier.php` | `Supplier` |
| `company.php`  | `Company.php`  | `Company`  |
| `report.php`   | `Report.php`   | `Report`   |
| `invoice.php`  | `Invoice.php`  | `Invoice`  |
| `person.php`   | `Person.php`   | `person` → `Person` |

Para `Person` además se cambió el **nombre de la clase** de `person` a `Person`
y se actualizó `use App\Models\Person;` en `PersonController`.

- **Por qué:** en Linux el sistema de archivos distingue mayúsculas/minúsculas.
  PSR-4 espera que la clase `App\Models\Product` esté en `app/Models/Product.php`.
  Con el nombre en minúscula fallaba con *"Class not found"*.
- **Acción requerida:** ejecutar `composer dump-autoload` (ver
  `COMANDOS_BASE_DATOS.md`, paso 2).
- **Concepto a estudiar:** el estándar **PSR-4** de autocarga de clases.

---

## PARTE 4 — Roles: rutas y menú (guía "Login roles permisos")

La guía pide redirección por rol y menú según rol. Se implementó así:

### 4.1 Paneles por rol (rutas)  — NUEVO respecto al estado anterior
- **Archivo:** `routes/web.php`.
- Se activaron rutas para los controladores que ya existían pero estaban sin usar
  (`AdminController`, `VendedorController`, `ContadorController`,
  `VisitanteController`):
  `admin.dashboard`, `vendedor.dashboard`, `contador.dashboard`,
  `visitante.dashboard`, cada una protegida con `->middleware('role:...')`.
- Se agregó una ruta `/panel` (nombre `panel`) que **redirige según el rol**:

```php
Route::get('/panel', function () {
    return match (auth()->user()->role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'vendedor'  => redirect()->route('vendedor.dashboard'),
        'contador'  => redirect()->route('contador.dashboard'),
        'visitante' => redirect()->route('visitante.dashboard'),
        default     => redirect()->route('dashboard'),
    };
})->name('panel');
```

- **Concepto a estudiar (NUEVO):** la expresión **`match()`** de PHP 8 (parecida a
  un `switch` pero más corta y segura). Devuelve un valor según el caso.
- **Nota:** el login sigue llevando al `dashboard` general (que ya funciona para
  todos los roles y es más completo). `/panel` queda disponible como entrada por
  rol. No se forzó el cambio de la redirección de login para no romper el
  dashboard actual.

### 4.2 Menú según el rol
- **Archivo:** `resources/views/layouts/navigation.blade.php`.
- Se agregaron enlaces del menú que aparecen/desaparecen según el permiso del
  usuario, usando la directiva **`@can`** de Blade (que consulta los Gates ya
  definidos: `gestionar-facturas`, `ver-reportes`, `gestionar-empresa`).
- **Concepto a estudiar:** directiva Blade **`@can('permiso') ... @endcan`**
  (ya aparece en la guía de roles, sección 14).

---

## PARTE 5 — Otros ajustes menores

### 5.1 Vista de facturas correcta
- **Archivo:** `app/Http/Controllers/FacturaController.php` (método `index`).
- **Antes:** `return view('invoices.index', ...)`
- **Después:** `return view('facturas.index', ...)`
- **Por qué:** `facturas/index.blade.php` es la vista propia del módulo y sí tiene
  el botón "Imprimir". Las variables que pasa el controlador son compatibles.

---

## PARTE 6 — Cosas que NO se cambiaron (y por qué) — pendientes de decisión

Para no introducir riesgos ni cosas fuera de las guías, se dejaron como están y
se documentan aquí:

1. **`products.price/cost/stock` son `integer`** (la guía sugiere `decimal`).
   Funciona para pesos colombianos sin centavos. Si quieres decimales, hay una
   migración lista y explicada en `COMANDOS_BASE_DATOS.md` (sección OPCIONAL).

2. **Tablas `customers` (usada) y `clientes` (no usada) coexisten.** Unificarlas
   es un cambio grande y arriesgado; se dejó documentado. No borres `clientes`
   sin revisar las migraciones de `ventas`.

3. **`resources/views/dashboard.blade.php` (en la raíz de views) es código
   muerto:** el dashboard real es `dashboard/index.blade.php`. Se dejó el archivo
   por precaución (no se borró nada), pero puedes eliminarlo si quieres limpiar.

4. **No existe un CRUD completo de usuarios** (la guía de foto menciona un
   listado de usuarios). La foto del usuario se gestiona desde su **Perfil**
   (Breeze). La ruta `/usuarios` es solo un marcador (devuelve un texto). Crear
   un módulo de administración de usuarios sería una funcionalidad nueva grande;
   no se agregó para no salirse de las guías.

---

## Resumen de conceptos nuevos para estudiar
- **PSR-4** (autocarga de clases y por qué el nombre de archivo debe coincidir con la clase).
- **`composer dump-autoload`** (regenerar el mapa de clases).
- **`uniqid()`** de PHP (vs `time()`).
- **`match()`** de PHP 8 (vs `switch`).
- **Route Model Binding** con `{parametro}` + type-hint del modelo.
- **`LIKE` / `not like` con `%`** dentro de Eloquent.
- **`enctype="multipart/form-data"`** para subir archivos.
- **`@can` / Gates** en Blade (ya en la guía de roles).
- **`php artisan storage:link`** (enlace de almacenamiento público).
