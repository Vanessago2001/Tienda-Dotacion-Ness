<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $venta = \App\Models\Venta::latest()->first();
    if (!$venta) {
        echo "No hay ventas.\n";
        exit;
    }
    echo "Venta ID: {$venta->id}\n";
    $controller = app(\App\Http\Controllers\FacturaController::class);
    $response = $controller->generarDesdeVenta($venta);
    echo "Respuesta HTTP Code: " . $response->getStatusCode() . "\n";
    echo "Factura count: " . \App\Models\Factura::count() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
