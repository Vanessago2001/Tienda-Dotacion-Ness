<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoCaja extends Model
{
    protected $table = 'movimiento_cajas';

    protected $fillable = [
        'tipo',
        'concepto',
        'categoria',
        'valor',
        'metodo_pago',
        'descripcion',
        'fecha',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'valor' => 'decimal:2',
    ];
}
