<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caja_apertura_cierres', function (Blueprint $table) {
            $table->id();
            $table->date('fecha');
            $table->decimal('saldo_inicial', 12, 2)->default(0);
            $table->decimal('ventas_efectivo', 12, 2)->default(0);
            $table->decimal('ventas_transferencia', 12, 2)->default(0);
            $table->decimal('ventas_tarjeta', 12, 2)->default(0);
            $table->decimal('ventas_nequi', 12, 2)->default(0);
            $table->decimal('ventas_daviplata', 12, 2)->default(0);
            $table->decimal('total_ventas', 12, 2)->default(0);
            $table->decimal('entradas_adicionales', 12, 2)->default(0);
            $table->decimal('salidas_gastos', 12, 2)->default(0);
            $table->decimal('saldo_esperado', 12, 2)->default(0);
            $table->decimal('dinero_contado', 12, 2)->nullable();
            $table->decimal('diferencia', 12, 2)->default(0);
            $table->enum('estado', ['abierta', 'cerrada'])->default('abierta');
            $table->dateTime('fecha_apertura');
            $table->dateTime('fecha_cierre')->nullable();
            $table->text('observacion_apertura')->nullable();
            $table->text('observacion_cierre')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caja_apertura_cierres');
    }
};
