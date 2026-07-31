<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
echo "Ventas: " . \App\Models\Venta::count() . "\n";
echo "VentaDetalles: " . \App\Models\VentaDetalle::count() . "\n";
echo "Facturas: " . \App\Models\Factura::count() . "\n";
echo "FacturaDetalles: " . \App\Models\FacturaDetalle::count() . "\n";
echo "MovimientoCaja: " . \App\Models\MovimientoCaja::count() . "\n";
