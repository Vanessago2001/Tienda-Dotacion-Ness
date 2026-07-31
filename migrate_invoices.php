<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\DB::transaction(function () {
        $invoices = \App\Models\Invoice::all();
        $producto = \App\Models\Product::first();

        foreach ($invoices as $invoice) {
            // Find or create customer
            $customerName = $invoice->customer;
            $customer = \App\Models\Customer::firstOrCreate(
                ['name' => $customerName],
                [
                    'document' => rand(1000000, 9999999),
                    'document_type' => 'CC',
                    'email' => 'migrated_' . rand() . '@example.com',
                    'phone' => '000000',
                    'address' => 'Desconocida',
                ]
            );

            // Extract numero_venta
            preg_match('/V-\d+/', $invoice->description, $matches);
            $numeroVenta = $matches[0] ?? 'V-MIG-' . time() . rand(10,99);

            $venta = \App\Models\Venta::create([
                'cliente_id' => $customer->id,
                'numero_venta' => $numeroVenta,
                'metodo_pago' => 'efectivo',
                'subtotal' => $invoice->price * $invoice->amount,
                'total' => $invoice->price * $invoice->amount,
                'dinero_recibido' => $invoice->price * $invoice->amount,
                'cambio' => 0,
                'estado' => 'pagada',
                'fecha' => \Carbon\Carbon::parse($invoice->date)->toDateString(),
            ]);

            \App\Models\VentaDetalle::create([
                'venta_id' => $venta->id,
                'product_id' => $producto->id, // Default to first product if unknown
                'cantidad' => $invoice->amount,
                'precio_unitario' => $invoice->price,
                'subtotal' => $invoice->price * $invoice->amount,
            ]);

            // Create Factura directly
            $ultimaFactura = \App\Models\Factura::latest('id')->first();
            $siguiente = $ultimaFactura ? $ultimaFactura->id + 1 : 1;
            $numeroFactura = 'FAC-' . str_pad($siguiente, 6, '0', STR_PAD_LEFT);

            $factura = \App\Models\Factura::create([
                'venta_id' => $venta->id,
                'cliente_id' => $customer->id,
                'numero_factura' => $numeroFactura,
                'subtotal' => $venta->subtotal,
                'total' => $venta->total,
                'estado' => 'emitida',
                'fecha_emision' => $venta->fecha,
            ]);

            \App\Models\FacturaDetalle::create([
                'factura_id' => $factura->id,
                'producto_id' => $producto->id,
                'cantidad' => $invoice->amount,
                'precio_unitario' => $invoice->price,
                'subtotal' => $invoice->price * $invoice->amount,
            ]);
            
            // Delete the old invoice to avoid migrating again
            $invoice->delete();
        }
        echo "Migración completada con éxito.\n";
    });
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
