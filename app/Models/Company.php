<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'nit',
        'address',
        'phone',
        'email',
        'city',
        'logo'
    ];

    /**
     * URL lista para mostrar el logo.
     * - Archivo subido (ruta en storage) -> asset('storage/...').
     * - URL antigua (http...) -> se devuelve tal cual.
     * - Sin logo -> placeholder.
     */
    public function getLogoUrlAttribute(): string
    {
        $logo = $this->logo;

        if (!$logo) {
            return 'https://via.placeholder.com/150?text=Logo';
        }

        if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
            return $logo;
        }

        return asset('storage/' . $logo);
    }
}