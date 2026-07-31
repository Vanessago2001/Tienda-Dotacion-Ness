<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CajaProductoController;
use App\Http\Controllers\MovimientoCajaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\CajaAperturaCierreController;
use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VendedorController;
use App\Http\Controllers\ContadorController;
use App\Http\Controllers\VisitanteController;
use App\Http\Controllers\UserPermissionController;
use App\Http\Controllers\HistorialVentaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\ReporteController;

// PÁGINA PRINCIPAL: catálogo público (sin login)
Route::get('/', [CatalogoController::class, 'index'])->name('catalogo.index');


Route::get(
'/buscar-barcode/{barcode}',
[ProductController::class,'buscarBarcode']
)->name('products.barcode');


// 2. DASHBOARD PRINCIPAL: Solo para usuarios autenticados
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. RUTAS PROTEGIDAS POR MIDDLEWARE Y GATES
Route::middleware('auth')->group(function () {
    
    // Solo ADMIN: Gestión de empresa completa
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('companies', CompanyController::class);

        // Gestión de usuarios (crear, editar, activar/inactivar, eliminar)
        Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/crear', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{usuario}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{usuario}', [UserController::class, 'update'])->name('usuarios.update');
        Route::put('/usuarios/{usuario}/estado', [UserController::class, 'toggleActive'])->name('usuarios.estado');
        Route::delete('/usuarios/{usuario}', [UserController::class, 'destroy'])->name('usuarios.destroy');

        // Permisos individuales por usuario
        Route::get('/usuarios/{usuario}/permisos', [UserPermissionController::class, 'edit'])
            ->name('usuarios.permisos.edit');
        Route::put('/usuarios/{usuario}/permisos', [UserPermissionController::class, 'update'])
            ->name('usuarios.permisos.update');

        // Roles (solo etiqueta; los permisos se manejan por usuario)
        Route::resource('roles', RoleController::class)->except(['show']);
    });

    // ADMIN, VENDEDOR y CONTADOR: Gestión de facturas
    Route::resource('invoices', InvoiceController::class)
        ->middleware('can:gestionar-facturas');

    // TODOS (incluido Visitante): Ver productos
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    
    // Solo Admin/Vendedor: Crear y Editar productos
    Route::middleware('can:editar-inventario')->group(function () {
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // Ver detalles del producto (Debe ir después de 'create' para evitar colisiones)
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

    // Otros Recursos compartidos
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('reports', ReportController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('persons', PersonController::class);
    Route::resource('posts', PostController::class);

    // Paneles por rol (redirección desde /panel según el rol del usuario)
    Route::get('/panel', function () {
        return match (auth()->user()->role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'vendedor'  => redirect()->route('vendedor.dashboard'),
            'contador'  => redirect()->route('contador.dashboard'),
            'visitante' => redirect()->route('visitante.dashboard'),
            default     => redirect()->route('dashboard'),
        };
    })->name('panel');

    Route::get('/admin/dashboard', [AdminController::class, 'index'])
        ->middleware('role:admin')->name('admin.dashboard');
    Route::get('/vendedor/dashboard', [VendedorController::class, 'index'])
        ->middleware('role:vendedor')->name('vendedor.dashboard');
    Route::get('/contador/dashboard', [ContadorController::class, 'index'])
        ->middleware('role:contador')->name('contador.dashboard');
    Route::get('/visitante/dashboard', [VisitanteController::class, 'index'])
        ->middleware('role:visitante')->name('visitante.dashboard');

    // Rutas de Perfil (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Caja
    Route::get('/caja-productos', [CajaProductoController::class, 'index'])
        ->name('caja.productos.index');
    Route::get('/apertura-cierre-caja', [CajaAperturaCierreController::class, 'index'])
        ->name('apertura-cierre-caja.index');
    Route::post('/apertura-cierre-caja/abrir', [CajaAperturaCierreController::class, 'abrir'])
        ->name('apertura-cierre-caja.abrir');
    Route::post('/apertura-cierre-caja/cerrar/{caja}', [CajaAperturaCierreController::class, 'cerrar'])
        ->name('apertura-cierre-caja.cerrar');
    Route::post('/caja-productos', [CajaProductoController::class, 'store'])
        ->name('caja.productos.store');
    Route::delete('/caja-clientes/{cliente}', [CajaProductoController::class, 'destroyCliente'])
        ->name('caja.clientes.destroy');

    // Historial de ventas (requiere permiso 'ver-historial')
    Route::get('/historial-ventas', [HistorialVentaController::class, 'index'])
        ->middleware('can:ver-historial')
        ->name('historial-ventas.index');
    Route::get('/historial-ventas/{venta}', [HistorialVentaController::class, 'show'])
        ->middleware('can:ver-historial')
        ->name('historial-ventas.show');

    // Movimientos de inventario (requiere permiso 'gestionar-inventario')
    Route::middleware('can:gestionar-inventario')->group(function () {
        Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/inventario/crear', [InventarioController::class, 'create'])->name('inventario.create');
        Route::post('/inventario', [InventarioController::class, 'store'])->name('inventario.store');
    });

    // Auditoría del sistema (requiere permiso 'ver-auditoria')
    Route::get('/auditoria', [AuditoriaController::class, 'index'])
        ->middleware('can:ver-auditoria')
        ->name('auditoria.index');

    // Reportes
    Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])
        ->middleware('can:ver-reportes')
        ->name('reportes.ventas');
    Route::get('/reportes/caja-dia', [ReporteController::class, 'cajaDia'])
        ->middleware('can:abrir-cerrar-caja')
        ->name('reportes.caja-dia');

    // Movimientos de Caja
    Route::get('/movimientos-caja', [MovimientoCajaController::class, 'index'])
        ->name('movimientos-caja.index');
    Route::post('/movimientos-caja', [MovimientoCajaController::class, 'store'])
        ->name('movimientos-caja.store');
    Route::get('/movimientos-caja/{movimiento}/edit', [MovimientoCajaController::class, 'edit'])
        ->name('movimientos-caja.edit');
    Route::put('/movimientos-caja/{movimiento}', [MovimientoCajaController::class, 'update'])
        ->name('movimientos-caja.update');
    Route::put('/movimientos-caja/{movimiento}/anular', [MovimientoCajaController::class, 'anular'])
        ->name('movimientos-caja.anular');
    Route::delete('/movimientos-caja/{movimiento}', [MovimientoCajaController::class, 'destroy'])
        ->name('movimientos-caja.destroy');
        
    // Facturas y Notas de Crédito
Route::get('/facturas', [FacturaController::class, 'index'])
    ->name('facturas.index');

Route::get('/facturas/{factura}', [FacturaController::class, 'show'])
    ->name('facturas.show');

Route::post('/facturas/generar/{venta}', [FacturaController::class, 'generarDesdeVenta'])
    ->name('facturas.generar');

Route::get('/facturas/{factura}/imprimir', [FacturaController::class, 'imprimir'])
    ->name('facturas.imprimir');

Route::get('/notas-credito', [FacturaController::class, 'notasCredito'])
    ->name('notas-credito.index');

    // Editar/actualizar facturas - Solo Admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/facturas/{factura}/edit', [FacturaController::class, 'edit'])
    ->name('facturas.edit');

Route::put('/facturas/{factura}', [FacturaController::class, 'update'])
    ->name('facturas.update');
    });

    // Anular factura: cualquier usuario con el permiso 'anular-facturas'
    // (el admin lo tiene siempre). Así un cajero puede o no anular según
    // lo que le asigne el administrador.
    Route::put('/facturas/{factura}/anular', [FacturaController::class, 'anular'])
        ->middleware('can:anular-facturas')
        ->name('facturas.anular');
});


// 4. RUTAS DE AUTENTICACIÓN (Breeze)
// MUY IMPORTANTE: Esta línea carga las rutas de login, registro, etc.
require __DIR__ .'/auth.php';