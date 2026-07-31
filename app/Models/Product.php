<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    public $timestamps = true;

    protected $fillable = [
    'name',
    'barcode',
    'supplier',
    'price',
    'cost',
    'stock',
    'category',
    'unit_of_measurement',
    'photo'
];

    /**
     * URL lista para mostrar la foto del producto.
     * - Si es una foto subida (ruta en storage) devuelve asset('storage/...').
     * - Si es una URL antigua (http...) la devuelve tal cual.
     * - Si no hay foto, devuelve un placeholder.
     */
    public function getPhotoUrlAttribute(): string
    {
        $photo = $this->photo;

        if (!$photo) {
            return 'https://via.placeholder.com/150?text=Sin+foto';
        }

        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        return asset('storage/' . $photo);
    }
}