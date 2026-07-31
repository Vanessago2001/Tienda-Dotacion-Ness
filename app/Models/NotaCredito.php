<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotaCredito extends Model
{
    protected $table = 'notas_credito';

    protected $fillable = [
        'factura_id',
        'numero_nota_credito',
        'subtotal',
        'total',
        'motivo',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(NotaCreditoDetalle::class);
    }
}
