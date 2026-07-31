<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    /**
     * Usuarios que tienen este rol (relación por el slug guardado en users.role).
     */
    public function users()
    {
        return $this->hasMany(User::class, 'role', 'slug');
    }
}
