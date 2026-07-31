<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventarios';

    protected $fillable = [
        'product_id',
        'user_id',
        'tipo',
        'cantidad',
        'stock_anterior',
        'stock_nuevo',
        'motivo',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Registra un movimiento de inventario (solo deja el rastro, no cambia el stock).
     */
    public static function registrar(
        int $productId,
        string $tipo,
        int $cantidad,
        int $stockAnterior,
        int $stockNuevo,
        ?string $motivo = null
    ): void {
        static::create([
            'product_id'     => $productId,
            'user_id'        => auth()->id(),
            'tipo'           => $tipo,
            'cantidad'       => $cantidad,
            'stock_anterior' => $stockAnterior,
            'stock_nuevo'    => $stockNuevo,
            'motivo'         => $motivo,
            'fecha'          => now()->toDateString(),
        ]);
    }
}
