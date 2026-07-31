<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\DB::transaction(function () {
        $cliente = \App\Models\Customer::first();
        $producto = \App\Models\Product::first();
        
        if (!$cliente || !$producto) {
            echo "Faltan datos para crear venta de prueba.";
            return;
        }

        $venta = \App\Models\Venta::create([
            'cliente_id' => $cliente->id,
            'numero_venta' => 'V-TEST-' . now()->format('YmdHis'),
            'metodo_pago' => 'efectivo',
            'subtotal' => 100,
            'total' => 100,
            'dinero_recibido' => 100,
            'cambio' => 0,
            'estado' => 'pagada',
            'fecha' => now()->toDateString(),
        ]);

        \App\Models\VentaDetalle::create([
            'venta_id' => $venta->id,
            'product_id' => $producto->id,
            'cantidad' => 1,
            'precio_unitario' => 100,
            'subtotal' => 100,
        ]);

        $controller = app(\App\Http\Controllers\FacturaController::class);
        $controller->generarDesdeVenta($venta);
        
        echo "VENTA Y FACTURA CREADAS CON EXITO!\n";
    });
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
