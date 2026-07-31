<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaAperturaCierre extends Model
{
    protected $table = 'caja_apertura_cierres';

    protected $fillable = [
        'fecha',
        'saldo_inicial',
        'ventas_efectivo',
        'ventas_transferencia',
        'ventas_tarjeta',
        'ventas_nequi',
        'ventas_daviplata',
        'total_ventas',
        'entradas_adicionales',
        'salidas_gastos',
        'saldo_esperado',
        'dinero_contado',
        'diferencia',
        'estado',
        'fecha_apertura',
        'fecha_cierre',
        'observacion_apertura',
        'observacion_cierre',
    ];

    protected $casts = [
        'fecha' => 'date',
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
        'saldo_inicial' => 'decimal:2',
        'ventas_efectivo' => 'decimal:2',
        'ventas_transferencia' => 'decimal:2',
        'ventas_tarjeta' => 'decimal:2',
        'ventas_nequi' => 'decimal:2',
        'ventas_daviplata' => 'decimal:2',
        'total_ventas' => 'decimal:2',
        'entradas_adicionales' => 'decimal:2',
        'salidas_gastos' => 'decimal:2',
        'saldo_esperado' => 'decimal:2',
        'dinero_contado' => 'decimal:2',
        'diferencia' => 'decimal:2',
    ];
}
