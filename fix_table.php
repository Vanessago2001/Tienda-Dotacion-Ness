<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    if (!\Illuminate\Support\Facades\Schema::hasTable('nota_credito_detalles')) {
        $migration = require __DIR__ . '/database/migrations/2026_06_12_205004_create_nota_credito_detalles_table.php';
        $migration->up();
        echo "Tabla nota_credito_detalles creada exitosamente.\n";
    } else {
        echo "La tabla ya existía.\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
