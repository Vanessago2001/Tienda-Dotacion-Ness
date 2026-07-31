<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movimiento_cajas', function (Blueprint $table) {
            $table->id();

            $table->enum('tipo', ['entrada', 'salida']);
            $table->string('concepto');

            $table->enum('categoria', [
                'aporte',
                'prestamo',
                'ajuste_caja',
                'arriendo',
                'servicios',
                'transporte',
                'compra_insumos',
                'nomina',
                'mantenimiento',
                'otro'
            ])->default('otro');

            $table->decimal('valor', 12, 2);
            $table->string('metodo_pago')->nullable();
            $table->text('descripcion')->nullable();
            $table->date('fecha');
            $table->enum('estado', ['activo', 'anulado'])->default('activo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_cajas');
    }
};
