<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')
                ->nullable()
                ->constrained('clientes')
                ->nullOnDelete();

            $table->string('numero_venta')->unique();
            $table->enum('metodo_pago', [
                'efectivo',
                'transferencia',
                'tarjeta',
                'nequi',
                'daviplata'
            ]);

            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0);
            $table->decimal('dinero_recibido', 12, 2)->default(0);
            $table->decimal('cambio', 12, 2)->default(0);

            $table->enum('estado', ['pagada', 'anulada'])->default('pagada');
            $table->date('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
